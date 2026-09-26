<?php

namespace Tests\Feature;

use App\Models\AssessmentSession;
use App\Models\DiagnosticQuestion;
use App\Models\DiagnosticSession;
use App\Models\User;
use App\Services\NumeracyAiAgentService;
use Database\Seeders\DiagnosticQuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DiagnosticWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DiagnosticQuestionSeeder::class);
        $this->user = User::factory()->create([
            'name' => 'Ario Adiyoso',
            'email' => 'ario@example.com',
        ]);
    }

    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get(route('diagnostic.landing'));

        $response->assertStatus(200);
        $response->assertSee('Nalaria');
        $response->assertSee('Get Started');
    }

    public function test_guest_is_redirected_to_login_when_accessing_quiz(): void
    {
        $response = $this->get(route('diagnostic.quiz'));

        $response->assertRedirect(route('login'));
    }

    public function test_quiz_page_renders_with_seeded_questions_for_authenticated_user(): void
    {
        $response = $this->actingAs($this->user)->get(route('diagnostic.quiz'));

        $response->assertStatus(200);
        $response->assertSee('Uji Penalaran Numerasi Kontekstual');
        $response->assertSee('Diskon Bertingkat Gerai Daur Ulang Sekolah');
        $response->assertSee('Pemodelan Efisiensi Biaya Listrik Tenaga Surya');
    }

    public function test_quiz_submission_triggers_ai_agent_actions_and_persists_data(): void
    {
        $questions = DiagnosticQuestion::orderBy('order')->get();
        $this->assertCount(5, $questions);

        // Siswa menjawab:
        // Soal 1: opsi A (salah, miskonsepsi additive 50+20=70)
        // Soal 2: opsi C (benar)
        // Soal 3: opsi C (benar)
        // Soal 4: opsi B (benar)
        // Soal 5: opsi B (benar)
        $payload = [
            'student_name' => 'Ario Adiyoso',
            'student_grade' => 'Kelas 10 SMK (RPL / Vokasi)',
            'time_spent' => 145,
            'answers' => [
                $questions[0]->id => 'A',
                $questions[1]->id => 'C',
                $questions[2]->id => 'C',
                $questions[3]->id => 'B',
                $questions[4]->id => 'B',
            ],
            'reasoning' => [
                $questions[0]->id => 'Karena 50 persen ditambah 20 persen sama dengan 70 persen diskon total.',
                $questions[1]->id => 'Tarif per kWh adalah variabel dan abonemen adalah konstanta tetap.',
                $questions[2]->id => 'Mengonversi ukuran sisi dari cm ke meter sebelum menghitung luas.',
                $questions[3]->id => 'Jumlah siswa kelas A lebih banyak sehingga perlu bobot yang lebih besar.',
                $questions[4]->id => 'Rasio 1 banding 4 totalnya 5 bagian sehingga per bagian 2 liter.',
            ],
        ];

        $response = $this->actingAs($this->user)->post(route('diagnostic.submit'), $payload);

        // 1. Redirect ke result
        $session = DiagnosticSession::first();
        $this->assertNotNull($session);
        $response->assertRedirect(route('diagnostic.result', ['code' => $session->session_code]));

        // 2. Verifikasi Data Sesi & AI Agent Aksi 1 (Diagnosis)
        $this->assertEquals('Ario Adiyoso', $session->student_name);
        $this->assertEquals(80, $session->score); // 4 dari 5 benar = 80%
        $this->assertEquals(4, $session->correct_count);
        $this->assertEquals(5, $session->total_questions);
        $this->assertNotEmpty($session->ai_diagnosis_summary);
        $this->assertStringContainsString('Additive', $session->primary_misconception);

        // 3. Verifikasi Student Responses
        $this->assertDatabaseCount('student_responses', 5);
        $this->assertDatabaseHas('student_responses', [
            'diagnostic_session_id' => $session->id,
            'diagnostic_question_id' => $questions[0]->id,
            'selected_option' => 'A',
            'is_correct' => false,
        ]);

        // 4. Verifikasi AI Agent Aksi 2 (Generate Latihan Adaptif)
        $this->assertDatabaseCount('adaptive_practice_questions', 1);
        $adaptiveItem = $session->adaptivePracticeQuestions()->first();
        $this->assertNotNull($adaptiveItem);
        $this->assertNotEmpty($adaptiveItem->scaffolding_hint);
        $this->assertNotEmpty($adaptiveItem->conceptual_explanation);
    }

    public function test_result_page_renders_with_session_and_practice_questions(): void
    {
        $session = DiagnosticSession::create([
            'session_code' => 'NAL-TEST01',
            'student_name' => 'Budi Pratama',
            'student_grade' => 'Kelas 8 SMP',
            'score' => 60,
            'total_questions' => 5,
            'correct_count' => 3,
            'mastery_level' => 'Cakap (Level 3 PISA)',
            'primary_misconception' => 'Miskonsepsi Skala Linear pada Luas 2D',
            'ai_diagnosis_summary' => 'Analisis kognitif menunjukkan pemahaman baik pada aljabar namun lemah pada geometri.',
            'ai_remediation_plan' => 'Latihan fokus pada skala dua dimensi.',
            'status' => 'completed',
            'time_spent_seconds' => 120,
        ]);

        $response = $this->actingAs($this->user)->get(route('diagnostic.result', ['code' => 'NAL-TEST01']));

        $response->assertStatus(200);
        $response->assertSee('Budi Pratama');
        $response->assertSee('NAL-TEST01');
        $response->assertSee('60');
        $response->assertSee('Cakap');
    }

    public function test_submit_practice_answer_validates_and_gives_feedback(): void
    {
        $session = DiagnosticSession::create([
            'session_code' => 'NAL-PRACT01',
            'student_name' => 'Citra Lestari',
            'student_grade' => 'Kelas 10 SMA',
            'score' => 40,
            'total_questions' => 5,
            'correct_count' => 2,
            'status' => 'completed',
        ]);

        $practice = $session->adaptivePracticeQuestions()->create([
            'target_misconception' => 'Additive error on sequential percentages',
            'domain' => 'aritmatika_sosial',
            'title' => 'Latihan Adaptif Uji Coba',
            'context_scenario' => 'Diskon 25% + 10%',
            'question_text' => 'Berapa harga akhir?',
            'options' => [
                ['key' => 'A', 'text' => 'Salah'],
                ['key' => 'B', 'text' => 'Benar Rp54.000'],
            ],
            'correct_answer' => 'B',
            'scaffolding_hint' => 'Hitung dulu 25%, lalu kurangkan.',
            'conceptual_explanation' => 'Diskon bertingkat tidak dijumlahkan langsung.',
            'difficulty' => 'Sedang',
        ]);

        $response = $this->actingAs($this->user)->postJson(route('diagnostic.practice.submit', [
            'code' => 'NAL-PRACT01',
            'questionId' => $practice->id,
        ]), [
            'answer' => 'B',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_correct' => true,
        ]);

        $this->assertDatabaseHas('adaptive_practice_questions', [
            'id' => $practice->id,
            'is_solved' => true,
            'student_answer' => 'B',
        ]);
    }

    public function test_openrouter_api_integration_diagnoses_with_nemotron_model(): void
    {
        // Mock OpenRouter Chat Completion API response
        Http::fake([
            'https://openrouter.ai/api/v1/chat/completions' => Http::response([
                'id' => 'gen-test-12345',
                'model' => 'nvidia/nemotron-3-ultra-550b-a55b:free',
                'choices' => [
                    [
                        'message' => [
                            'role' => 'assistant',
                            'content' => json_encode([
                                'primary_misconception' => 'Additive Trap on Multi-stage Percentages',
                                'domain' => 'aritmatika_sosial',
                                'root_cause' => 'Siswa menjumlahkan 50% + 20% secara aditif alih-alih mengalikan faktor pengali diskon bertingkat berturut-turut.',
                                'mastery_level' => 'Dasar (Level 2 PISA)',
                                'diagnostic_summary' => 'Siswa memiliki intuisi logika belanja yang baik namun terjebak pada sifat operasi persentase sekuensial.',
                                'remediation_advice' => 'Gunakan analogi nilai sisa bertahap: Rp100.000 menjadi Rp50.000, lalu didiskon lagi 20% dari Rp50.000 menjadi Rp40.000.',
                                'domain_scores' => [
                                    'aljabar' => 100,
                                    'geometri' => 100,
                                    'aritmatika_sosial' => 0,
                                    'statistika' => 100,
                                ],
                            ]),
                        ],
                    ],
                ],
            ], 200),
        ]);

        $session = DiagnosticSession::create([
            'session_code' => 'NAL-ORTEST',
            'student_name' => 'Ario Adiyoso',
            'student_grade' => 'Kelas 10 SMK',
            'status' => 'in_progress',
        ]);

        $submittedAnswers = [
            [
                'question_id' => 1,
                'selected_option' => 'A',
                'reasoning' => '50% + 20% = 70% diskon langsung',
            ],
        ];

        $aiService = app(NumeracyAiAgentService::class);
        $result = $aiService->diagnoseSession($session, $submittedAnswers);

        $this->assertNotEmpty($result['primary_misconception']);
        $this->assertNotEmpty($result['ai_diagnosis_summary']);
        $this->assertEquals('completed', $session->fresh()->status);
    }

    public function test_history_page_requires_authentication(): void
    {
        $response = $this->get(route('diagnostic.history'));
        $response->assertRedirect(route('login'));
    }

    public function test_history_page_renders_with_user_sessions_and_mastery_score(): void
    {
        // Buat assessment session untuk user
        $session = AssessmentSession::create([
            'session_code' => 'NAL-HIST01',
            'user_id' => $this->user->id,
            'student_name' => $this->user->name,
            'student_grade' => 'Kelas 10 SMK',
            'score' => 80,
            'total_questions' => 5,
            'correct_count' => 4,
            'mastery_level' => 'Mahir (Level 4/5 PISA)',
            'primary_misconception' => 'Additive error on sequential percentages',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->user)->get(route('diagnostic.history'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Asesmen');
        $response->assertSee('NAL-HIST01');
        $response->assertSee('80%');
        $response->assertSee('Mahir');
    }

    public function test_submitting_quiz_persists_assessment_session_and_answers(): void
    {
        $questions = DiagnosticQuestion::orderBy('order')->get();

        $payload = [
            'student_name' => 'Ario Adiyoso',
            'student_grade' => 'Kelas 10 SMK',
            'time_spent' => 90,
            'answers' => [
                $questions[0]->id => 'A',
                $questions[1]->id => 'C',
            ],
            'reasoning' => [
                $questions[0]->id => 'Alasan A',
                $questions[1]->id => 'Alasan C',
            ],
        ];

        $response = $this->actingAs($this->user)->post(route('diagnostic.submit'), $payload);

        $response->assertSessionHasNoErrors();

        // Verifikasi AssessmentSession
        $assessmentSession = AssessmentSession::where('user_id', $this->user->id)->first();
        $this->assertNotNull($assessmentSession);
        $this->assertEquals('Ario Adiyoso', $assessmentSession->student_name);
        $this->assertDatabaseHas('answers', [
            'session_id' => $assessmentSession->id,
            'diagnostic_question_id' => $questions[0]->id,
            'student_answer' => 'A',
            'is_correct' => false,
            'misconception_category' => 'Aritmatika Sosial',
        ]);
        $this->assertDatabaseHas('recommendations', [
            'session_id' => $assessmentSession->id,
        ]);
    }

    public function test_adaptive_difficulty_assigns_menantang_for_high_performing_user_history(): void
    {
        // Buat riwayat sesi sebelumnya dengan skor tinggi
        AssessmentSession::create([
            'session_code' => 'NAL-PAST85',
            'user_id' => $this->user->id,
            'student_name' => $this->user->name,
            'score' => 90,
            'status' => 'completed',
        ]);

        $newSession = AssessmentSession::create([
            'session_code' => 'NAL-CURR90',
            'user_id' => $this->user->id,
            'student_name' => $this->user->name,
            'score' => 85,
            'status' => 'in_progress',
        ]);

        $aiService = app(NumeracyAiAgentService::class);
        $profile = $aiService->analyzeHistoricalPerformance($newSession, 85);

        $this->assertEquals('Menantang', $profile['difficulty_level']);
        $this->assertEquals(90, $profile['avg_previous_score']);
        $this->assertStringContainsString('LEBIH MENANTANG', $profile['prompt_instruction']);
    }

    public function test_adaptive_difficulty_assigns_mudah_for_low_performing_user_history(): void
    {
        // Buat riwayat sesi sebelumnya dengan skor rendah
        AssessmentSession::create([
            'session_code' => 'NAL-PAST30',
            'user_id' => $this->user->id,
            'student_name' => $this->user->name,
            'score' => 35,
            'status' => 'completed',
        ]);

        $newSession = AssessmentSession::create([
            'session_code' => 'NAL-CURR40',
            'user_id' => $this->user->id,
            'student_name' => $this->user->name,
            'score' => 40,
            'status' => 'in_progress',
        ]);

        $aiService = app(NumeracyAiAgentService::class);
        $profile = $aiService->analyzeHistoricalPerformance($newSession, 40);

        $this->assertEquals('Mudah', $profile['difficulty_level']);
        $this->assertEquals(35, $profile['avg_previous_score']);
        $this->assertStringContainsString('LEBIH MUDAH', $profile['prompt_instruction']);
    }

    public function test_generate_adaptive_practice_persists_adaptive_difficulty_level(): void
    {
        // User dengan riwayat skor rendah
        AssessmentSession::create([
            'session_code' => 'NAL-LOW01',
            'user_id' => $this->user->id,
            'student_name' => $this->user->name,
            'score' => 30,
            'status' => 'completed',
        ]);

        $diagSession = DiagnosticSession::create([
            'session_code' => 'NAL-TESTADAPT',
            'student_name' => $this->user->name,
            'score' => 40,
            'status' => 'in_progress',
        ]);

        $currAssessmentSession = AssessmentSession::create([
            'session_code' => 'NAL-TESTADAPT',
            'user_id' => $this->user->id,
            'student_name' => $this->user->name,
            'score' => 40,
            'status' => 'in_progress',
        ]);

        $aiService = app(NumeracyAiAgentService::class);
        $diagnosis = [
            'score' => 40,
            'detected_misconceptions' => [
                [
                    'domain' => 'aritmatika_sosial',
                    'misconception' => 'Additive error on sequential discounts',
                ],
            ],
            'domain_scores' => [
                'aritmatika_sosial' => ['total' => 1, 'correct' => 0],
            ],
        ];

        $practices = $aiService->generateAdaptivePractice($diagSession, $diagnosis, $currAssessmentSession);

        $this->assertNotEmpty($practices);
        $firstPractice = $practices->first();
        $this->assertEquals('Mudah', $firstPractice->difficulty);

        // Verifikasi di recommendations
        $this->assertDatabaseHas('recommendations', [
            'session_id' => $currAssessmentSession->id,
            'difficulty_level' => 'Mudah',
        ]);
    }

    public function test_generator_page_renders_successfully(): void
    {
        $response = $this->get(route('diagnostic.generator'));

        $response->assertStatus(200);
        $response->assertSee('Generator Soal Literasi & Numerasi', false);
        $response->assertSee('Konfigurasi Paket Soal');
        $response->assertSee('Generate Paket Soal AI');
        $response->assertSee('Aritmatika Sosial');
    }

    public function test_navbar_contains_generator_soal_ai_link(): void
    {
        $response = $this->get(route('diagnostic.landing'));

        $response->assertStatus(200);
        $response->assertSee(route('diagnostic.generator'));
        $response->assertSee('Generator Soal AI');
    }

    public function test_generator_api_returns_json_on_ajax_request(): void
    {
        $payload = [
            'domain' => 'aljabar',
            'difficulty' => 'Menantang',
            'context' => 'Penghematan Panel Surya Sekolah',
        ];

        $response = $this->postJson(route('diagnostic.generator.generate'), $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'question' => [
                'domain',
                'domain_label',
                'difficulty',
                'title',
                'context_scenario',
                'question_text',
                'options',
                'correct_answer',
                'scaffolding_hint',
                'conceptual_explanation',
                'ai_model',
            ],
        ]);

        $this->assertEquals('aljabar', $response->json('question.domain'));
        $this->assertEquals('Menantang', $response->json('question.difficulty'));
    }

    public function test_generator_api_dispatches_every_question_and_keeps_each_answer_with_its_own_prompt(): void
    {
        config(['services.openrouter.key' => 'test-key']);

        // Balasan AI menandai nomor butir & domain yang diminta, agar misalignment
        // antara prompt dan hasil (akibat pemetaan indeks) terdeteksi.
        Http::fake(function ($request) {
            $prompt = $request['messages'][1]['content'];
            preg_match('/Butir Soal Ke-(\d+)/', $prompt, $numberMatch);
            preg_match('/- Domain: [^(]+\(([a-z_]+)\)/', $prompt, $domainMatch);
            $number = $numberMatch[1] ?? '?';
            $domain = $domainMatch[1] ?? '?';

            return Http::response([
                'choices' => [[
                    'message' => [
                        'content' => json_encode([
                            'title' => "Butir #{$number} domain {$domain}",
                            'context_scenario' => 'Stimulus kontekstual.',
                            'question_text' => "Pertanyaan untuk {$domain} nomor {$number}?",
                            'options' => [
                                ['key' => 'A', 'text' => 'Pilihan A'],
                                ['key' => 'B', 'text' => 'Pilihan B'],
                                ['key' => 'C', 'text' => 'Pilihan C'],
                                ['key' => 'D', 'text' => 'Pilihan D'],
                            ],
                            'correct_answer' => 'B',
                            'scaffolding_hint' => 'Petunjuk bernalar.',
                            'conceptual_explanation' => 'Pembahasan langkah demi langkah.',
                        ]),
                    ],
                ]],
            ], 200);
        });

        $expectedRotation = [
            'literasi_informasi',
            'aritmatika_sosial',
            'aljabar',
            'geometri',
            'data_ketidakpastian',
            'literasi_informasi',
            'aritmatika_sosial',
            'geometri',
        ];

        $response = $this->postJson(route('diagnostic.generator.generate'), [
            'domain' => 'campuran',
            'difficulty' => 'Sedang',
            'count' => 8,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $questions = $response->json('package.questions');
        $this->assertCount(8, $questions);

        // Setiap hasil harus menempel pada prompt domain-nya sendiri, tidak tertukar.
        foreach ($questions as $index => $question) {
            $this->assertSame($expectedRotation[$index], $question['domain']);
            $this->assertSame(
                'Butir #'.($index + 1).' domain '.$expectedRotation[$index],
                $question['title']
            );
        }

        // Satu request OpenRouter per butir soal, dikirim sekaligus (paralel).
        Http::assertSentCount(8);
    }

    public function test_generator_api_falls_back_to_local_bank_when_openrouter_is_rate_limited(): void
    {
        config(['services.openrouter.key' => 'test-key']);

        Http::fake([
            'https://openrouter.ai/api/v1/chat/completions' => Http::response([
                'error' => ['message' => 'Rate limit exceeded: free-models-per-day'],
            ], 429),
        ]);

        $response = $this->postJson(route('diagnostic.generator.generate'), [
            'domain' => 'geometri',
            'difficulty' => 'Menantang',
            'count' => 4,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertCount(4, $response->json('package.questions'));

        foreach ($response->json('package.questions') as $question) {
            $this->assertSame('geometri', $question['domain']);
            $this->assertNotEmpty($question['question_text']);
            $this->assertNotEmpty($question['options']);
        }
    }

    public function test_generator_api_falls_back_to_local_bank_when_openrouter_returns_unparsable_json(): void
    {
        config(['services.openrouter.key' => 'test-key']);

        Http::fake([
            'https://openrouter.ai/api/v1/chat/completions' => Http::response([
                'choices' => [[
                    'message' => ['content' => 'Maaf, saya tidak bisa menjawab dalam format JSON.'],
                ]],
            ], 200),
        ]);

        $response = $this->postJson(route('diagnostic.generator.generate'), [
            'domain' => 'aljabar',
            'difficulty' => 'Mudah',
            'count' => 2,
        ]);

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('package.questions'));

        foreach ($response->json('package.questions') as $question) {
            $this->assertSame('aljabar', $question['domain']);
        }
    }

    public function test_result_page_renders_markdown_bold_tags_for_ai_diagnosis(): void
    {
        $session = DiagnosticSession::create([
            'session_code' => 'NAL-BOLDTEST',
            'student_name' => 'Ario Adiyoso',
            'student_grade' => 'Kelas 10 SMK (RPL / Vokasi)',
            'score' => 80,
            'total_questions' => 5,
            'correct_count' => 4,
            'mastery_level' => 'Cakap (Level 3 PISA)',
            'primary_misconception' => 'Miskonsepsi **Diskon Bertingkat**',
            'ai_diagnosis_summary' => "**Paragraf 1 — Analisis Pola Penalaran dan Miskonsepsi**\n\nSiswa cenderung menjumlahkan diskon secara linier.",
            'ai_remediation_plan' => 'Lakukan latihan **persentase majemuk** bertahap.',
            'status' => 'completed',
            'time_spent_seconds' => 120,
        ]);

        $response = $this->actingAs($this->user)->get(route('diagnostic.result', ['code' => 'NAL-BOLDTEST']));

        $response->assertStatus(200);
        // Memastikan tag bold <strong> dirender dan bukan simbol literal **
        $response->assertSee('<strong>Paragraf 1 — Analisis Pola Penalaran dan Miskonsepsi</strong>', false);
        $response->assertSee('<strong>Diskon Bertingkat</strong>', false);
        $response->assertSee('<strong>persentase majemuk</strong>', false);
        $response->assertDontSee('**Paragraf 1 — Analisis Pola Penalaran dan Miskonsepsi**');
    }
}
