<?php

namespace Tests\Feature;

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
}
