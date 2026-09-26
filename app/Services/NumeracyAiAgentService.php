<?php

namespace App\Services;

use App\Models\AdaptivePracticeQuestion;
use App\Models\Answer;
use App\Models\AssessmentSession;
use App\Models\DiagnosticQuestion;
use App\Models\DiagnosticSession;
use App\Models\Recommendation;
use App\Models\StudentResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NumeracyAiAgentService
{
    /**
     * Memetakan domain ke salah satu Kategori Standar AKM & PISA:
     * - Literasi Informasi & Sains
     * - Aljabar
     * - Geometri Spasial
     * - Aritmatika Sosial
     * - Data & Statistik
     * - Paket Terpadu Literasi & Numerasi
     */
    public static function mapDomainToCategory(?string $domain): string
    {
        return match (strtolower(trim($domain ?? ''))) {
            'literasi', 'literasi_informasi' => 'Literasi Informasi & Sains',
            'aljabar' => 'Aljabar',
            'geometri', 'geometri_spasial' => 'Geometri Spasial',
            'data_ketidakpastian', 'statistika', 'data & statistik' => 'Data & Statistik',
            'campuran' => 'Paket Terpadu Literasi & Numerasi',
            default => 'Aritmatika Sosial',
        };
    }

    /**
     * Memetakan domain ke Klasifikasi Kompetensi Inti (Literasi / Numerasi)
     */
    public static function mapDomainToCompetency(?string $domain): string
    {
        return match (strtolower(trim($domain ?? ''))) {
            'literasi', 'literasi_informasi' => 'Literasi',
            'campuran' => 'Literasi & Numerasi',
            default => 'Numerasi',
        };
    }

    /**
     * ACTION 1: Diagnosa Miskonsepsi & Profiling Kognitif Penalaran Siswa
     *
     * @param  array  $submittedAnswers  Array of ['question_id' => int, 'selected_option' => string, 'reasoning' => string]
     */
    public function diagnoseSession(DiagnosticSession $session, array $submittedAnswers, ?AssessmentSession $assessmentSession = null): array
    {
        $questions = DiagnosticQuestion::all()->keyBy('id');
        $totalQuestions = count($submittedAnswers);
        $correctCount = 0;
        $detectedMisconceptions = [];
        $domainScores = [
            'aritmatika_sosial' => ['total' => 0, 'correct' => 0],
            'aljabar' => ['total' => 0, 'correct' => 0],
            'geometri' => ['total' => 0, 'correct' => 0],
            'data_ketidakpastian' => ['total' => 0, 'correct' => 0],
        ];

        foreach ($submittedAnswers as $ans) {
            $questionId = (int) ($ans['question_id'] ?? 0);
            $selectedOption = strtoupper(trim($ans['selected_option'] ?? ''));
            $studentReasoning = trim($ans['reasoning'] ?? '');

            if (! isset($questions[$questionId])) {
                continue;
            }

            $question = $questions[$questionId];
            $isCorrect = ($selectedOption === $question->correct_answer);

            if ($isCorrect) {
                $correctCount++;
            }

            // Domain breakdown
            $domain = $question->domain;
            if (! isset($domainScores[$domain])) {
                $domainScores[$domain] = ['total' => 0, 'correct' => 0];
            }
            $domainScores[$domain]['total']++;
            if ($isCorrect) {
                $domainScores[$domain]['correct']++;
            }

            // Misconception mapping
            $misconception = null;
            if (! $isCorrect && ! empty($question->misconception_map[$selectedOption])) {
                $misconception = $question->misconception_map[$selectedOption];
                $detectedMisconceptions[] = [
                    'question_title' => $question->title,
                    'domain' => $domain,
                    'selected_option' => $selectedOption,
                    'misconception' => $misconception,
                    'student_reasoning' => $studentReasoning,
                ];
            }

            // Simpan record response siswa (Fase 1)
            StudentResponse::create([
                'diagnostic_session_id' => $session->id,
                'diagnostic_question_id' => $question->id,
                'selected_option' => $selectedOption,
                'is_correct' => $isCorrect,
                'student_reasoning' => $studentReasoning,
                'detected_misconception' => $misconception,
            ]);

            // Simpan record ke tabel answers (Fase 2 multi-sesi schema)
            if ($assessmentSession) {
                $misconceptionCategory = ! $isCorrect ? self::mapDomainToCategory($domain) : null;
                Answer::create([
                    'session_id' => $assessmentSession->id,
                    'diagnostic_question_id' => $question->id,
                    'question_text' => $question->question_text,
                    'student_answer' => $selectedOption,
                    'student_reasoning' => $studentReasoning,
                    'is_correct' => $isCorrect,
                    'misconception_category' => $misconceptionCategory,
                    'misconception_detail' => $misconception,
                ]);
            }
        }

        $calculatedScore = $totalQuestions > 0 ? (int) round(($correctCount / $totalQuestions) * 100) : 0;

        // Level AKM / PISA
        $masteryLevel = match (true) {
            $calculatedScore >= 80 => 'Mahir (Level 4/5 PISA)',
            $calculatedScore >= 60 => 'Cakap (Level 3 PISA)',
            $calculatedScore >= 40 => 'Dasar (Level 2 PISA)',
            default => 'Perlu Intervensi Khusus (Level 1 PISA)',
        };

        // Identifikasi miskonsepsi primer
        $primaryMisconception = null;
        if (! empty($detectedMisconceptions)) {
            $primaryMisconception = $detectedMisconceptions[0]['misconception'];
        } elseif ($calculatedScore === 100) {
            $primaryMisconception = 'Tidak ada miskonsepsi terdeteksi. Pemahaman konsep kontekstual sangat solid.';
        } else {
            $primaryMisconception = 'Ketidaktelitian membaca variabel atau konversi unit kontekstual.';
        }

        // Generate AI Diagnosis Summary (LLM API dengan intelligent fallback)
        $aiDiagnosisSummary = $this->callAiForDiagnosis(
            $session->student_name,
            $calculatedScore,
            $masteryLevel,
            $domainScores,
            $detectedMisconceptions
        );

        // Update Sesi Fase 1
        $session->update([
            'score' => $calculatedScore,
            'total_questions' => $totalQuestions,
            'correct_count' => $correctCount,
            'mastery_level' => $masteryLevel,
            'primary_misconception' => $primaryMisconception,
            'domain_scores' => $domainScores,
            'ai_diagnosis_summary' => $aiDiagnosisSummary,
            'status' => 'completed',
        ]);

        // Update Sesi Fase 2 (AssessmentSession)
        if ($assessmentSession) {
            $assessmentSession->update([
                'score' => $calculatedScore,
                'total_questions' => $totalQuestions,
                'correct_count' => $correctCount,
                'mastery_level' => $masteryLevel,
                'primary_misconception' => $primaryMisconception,
                'domain_scores' => $domainScores,
                'ai_diagnosis_summary' => $aiDiagnosisSummary,
                'status' => 'completed',
            ]);
        }

        return [
            'score' => $calculatedScore,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'mastery_level' => $masteryLevel,
            'domain_scores' => $domainScores,
            'primary_misconception' => $primaryMisconception,
            'detected_misconceptions' => $detectedMisconceptions,
            'ai_diagnosis_summary' => $aiDiagnosisSummary,
        ];
    }

    /**
     * Menganalisis riwayat performa user dari sesi-sesi sebelumnya untuk menentukan
     * tingkat kesulitan adaptif (Tahap 6):
     * - Skor sebelumnya tinggi (>= 80): Tingkat kesulitan ditingkatkan (Menantang/Sulit, HOTS Level 4/5)
     * - Skor sebelumnya sedang (60 - 79): Tingkat kesulitan standar (Sedang, Level 3 PISA)
     * - Skor sebelumnya rendah (< 60): Tingkat kesulitan dipermudah (Mudah, Level 2 PISA fondasi dasar)
     *
     * @return array{
     *     difficulty_level: string,
     *     blended_score: int,
     *     avg_previous_score: ?int,
     *     previous_sessions_count: int,
     *     prompt_instruction: string
     * }
     */
    public function analyzeHistoricalPerformance(?AssessmentSession $assessmentSession, int $currentScore): array
    {
        $userId = $assessmentSession?->user_id;

        $previousSessions = collect();
        if ($userId) {
            $previousSessions = AssessmentSession::where('user_id', $userId)
                ->when($assessmentSession?->id, fn ($q) => $q->where('id', '!=', $assessmentSession->id))
                ->where('status', 'completed')
                ->orderByDesc('created_at')
                ->get();
        }

        $sessionCount = $previousSessions->count();

        if ($sessionCount > 0) {
            $avgScore = (int) round($previousSessions->avg('score'));
            // Kombinasikan riwayat rata-rata sesi terdahulu (bobot 60%) dengan sesi terkini (40%)
            $blendedScore = (int) round(($avgScore * 0.6) + ($currentScore * 0.4));
        } else {
            $avgScore = null;
            $blendedScore = $currentScore;
        }

        if ($blendedScore >= 80) {
            $difficultyLevel = 'Menantang';
            $guidance = "Siswa memiliki riwayat performa TINGGI (skor gabungan {$blendedScore}%"
                .($avgScore !== null ? ", rerata {$sessionCount} sesi terdahulu {$avgScore}%" : '')
                .'). Buat soal latihan yang LEBIH MENANTANG (HOTS / Level 4-5 PISA). Gunakan skenario kontekstual multivariabel, konversi unit bertingkat, atau penalaran multi-langkah agar memicu perkembangan kognitif lebih tinggi.';
        } elseif ($blendedScore >= 60) {
            $difficultyLevel = 'Sedang';
            $guidance = "Siswa memiliki riwayat performa SEDANG (skor gabungan {$blendedScore}%"
                .($avgScore !== null ? ", rerata {$sessionCount} sesi terdahulu {$avgScore}%" : '')
                .'). Buat soal latihan pada level SEDANG (Level 3 PISA). Fokus pada pemantapan konsep kontekstual dengan alur penalaran terstruktur.';
        } else {
            $difficultyLevel = 'Mudah';
            $guidance = "Siswa memiliki riwayat performa RENDAH (skor gabungan {$blendedScore}%"
                .($avgScore !== null ? ", rerata {$sessionCount} sesi terdahulu {$avgScore}%" : '')
                .'). Buat soal latihan yang LEBIH MUDAH / BERTARAF DASAR (Level 2 PISA). Gunakan angka bulat sederhana, kurangi kerumitan narasi teks, dan berikan petunjuk scaffolding yang sangat memandu langkah bernalar dasar.';
        }

        return [
            'difficulty_level' => $difficultyLevel,
            'blended_score' => $blendedScore,
            'avg_previous_score' => $avgScore,
            'previous_sessions_count' => $sessionCount,
            'prompt_instruction' => $guidance,
        ];
    }

    /**
     * ACTION 2: Generate Paket Soal Latihan Bertarget & Scaffolding Remedial
     * Mengadaptasi tingkat kesulitan berdasarkan riwayat performa sesi sebelumnya (Tahap 6)
     */
    public function generateAdaptivePractice(DiagnosticSession $session, array $diagnosis, ?AssessmentSession $assessmentSession = null): Collection
    {
        $detectedMisconceptions = $diagnosis['detected_misconceptions'] ?? [];
        $domainScores = $diagnosis['domain_scores'] ?? [];
        $currentScore = (int) ($diagnosis['score'] ?? $session->score ?? 0);

        // Analisis performa sesi lalu untuk adaptasi level kesulitan (Tahap 6)
        $adaptiveProfile = $this->analyzeHistoricalPerformance($assessmentSession, $currentScore);
        $targetDifficulty = $adaptiveProfile['difficulty_level'];
        $difficultyPromptInstruction = $adaptiveProfile['prompt_instruction'];

        // Hapus latihan lama jika sesi ini digenerate ulang
        AdaptivePracticeQuestion::where('diagnostic_session_id', $session->id)->delete();

        // Cari domain dengan performa paling rendah
        $weakDomains = [];
        foreach ($domainScores as $dom => $stat) {
            $total = $stat['total'] ?? 1;
            $correct = $stat['correct'] ?? 0;
            $rate = $total > 0 ? ($correct / $total) : 0;
            if ($rate < 1.0) {
                $weakDomains[] = ['domain' => $dom, 'rate' => $rate];
            }
        }
        usort($weakDomains, fn ($a, $b) => $a['rate'] <=> $b['rate']);

        $generatedQuestions = [];

        // Buat 3 paket latihan adaptif bertarget
        if (! empty($detectedMisconceptions)) {
            foreach (array_slice($detectedMisconceptions, 0, 3) as $idx => $item) {
                $targetDomain = $item['domain'];
                $misconceptionName = $item['misconception'];

                // Coba generate via OpenRouter nemotron LLM dengan prompt tingkat kesulitan adaptif
                $aiQuestion = $this->generatePracticeWithOpenRouter(
                    $session,
                    $targetDomain,
                    $misconceptionName,
                    $idx + 1,
                    $targetDifficulty,
                    $difficultyPromptInstruction
                );

                $generatedQuestions[] = $aiQuestion ?: $this->buildAdaptivePracticeItem(
                    $session,
                    $targetDomain,
                    $misconceptionName,
                    $idx + 1,
                    $targetDifficulty
                );
            }
        } else {
            // Jika siswa benar semua atau tidak ada miskonsepsi eksplisit, generate tantangan pengayaan
            $aiQuestion1 = $this->generatePracticeWithOpenRouter(
                $session,
                'aritmatika_sosial',
                'Pengayaan: Optimasi Penganggaran Proyek Energi Mandiri',
                1,
                $targetDifficulty,
                $difficultyPromptInstruction
            );
            $generatedQuestions[] = $aiQuestion1 ?: $this->buildAdaptivePracticeItem(
                $session,
                'aritmatika_sosial',
                'Pengayaan: Optimasi Penganggaran Proyek Energi Mandiri',
                1,
                $targetDifficulty
            );

            $aiQuestion2 = $this->generatePracticeWithOpenRouter(
                $session,
                'data_ketidakpastian',
                'Pengayaan: Analisis Tren Emisi Karbon Sekolah',
                2,
                $targetDifficulty,
                $difficultyPromptInstruction
            );
            $generatedQuestions[] = $aiQuestion2 ?: $this->buildAdaptivePracticeItem(
                $session,
                'data_ketidakpastian',
                'Pengayaan: Analisis Tren Emisi Karbon Sekolah',
                2,
                $targetDifficulty
            );
        }

        // Simpan rencana remediasi umum di sesi dengan label kesulitan adaptif
        $remediationOverview = "Paket latihan adaptif ini disesuaikan ke level [{$targetDifficulty}] berdasarkan analisis riwayat performa numerasi Anda. "
            .'Fokus remediasi pada domain '
            .implode(', ', array_map(fn ($q) => ucfirst(str_replace('_', ' ', $q->domain)), $generatedQuestions))
            .'. Setiap butir soal dilengkapi petunjuk penalaran (scaffolding hint) untuk membimbing langkah berpikir konseptual.';

        $session->update([
            'ai_remediation_plan' => $remediationOverview,
        ]);

        // Simpan ke tabel recommendations (Fase 2 multi-sesi)
        if ($assessmentSession) {
            Recommendation::where('session_id', $assessmentSession->id)->delete();
            foreach ($generatedQuestions as $q) {
                Recommendation::create([
                    'session_id' => $assessmentSession->id,
                    'target_misconception' => $q->target_misconception,
                    'domain' => $q->domain,
                    'title' => $q->title,
                    'context_scenario' => $q->context_scenario,
                    'generated_question' => $q->question_text,
                    'options' => $q->options,
                    'correct_answer' => $q->correct_answer,
                    'explanation' => $q->conceptual_explanation,
                    'scaffolding_hint' => $q->scaffolding_hint,
                    'difficulty_level' => $q->difficulty ?? $targetDifficulty,
                    'is_solved' => false,
                ]);
            }

            $assessmentSession->update([
                'ai_remediation_plan' => $remediationOverview,
            ]);
        }

        return collect($generatedQuestions);
    }

    /**
     * Generate 1 soal latihan adaptif kontekstual secara dinamis menggunakan OpenRouter (nemotron LLM)
     * Menggabungkan parameter target tingkat kesulitan berbasis riwayat performa siswa (Tahap 6)
     */
    protected function generatePracticeWithOpenRouter(
        DiagnosticSession $session,
        string $domain,
        string $misconception,
        int $number,
        string $targetDifficulty = 'Sedang',
        string $difficultyGuidance = ''
    ): ?AdaptivePracticeQuestion {
        $apiKey = config('services.openrouter.key') ?: env('OPENROUTER_API_KEY');
        if (empty($apiKey)) {
            return null;
        }

        $systemPrompt = 'Kamu adalah Pakar Desain Soal Numerasi Kontekstual Indonesia berstandar PISA. '
            .'Hasilkan 1 soal latihan adaptif kontekstual bertema keberlanjutan masa depan (energi surya, daur ulang sampah, penghematan air, atau alokasi anggaran hijau) dalam format JSON valid.';

        $userPrompt = "Buat 1 soal latihan adaptif untuk memperbaiki miskonsepsi: '{$misconception}' pada domain numerasi: '{$domain}'.\n"
            ."TARGET TINGKAT KESULITAN ADAPTIF BERBASIS RIWAYAT SISWA: '{$targetDifficulty}'.\n"
            .(! empty($difficultyGuidance) ? "PEDOMAN KESULITAN RIWAYAT PERFORMA: {$difficultyGuidance}\n\n" : "\n")
            ."Berikan output HANYA berupa JSON valid (tanpa markdown atau teks lain) dengan struktur persis seperti ini:\n"
            ."{\n"
            ."  \"title\": \"Latihan Adaptif #{$number}: ...\",\n"
            ."  \"context_scenario\": \"... cerita skenario situasi nyata ...\",\n"
            ."  \"question_text\": \"... pertanyaan ...\",\n"
            ."  \"options\": [\n"
            ."    {\"key\": \"A\", \"text\": \"...\"},\n"
            ."    {\"key\": \"B\", \"text\": \"...\"},\n"
            ."    {\"key\": \"C\", \"text\": \"...\"},\n"
            ."    {\"key\": \"D\", \"text\": \"...\"}\n"
            ."  ],\n"
            ."  \"correct_answer\": \"B\",\n"
            ."  \"scaffolding_hint\": \"... petunjuk langkah bernalar tanpa membocorkan jawaban ...\",\n"
            ."  \"conceptual_explanation\": \"... penjelasan konsep yang benar ...\"\n"
            .'}';

        $raw = $this->callOpenRouter($systemPrompt, $userPrompt);
        if (! empty($raw)) {
            $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($raw));
            $data = json_decode($cleanJson, true);
            if (is_array($data) && ! empty($data['question_text']) && ! empty($data['options']) && ! empty($data['correct_answer'])) {
                return AdaptivePracticeQuestion::create([
                    'diagnostic_session_id' => $session->id,
                    'target_misconception' => $misconception,
                    'domain' => $domain,
                    'title' => $data['title'] ?? "Latihan Adaptif #{$number}",
                    'context_scenario' => $data['context_scenario'] ?? '',
                    'question_text' => $data['question_text'],
                    'options' => $data['options'],
                    'correct_answer' => strtoupper($data['correct_answer']),
                    'scaffolding_hint' => $data['scaffolding_hint'] ?? '',
                    'conceptual_explanation' => $data['conceptual_explanation'] ?? '',
                    'difficulty' => $targetDifficulty,
                ]);
            }
        }

        return null;
    }

    /**
     * Membangun butir soal adaptif bertarget (AI Generated Item)
     */
    protected function buildAdaptivePracticeItem(
        DiagnosticSession $session,
        string $domain,
        string $targetMisconception,
        int $number,
        string $difficulty = 'Sedang'
    ): AdaptivePracticeQuestion {
        // Template bank generator berbasis pola miskonsepsi
        $item = match ($domain) {
            'aljabar' => [
                'title' => "Latihan Adaptif #{$number}: Pemodelan Biaya Pemilahan Sampah",
                'context_scenario' => 'Sebuah bank sampah sekolah mengenakan biaya sewa wadah pemilah sebesar Rp20.000 per semester, ditambah insentif tabungan sebesar Rp800 untuk setiap kilogram sampah kardus (k) yang disetor siswa.',
                'question_text' => 'Berapakah total saldo tabungan bersih (S) yang diperoleh siswa setelah dikurangi biaya sewa wadah?',
                'options' => [
                    ['key' => 'A', 'text' => 'S = 800k - 20.000'],
                    ['key' => 'B', 'text' => 'S = 20.000k + 800'],
                    ['key' => 'C', 'text' => 'S = 20.800k'],
                    ['key' => 'D', 'text' => 'S = (800 - 20.000)k'],
                ],
                'correct_answer' => 'A',
                'scaffolding_hint' => 'Identifikasi mana besaran yang nilainya tetap tidak berubah (konstanta biaya sewa), dan mana besaran yang bertambah seiring banyaknya kilogram sampah (variabel dengan pengali Rp800).',
                'conceptual_explanation' => 'Insentif yang diterima adalah 800 dikali jumlah kilogram (800k). Biaya sewa adalah potongan tetap (-20.000). Maka model persamaannya adalah S = 800k - 20.000.',
            ],
            'geometri' => [
                'title' => "Latihan Adaptif #{$number}: Perbesaran Taman Apotek Hidup Sekolah",
                'context_scenario' => 'Sebuah denah kebun toga digambar dengan skala 1 : 50. Denah tersebut menunjukkan bedeng tanaman berukuran 6 cm × 8 cm.',
                'question_text' => 'Berapakah luas bedeng tanaman obat sebenarnya di halaman sekolah?',
                'options' => [
                    ['key' => 'A', 'text' => '24 m² (Hasil konversi sisi nyata: 3 m × 4 m)'],
                    ['key' => 'B', 'text' => '12 m² (Hasil konversi sisi nyata: 3 m × 4 m)'],
                    ['key' => 'C', 'text' => '48 m² (Mengalikan luas 48 cm² dengan 50)'],
                    ['key' => 'D', 'text' => '2,4 m² (Salah meletakkan koma desimal)'],
                ],
                'correct_answer' => 'B',
                'scaffolding_hint' => 'Jangan langsung kalikan luas denah dengan 50! Ubah dulu masing-masing panjang dan lebar cm ke meter lapangan: 6 cm × 50 = ... meter, dan 8 cm × 50 = ... meter.',
                'conceptual_explanation' => 'Panjang sebenarnya = 6 cm × 50 = 300 cm = 3 meter. Lebar sebenarnya = 8 cm × 50 = 400 cm = 4 meter. Maka luas sebenarnya = 3 m × 4 m = 12 m².',
            ],
            'data_ketidakpastian' => [
                'title' => "Latihan Adaptif #{$number}: Rata-rata Terbobot Penghematan Air",
                'context_scenario' => 'Gedung A (berisi 60 siswa) berhasil menghemat rata-rata 4 liter air per hari. Gedung B (berisi 20 siswa) berhasil menghemat rata-rata 8 liter air per hari.',
                'question_text' => 'Berapakah rata-rata liter air yang dihemat per siswa secara keseluruhan dari kedua gedung tersebut?',
                'options' => [
                    ['key' => 'A', 'text' => '6 liter (Rata-rata sederhana dari 4 dan 8)'],
                    ['key' => 'B', 'text' => '5 liter (Rata-rata terbobot: total 320 liter ÷ 80 siswa)'],
                    ['key' => 'C', 'text' => '4,5 liter (Hanya menjumlahkan lalu bagi 2)'],
                    ['key' => 'D', 'text' => '7 liter (Mendekati gedung dengan efisiensi tinggi)'],
                ],
                'correct_answer' => 'B',
                'scaffolding_hint' => 'Hitung dulu total liter air yang dihemat seluruh siswa di Gedung A (60 × 4) dan Gedung B (20 × 8). Lalu jumlahkan dan bagi dengan total seluruh 80 siswa.',
                'conceptual_explanation' => 'Total air dihemat = (60 × 4) + (20 × 8) = 240 + 160 = 320 liter. Total siswa = 60 + 20 = 80 siswa. Rata-rata terbobot = 320 ÷ 80 = 5 liter per siswa.',
            ],
            default => [
                'title' => "Latihan Adaptif #{$number}: Diskon Bertingkat Buku Daur Ulang",
                'context_scenario' => 'Sebuah buku jurnal lingkungan seharga Rp80.000 mendapat promo: Diskon 25% + Potongan Tambahan 10% dari harga setelah diskon pertama.',
                'question_text' => 'Berapa harga yang harus dibayar pembeli?',
                'options' => [
                    ['key' => 'A', 'text' => 'Rp52.000 (Menganggap total diskon 35%)'],
                    ['key' => 'B', 'text' => 'Rp54.000 (Diskon 25% = Rp60.000, lalu diskon 10% dari Rp60.000 = Rp6.000)'],
                    ['key' => 'C', 'text' => 'Rp60.000 (Hanya menghitung diskon pertama)'],
                    ['key' => 'D', 'text' => 'Rp48.000 (Perkiraan kasar)'],
                ],
                'correct_answer' => 'B',
                'scaffolding_hint' => 'Hitung potongan 25% dari Rp80.000 terlebih dahulu untuk mendapatkan harga sementara. Baru setelah itu hitung potongan 10% dari harga sementara tersebut.',
                'conceptual_explanation' => 'Harga setelah diskon 25% = Rp80.000 - Rp20.000 = Rp60.000. Diskon kedua 10% dari Rp60.000 = Rp6.000. Maka harga akhir = Rp60.000 - Rp6.000 = Rp54.000.',
            ],
        };

        return AdaptivePracticeQuestion::create([
            'diagnostic_session_id' => $session->id,
            'target_misconception' => $targetMisconception,
            'domain' => $domain,
            'title' => $item['title'],
            'context_scenario' => $item['context_scenario'],
            'question_text' => $item['question_text'],
            'options' => $item['options'],
            'correct_answer' => $item['correct_answer'],
            'scaffolding_hint' => $item['scaffolding_hint'],
            'conceptual_explanation' => $item['conceptual_explanation'],
            'difficulty' => $difficulty,
        ]);
    }

    /**
     * Memanggil OpenRouter API dengan model pilihan (default: nvidia/nemotron-3-ultra-550b-a55b:free)
     */
    protected function callOpenRouter(string $systemPrompt, string $userPrompt): ?string
    {
        $apiKey = config('services.openrouter.key') ?: env('OPENROUTER_API_KEY');
        if (empty($apiKey)) {
            return null;
        }

        $model = config('services.openrouter.model') ?: env('OPENROUTER_MODEL', 'nvidia/nemotron-3-ultra-550b-a55b:free');
        $siteUrl = config('services.openrouter.site_url') ?: env('OPENROUTER_SITE_URL', 'http://127.0.0.1:8000');
        $siteName = config('services.openrouter.site_name') ?: env('OPENROUTER_SITE_NAME', 'Nalaria');

        try {
            $response = Http::timeout(25)
                ->withHeaders([
                    'Authorization' => 'Bearer '.$apiKey,
                    'HTTP-Referer' => $siteUrl,
                    'X-OpenRouter-Title' => $siteName,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                $json = $response->json();
                $content = $json['choices'][0]['message']['content'] ?? null;
                if ($content) {
                    return trim($content);
                }
            } else {
                Log::warning('OpenRouter API returned error: '.$response->status().' - '.$response->body());
            }
        } catch (\Throwable $e) {
            Log::warning('OpenRouter API call failed: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Memanggil LLM API OpenRouter (nvidia/nemotron-3-ultra-550b-a55b:free) jika tersedia,
     * atau menggunakan intelligent rule-based generator
     */
    protected function callAiForDiagnosis(
        string $studentName,
        int $score,
        string $masteryLevel,
        array $domainScores,
        array $detectedMisconceptions
    ): string {
        $openRouterApiKey = config('services.openrouter.key') ?: env('OPENROUTER_API_KEY');

        // Panggil OpenRouter API jika API key diatur
        if (! empty($openRouterApiKey)) {
            $systemPrompt = 'Kamu adalah Pakar AI Diagnostik Literasi-Numerasi Indonesia berstandar AKM dan PISA.';
            $userPrompt = "Berikan diagnosis kognitif ringkas (3 paragraf empatik, mendalam, dan konstruktif) dalam bahasa Indonesia untuk siswa bernama {$studentName}.\n"
                ."Skor Numerasi: {$score}/100. Tingkat Kemampuan: {$masteryLevel}.\n"
                .'Rekap Domain: '.json_encode($domainScores)."\n"
                .'Miskonsepsi Kognitif yang Terdeteksi dari Pilihan dan Alasan Siswa: '.json_encode($detectedMisconceptions)."\n\n"
                ."Instruksi:\n"
                ."1. Analisis pola penalaran siswa dan mengapa mereka terjebak pada miskonsepsi tersebut.\n"
                ."2. Sebutkan domain mana yang perlu pembenahan konsep dasar.\n"
                .'3. Berikan kalimat penguatan motivasi dan rekomendasi aksi nyata belajar.';

            $aiText = $this->callOpenRouter($systemPrompt, $userPrompt);
            if (! empty($aiText)) {
                return $aiText;
            }
        }

        // Heuristic Generator berkualitas tinggi (Jaminan 100% reliabel & cepat bila offline/tanpa API key)
        $domainWeaknessText = [];
        foreach ($domainScores as $dom => $stat) {
            $total = $stat['total'] ?? 0;
            $correct = $stat['correct'] ?? 0;
            if ($total > 0 && ($correct / $total) < 1.0) {
                $domainWeaknessText[] = ucfirst(str_replace('_', ' ', $dom))." (akurasi {$correct}/{$total})";
            }
        }

        $misconceptionList = '';
        if (! empty($detectedMisconceptions)) {
            $misconceptionList = ' Dari analisis pola jawaban dan penalaran siswa, terdeteksi hambatan pada: **'
                .htmlspecialchars($detectedMisconceptions[0]['misconception']).'**.';
        }

        $weaknessSummary = ! empty($domainWeaknessText)
            ? 'Area yang memerlukan perhatian lebih berada pada domain: '.implode(', ', $domainWeaknessText).'.'
            : 'Siswa menunjukkan pemahaman yang seimbang di semua domain numerasi kontekstual.';

        return "Berdasarkan evaluasi diagnostik adaptif, ananda **{$studentName}** memperoleh skor kompetensi **{$score}/100** dan berada pada kategori tingkat kecakapan **{$masteryLevel}**.\n\n"
            ."{$weaknessSummary}{$misconceptionList}\n\n"
            .'Siswa cenderung menyelesaikan persoalan dengan intuisi aritmatika langsung atau prosedur hafalan linier tanpa memvalidasi keterikatan antar variabel situasi kontekstual. Rekomendasi tindakan: Siswa disarankan mempelajari materi remediasi bertarget dan mengerjakan paket latihan scaffolding adaptif di bawah ini untuk memperkuat fondasi penalaran matematis.';
    }

    /**
     * Menghasilkan Paket Soal Adaptif Multi-Butir (Literasi & Numerasi) On-Demand
     * Mendukung pilihan jumlah soal (1 s/d 8), domain spesifik atau paket terpadu Literasi-Numerasi
     *
     * @return array{
     *     package_title: string,
     *     difficulty: string,
     *     domain: string,
     *     domain_label: string,
     *     competency: string,
     *     total_questions: int,
     *     ai_model: string,
     *     questions: array<int, array>
     * }
     */
    public function generateOnDemandPackage(
        string $domain = 'campuran',
        string $difficulty = 'Sedang',
        int $count = 3,
        ?string $topicContext = null
    ): array {
        $count = max(1, min(8, $count));

        $normalizedDomain = match (strtolower(trim($domain))) {
            'literasi', 'literasi_informasi' => 'literasi_informasi',
            'aljabar' => 'aljabar',
            'geometri', 'geometri_spasial' => 'geometri',
            'data_ketidakpastian', 'statistika', 'data & statistik' => 'data_ketidakpastian',
            'aritmatika_sosial' => 'aritmatika_sosial',
            default => 'campuran',
        };

        $categoryLabel = self::mapDomainToCategory($normalizedDomain);
        $competencyLabel = self::mapDomainToCompetency($normalizedDomain);

        $allowedDifficulties = ['Mudah', 'Sedang', 'Menantang'];
        $difficulty = in_array(ucfirst(strtolower($difficulty)), $allowedDifficulties) ? ucfirst(strtolower($difficulty)) : 'Sedang';

        $defaultContexts = [
            'campuran' => 'Kombinasi Transisi Energi Bersih, Daur Ulang & Audit Ekologis',
            'literasi_informasi' => 'Dampak Krisis Iklim, Bioakumulasi Mikroplastik & Energi Terbarukan',
            'aljabar' => 'Pemodelan Efisiensi Biaya Instalasi Panel Surya Sekolah',
            'geometri' => 'Tata Ruang & Luas Efektif Taman Hidroponik Komunitas',
            'data_ketidakpastian' => 'Analisis Tren Penurunan Emisi Karbon dan Jejak Plastik',
            'aritmatika_sosial' => 'Skema Investasi Daur Ulang Logam & Diskon Bertingkat Pupuk Kompos',
        ];

        $topic = ! empty(trim($topicContext ?? '')) ? trim($topicContext) : ($defaultContexts[$normalizedDomain] ?? 'Keberlanjutan Lingkungan Indonesia');

        // Rotasi domain jika memilih paket terpadu (campuran literasi & numerasi)
        $domainRotation = [
            'literasi_informasi',
            'aritmatika_sosial',
            'aljabar',
            'geometri',
            'data_ketidakpastian',
            'literasi_informasi',
            'aritmatika_sosial',
            'geometri',
        ];

        $generatedQuestions = [];
        $apiKey = config('services.openrouter.key') ?: env('OPENROUTER_API_KEY');
        $modelName = config('services.openrouter.model') ?: env('OPENROUTER_MODEL', 'nvidia/nemotron-3-ultra-550b-a55b:free');
        $usedAiModel = ! empty($apiKey) ? $modelName : 'Nalaria AI Adaptive Generator Engine';

        // Coba generate via OpenRouter jika API Key tersedia
        if (! empty($apiKey)) {
            for ($i = 0; $i < $count; $i++) {
                $itemDomain = ($normalizedDomain === 'campuran') ? $domainRotation[$i % count($domainRotation)] : $normalizedDomain;
                $itemCategory = self::mapDomainToCategory($itemDomain);
                $itemCompetency = self::mapDomainToCompetency($itemDomain);

                $systemPrompt = 'Kamu adalah Pakar Desain Soal Literasi dan Numerasi Kontekstual Indonesia berstandar PISA dan Asesmen Nasional (AKM). '
                    .'Hasilkan 1 butir soal pilihan ganda kontekstual bertema keberlanjutan masa depan dalam format JSON valid.';

                $difficultyGuidance = match ($difficulty) {
                    'Menantang' => 'Level 4-5 PISA (HOTS): Skenario multivariabel / teks analitis kompleks, penalaran kritis langkah ganda.',
                    'Mudah' => 'Level 2 PISA: Narasi langsung, angka bulat bersahabat / teks fakta eksplisit, konsep dasar tanpa jebakan rumit.',
                    default => 'Level 3 PISA: Penalaran terstruktur, aplikasi konsep kontekstual dengan 2 tahapan berpikir sistematis.',
                };

                $competencyGuidance = ($itemCompetency === 'Literasi')
                    ? 'Fokus pada Literasi Membaca Teks Informasi Sains/Lingkungan (kemampuan menemukan informasi tersirat, inferensi logis, dan evaluasi argumen berbasis data narasi).'
                    : 'Fokus pada Numerasi Kontekstual (kemampuan memodelkan matematika ke situasi nyata, kalkulasi terstruktur, dan interpretasi matematis).';

                $userPrompt = 'Spesifikasi Butir Soal Ke-'.($i + 1).":\n"
                    ."- Kompetensi: {$itemCompetency} ({$competencyGuidance})\n"
                    ."- Domain: {$itemCategory} ({$itemDomain})\n"
                    ."- Tingkat Kesulitan: {$difficulty} ({$difficultyGuidance})\n"
                    ."- Konteks Topik Keberlanjutan: {$topic}\n\n"
                    ."Format output WAJIB HANYA berupa JSON valid (tanpa markdown atau teks lainnya) dengan struktur:\n"
                    ."{\n"
                    ."  \"title\": \"... judul menarik ...\",\n"
                    ."  \"context_scenario\": \"... teks stimulus / cerita latar situasi kontekstual ...\",\n"
                    ."  \"question_text\": \"... pertanyaan terukur ...\",\n"
                    ."  \"options\": [\n"
                    ."    {\"key\": \"A\", \"text\": \"...\"},\n"
                    ."    {\"key\": \"B\", \"text\": \"...\"},\n"
                    ."    {\"key\": \"C\", \"text\": \"...\"},\n"
                    ."    {\"key\": \"D\", \"text\": \"...\"}\n"
                    ."  ],\n"
                    ."  \"correct_answer\": \"B\",\n"
                    ."  \"scaffolding_hint\": \"... petunjuk cara bernalar tanpa membocorkan jawaban ...\",\n"
                    ."  \"conceptual_explanation\": \"... pembahasan langkah demi langkah dan konsep yang benar ...\"\n"
                    .'}';

                $raw = $this->callOpenRouter($systemPrompt, $userPrompt);
                if (! empty($raw)) {
                    $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($raw));
                    $data = json_decode($cleanJson, true);
                    if (is_array($data) && ! empty($data['question_text']) && ! empty($data['options']) && ! empty($data['correct_answer'])) {
                        $generatedQuestions[] = [
                            'number' => $i + 1,
                            'competency' => $itemCompetency,
                            'domain' => $itemDomain,
                            'domain_label' => $itemCategory,
                            'difficulty' => $difficulty,
                            'title' => $data['title'] ?? 'Soal #'.($i + 1).": {$itemCategory}",
                            'context_scenario' => $data['context_scenario'] ?? '',
                            'question_text' => $data['question_text'],
                            'options' => $data['options'],
                            'correct_answer' => strtoupper($data['correct_answer']),
                            'scaffolding_hint' => $data['scaffolding_hint'] ?? '',
                            'conceptual_explanation' => $data['conceptual_explanation'] ?? '',
                            'ai_model' => $modelName,
                        ];

                        continue;
                    }
                }

                // Fallback jika API gagal untuk butir soal ini
                $fallbackItem = $this->getFallbackItem($itemDomain, $difficulty, $i + 1);
                $generatedQuestions[] = $fallbackItem;
            }

            if (count($generatedQuestions) === $count) {
                return [
                    'package_title' => 'Paket Asesmen '.($normalizedDomain === 'campuran' ? 'Terpadu Literasi-Numerasi' : $categoryLabel)." ({$count} Soal)",
                    'difficulty' => $difficulty,
                    'domain' => $normalizedDomain,
                    'domain_label' => $categoryLabel,
                    'competency' => $competencyLabel,
                    'total_questions' => count($generatedQuestions),
                    'ai_model' => $usedAiModel,
                    'questions' => $generatedQuestions,
                ];
            }
        }

        // Generator Fallback Kaya Konteks Literasi & Numerasi (100% Cepat & Bergaransi Berjalan)
        return $this->getFallbackOnDemandPackage($normalizedDomain, $difficulty, $count, $topic);
    }

    /**
     * Menghasilkan 1 butir soal adaptif secara interaktif/on-demand (Backward compatible)
     *
     * @return array{
     *     domain: string,
     *     domain_label: string,
     *     difficulty: string,
     *     title: string,
     *     context_scenario: string,
     *     question_text: string,
     *     options: array<int, array{key: string, text: string}>,
     *     correct_answer: string,
     *     scaffolding_hint: string,
     *     conceptual_explanation: string,
     *     ai_model: string
     * }
     */
    public function generateOnDemandQuestion(
        string $domain = 'aritmatika_sosial',
        string $difficulty = 'Sedang',
        ?string $topicContext = null
    ): array {
        $package = $this->generateOnDemandPackage($domain, $difficulty, 1, $topicContext);

        return $package['questions'][0] ?? $this->getFallbackItem('aritmatika_sosial', $difficulty, 1);
    }

    /**
     * Fallback Bank Paket Soal On-Demand Multi-Butir (Literasi & Numerasi)
     */
    protected function getFallbackOnDemandPackage(string $domain, string $difficulty, int $count, string $topic): array
    {
        $categoryLabel = self::mapDomainToCategory($domain);
        $competencyLabel = self::mapDomainToCompetency($domain);

        $domainRotation = [
            'literasi_informasi',
            'aritmatika_sosial',
            'aljabar',
            'geometri',
            'data_ketidakpastian',
            'literasi_informasi',
            'aritmatika_sosial',
            'geometri',
        ];

        $questions = [];
        for ($i = 0; $i < $count; $i++) {
            $itemDomain = ($domain === 'campuran') ? $domainRotation[$i % count($domainRotation)] : $domain;
            $questions[] = $this->getFallbackItem($itemDomain, $difficulty, $i + 1);
        }

        return [
            'package_title' => 'Paket Asesmen '.($domain === 'campuran' ? 'Terpadu Literasi & Numerasi' : $categoryLabel)." ({$count} Soal)",
            'difficulty' => $difficulty,
            'domain' => $domain,
            'domain_label' => $categoryLabel,
            'competency' => $competencyLabel,
            'total_questions' => count($questions),
            'ai_model' => 'Nalaria AI Adaptive Generator Engine',
            'questions' => $questions,
        ];
    }

    /**
     * Mengambil 1 butir soal fallback sesuai domain, tingkat kesulitan, dan index
     */
    protected function getFallbackItem(string $domain, string $difficulty, int $number): array
    {
        $categoryLabel = self::mapDomainToCategory($domain);
        $competencyLabel = self::mapDomainToCompetency($domain);

        $bank = [
            'literasi_informasi' => [
                'Mudah' => [
                    [
                        'title' => 'Dampak Mikroplastik terhadap Rantai Makanan Sungai Brantas',
                        'context_scenario' => 'Penelitian ilmiah di sepanjang Sungai Brantas menemukan 80% sampel ikan air tawar telah menelan partikel mikroplastik dari sampah sachet dan kantong plastik sekali pakai. Mikroplastik ini memiliki sifat mengikat logam berat dan polutan beracun di perairan. Ketika ikan tersebut ditangkap dan dikonsumsi oleh masyarakat, racun yang menempel pada mikroplastik dapat berpindah dan terakumulasi di dalam jaringan tubuh manusia (bioakumulasi). Peneliti merekomendasikan pembatasan plastik sekali pakai dan penyediaan bank sampah di pemukiman bantaran sungai.',
                        'question_text' => 'Berdasarkan teks bacaan di atas, mengapa keberadaan mikroplastik pada ikan air tawar dapat membahayakan kesehatan manusia yang mengonsumsinya?',
                        'options' => [
                            ['key' => 'A', 'text' => 'Mikroplastik mengikat zat polutan beracun dan berpindah ke tubuh manusia melalui proses rantai makanan.'],
                            ['key' => 'B', 'text' => 'Mikroplastik langsung mencair dan menguap menjadi gas beracun saat dimasak di suhu mendidih.'],
                            ['key' => 'C', 'text' => 'Mikroplastik hanya menempel di sisik luar ikan sehingga mudah dihilangkan saat dicuci.'],
                            ['key' => 'D', 'text' => 'Mikroplastik mempercepat perkembangbiakan parasit usus pada ikan secara drastis.'],
                        ],
                        'correct_answer' => 'A',
                        'scaffolding_hint' => 'Perhatikan penjelasan pada kalimat yang menerangkan tentang bioakumulasi dan bagaimana polutan berpindah dari ikan ke konsumen.',
                        'conceptual_explanation' => 'Teks secara eksplisit menegaskan bahwa mikroplastik mengikat logam berat/polutan dan berpindah ke tubuh manusia melalui konsumsi ikan dalam rantai makanan.',
                    ],
                    [
                        'title' => 'Konservasi Mangrove Pantai Utara Jawa sebagai Peredam Rob',
                        'context_scenario' => 'Wilayah pesisir utara Jawa menghadapi ancaman banjir rob musiman akibat kenaikan permukaan air laut dan penurunan muka tanah. Penanaman kembali hutan mangrove terbukti mampu meredam energi gelombang pasang hingga 60% dan akar napasnya memerangkap sedimen lumpur sehingga daratan tidak mudah tergerus abrasi. Selain itu, ekosistem mangrove menjadi tempat memijah biota laut yang meningkatkan hasil tangkapan nelayan lokal.',
                        'question_text' => 'Apa manfaat ganda ekologis dan ekonomis penanaman mangrove bagi masyarakat pesisir menurut teks?',
                        'options' => [
                            ['key' => 'A', 'text' => 'Meredam gelombang rob pasang sekaligus menyediakan habitat biota laut pendukung nelayan.'],
                            ['key' => 'B', 'text' => 'Mengubah air laut menjadi air tawar murni secara otomatis tanpa biaya penyulingan.'],
                            ['key' => 'C', 'text' => 'Menghilangkan kebutuhan tanggul beton selamanya di seluruh pantai Indonesia.'],
                            ['key' => 'D', 'text' => 'Menghasilkan kayu bakar industri dalam jumlah tak terbatas setiap bulan.'],
                        ],
                        'correct_answer' => 'A',
                        'scaffolding_hint' => 'Temukan dua aspek yang disebutkan: aspek perlindungan fisik pantai (ekologis) dan aspek pendapatan nelayan (ekonomis).',
                        'conceptual_explanation' => 'Secara ekologis mangrove meredam energi gelombang hingga 60%, dan secara ekonomis akarnya menjadi habitat ikan pendukung hasil tangkapan nelayan.',
                    ],
                ],
                'Sedang' => [
                    [
                        'title' => 'Transisi Energi Bersih: Efektivitas Turbin Angin PLTB Sidrap',
                        'context_scenario' => 'Pembangkit Listrik Tenaga Bayu (PLTB) Sidrap di Sulawesi Selatan mengoperasikan 30 turbin kincir angin dengan kapasitas total 75 MW yang mampu memasok listrik ramah lingkungan untuk 70.000 rumah tangga. Meskipun berhasil memangkas ribuan ton emisi karbon, pengelola menghadapi tantangan intermittency (fluktuasi angin), di mana saat musim angin tenang produksi daya listrik dapat menurun drastis. Untuk menjaga keandalan pasokan ke jaringan PLN, PLTB Sidrap dikombinasikan dengan pembangkit tenaga air (PLTA) dan sedang menjajaki baterai penyimpanan skala besar.',
                        'question_text' => 'Berdasarkan teks, kesimpulan paling objektif apa yang dapat ditarik mengenai pemanfaatan PLTB Sidrap?',
                        'options' => [
                            ['key' => 'A', 'text' => 'PLTB tidak efisien karena kecepatan angin yang tidak stabil menyebabkan pembangkit sering tidak berguna.'],
                            ['key' => 'B', 'text' => 'PLTB sangat efektif mereduksi emisi karbon, namun memerlukan integrasi sistem cadangan energi untuk mengatasi fluktuasi angin.'],
                            ['key' => 'C', 'text' => 'Turbin angin sudah sepenuhnya mampu menggantikan seluruh pembangkit listrik batu bara tanpa perlu energi penyangga.'],
                            ['key' => 'D', 'text' => 'Penggunaan PLTB hanya cocok diterapkan di pulau-pulau kecil terpencil yang tidak terhubung jaringan listrik.'],
                        ],
                        'correct_answer' => 'B',
                        'scaffolding_hint' => 'Pilihlah kesimpulan yang menyeimbangkan keunggulan utama energi hijau PLTB dengan tantangan nyata intermittency yang membutuhkan solusi integrasi pembangkit/baterai.',
                        'conceptual_explanation' => 'Teks menguraikan manfaat besar PLTB dalam reduksi emisi, sekaligus menegaskan pentingnya kolaborasi dengan PLTA/baterai guna mengatasi kelemahan fluktuasi angin.',
                    ],
                    [
                        'title' => 'Inovasi Pertanian Presisi Menggunakan Sensor IoT dan Cuaca',
                        'context_scenario' => 'Kelompok tani milenial di Lembang menerapkan pertanian presisi berbasis Internet of Things (IoT). Sensor kelembapan tanah dan stasiun cuaca mini mengirimkan data real-time ke aplikasi ponsel petani. Irigasi tetes hanya diaktifkan saat kelembapan berada di bawah ambang batas optimal, sehingga menghemat konsumsi air hingga 45% dan memangkas penggunaan pupuk kimia cair karena tidak terbuang sia-sia oleh aliran air berlebih.',
                        'question_text' => 'Bagaimana penerapan sensor IoT mampu meningkatkan efisiensi pertanian berkelanjutan menurut teks?',
                        'options' => [
                            ['key' => 'A', 'text' => 'Dengan memberikan pupuk dalam dosis maksimal setiap hari tanpa henti.'],
                            ['key' => 'B', 'text' => 'Dengan mengatur pengairan hanya saat dibutuhkan tanah sehingga menghemat air dan mencegah pupuk terbuang.'],
                            ['key' => 'C', 'text' => 'Dengan menggantikan seluruh tenaga kerja manusia di sawah secara otomatis tanpa kontrol.'],
                            ['key' => 'D', 'text' => 'Dengan mematikan suplai air tanah saat musim kemarau tiba.'],
                        ],
                        'correct_answer' => 'B',
                        'scaffolding_hint' => 'Perhatikan mekanisme irigasi tetes berbasis sensor kelembapan tanah yang menghemat 45% air.',
                        'conceptual_explanation' => 'Sistem IoT memicu irigasi presisi hanya saat tanah membutuhkan air, menghemat sumber daya air dan meminimalkan pencemaran pupuk berlebih.',
                    ],
                ],
                'Menantang' => [
                    [
                        'title' => 'Evaluasi Kritis Kebijakan Pajak Karbon & Dekarbonisasi Industri',
                        'context_scenario' => 'Dalam rangka mencapai target Net Zero Emission 2060, pemerintah memberlakukan skema Nilai Ekonomi Karbon (Pajak Karbon) bagi industri penghasil emisi tinggi. Sebagian kalangan khawatir kebijakan ini akan memicu kenaikan harga barang konsumen karena industri melimpahkan beban pajak ke masyarakat. Namun, analisis ekonomi hijau membuktikan bahwa skema pajak karbon dirancang bersamaan dengan insentif pemotongan pajak bagi korporasi yang memasang solar panel dan efisiensi energi. Akibatnya, industri yang bertransisi ke energi bersih akan memiliki biaya produksi lebih hemat dan harga produknya lebih kompetitif dibanding industri pencemar.',
                        'question_text' => 'Argumen teks manakah yang paling kuat mematahkan anggapan bahwa pajak karbon hanya akan merugikan konsumen akhir?',
                        'options' => [
                            ['key' => 'A', 'text' => 'Konsumen akan mendapatkan subsidi tunai tanpa batas langsung dari kas pemerintah.'],
                            ['key' => 'B', 'text' => 'Insentif fiskal energi bersih mendorong pabrik berinovasi sehingga produk ramah lingkungan berbiaya lebih hemat dan terjangkau.'],
                            ['key' => 'C', 'text' => 'Pemerintah akan melarang industri menaikkan harga barang dengan hukuman pidana sepihak.'],
                            ['key' => 'D', 'text' => 'Pajak karbon hanya berlaku bagi barang-barang mewah impor yang tidak dibeli masyarakat umum.'],
                        ],
                        'correct_answer' => 'B',
                        'scaffolding_hint' => 'Cari argumen berbasis mekanisme pasar dan insentif teknologi bersih yang membuat efisiensi biaya produksi menguntungkan konsumen.',
                        'conceptual_explanation' => 'Pajak karbon bukan sekadar denda, melainkan insentif agar korporasi efisien dan mengadopsi energi bersih, yang pada akhirnya menghasilkan produk kompetitif berbiaya rendah bagi konsumen.',
                    ],
                ],
            ],
            'aljabar' => [
                'Mudah' => [
                    [
                        'title' => 'Pemodelan Tabungan Sedekah Sampah Botol Plastik',
                        'context_scenario' => 'Sebuah sekolah memulai program bank sampah. Setiap siswa yang mendaftar mendapatkan saldo awal Rp10.000. Setiap botol plastik yang disetor bernilai Rp200.',
                        'question_text' => 'Jika seorang siswa menyetor sebanyak b botol plastik, rumus manakah yang menyatakan total saldo tabungan (T) yang ia miliki?',
                        'options' => [
                            ['key' => 'A', 'text' => 'T = 10.000 + 200b'],
                            ['key' => 'B', 'text' => 'T = 10.000b + 200'],
                            ['key' => 'C', 'text' => 'T = 10.200b'],
                            ['key' => 'D', 'text' => 'T = 200b - 10.000'],
                        ],
                        'correct_answer' => 'A',
                        'scaffolding_hint' => 'Saldo awal Rp10.000 adalah konstanta yang didapat sekali di awal, sedangkan nilai per botol Rp200 bertambah sesuai banyaknya botol b.',
                        'conceptual_explanation' => 'Saldo awal adalah konstanta (+10.000), dan setiap botol menghasilkan Rp200 sehingga bagian variabelnya adalah 200b. Total saldo: T = 10.000 + 200b.',
                    ],
                ],
                'Sedang' => [
                    [
                        'title' => 'Kalkulasi Biaya Operasional Pembangkit Listrik Tenaga Surya',
                        'context_scenario' => 'Koperasi sekolah menyewa sistem panel surya dengan biaya sewa tetap Rp150.000 per bulan ditambah biaya perawatan Rp50 per kWh listrik yang dihasilkan.',
                        'question_text' => 'Jika dalam satu bulan sistem menghasilkan k kWh listrik dan koperasi membayar total Rp275.000, berapakah kWh listrik yang dihasilkan?',
                        'options' => [
                            ['key' => 'A', 'text' => '2.000 kWh'],
                            ['key' => 'B', 'text' => '2.500 kWh'],
                            ['key' => 'C', 'text' => '3.000 kWh'],
                            ['key' => 'D', 'text' => '1.500 kWh'],
                        ],
                        'correct_answer' => 'B',
                        'scaffolding_hint' => 'Susun persamaan: Total Biaya = 150.000 + 50k = 275.000. Kurangkan kedua ruas dengan 150.000 lalu bagi dengan 50.',
                        'conceptual_explanation' => '50k = 275.000 - 150.000 = 125.000. Maka k = 125.000 / 50 = 2.500 kWh.',
                    ],
                ],
                'Menantang' => [
                    [
                        'title' => 'Titik Impas (BEP) Pengadaan Motor Listrik Operasional',
                        'context_scenario' => 'Sebuah unit usaha sekolah mempertimbangkan beralih ke motor listrik. Biaya awal motor listrik Rp24.000.000 dengan biaya operasional Rp200/km. Motor bensin yang ada bernilai jual Rp0 dengan biaya operasional Rp800/km (bensin + servis rutin).',
                        'question_text' => 'Berapa kilometer (x) jarak tempuh minimal agar total biaya kepemilikan motor listrik menjadi lebih hemat daripada tetap menggunakan motor bensin?',
                        'options' => [
                            ['key' => 'A', 'text' => '30.000 km'],
                            ['key' => 'B', 'text' => '40.000 km'],
                            ['key' => 'C', 'text' => '50.000 km'],
                            ['key' => 'D', 'text' => '24.000 km'],
                        ],
                        'correct_answer' => 'B',
                        'scaffolding_hint' => 'Penghematan per kilometer adalah Rp800 - Rp200 = Rp600/km. Hitung berapa km yang dibutuhkan penghematan ini untuk menutup modal Rp24.000.000.',
                        'conceptual_explanation' => 'Persamaan impas: 24.000.000 + 200x = 800x <=> 600x = 24.000.000 <=> x = 40.000 km. Setelah 40.000 km, motor listrik lebih ekonomis.',
                    ],
                ],
            ],
            'geometri' => [
                'Mudah' => [
                    [
                        'title' => 'Pembuatan Bedeng Tanaman Sayur Organik',
                        'context_scenario' => 'Siswa merancang bedeng kebun sekolah berbentuk persegi panjang dengan panjang 4 meter dan lebar 1,5 meter.',
                        'question_text' => 'Berapa meter keliling papan kayu yang dibutuhkan untuk memagari sekeliling bedeng tersebut?',
                        'options' => [
                            ['key' => 'A', 'text' => '11 meter'],
                            ['key' => 'B', 'text' => '6 meter'],
                            ['key' => 'C', 'text' => '12 meter'],
                            ['key' => 'D', 'text' => '8 meter'],
                        ],
                        'correct_answer' => 'A',
                        'scaffolding_hint' => 'Keliling persegi panjang adalah 2 × (panjang + lebar).',
                        'conceptual_explanation' => 'Keliling = 2 × (4 + 1,5) = 2 × 5,5 = 11 meter.',
                    ],
                ],
                'Sedang' => [
                    [
                        'title' => 'Skala Denah Pemasangan Paving Porous Penyerap Air Hujan',
                        'context_scenario' => 'Halaman resapan air sekolah digambar pada denah berskala 1 : 200 dengan ukuran panjang 5 cm dan lebar 3 cm.',
                        'question_text' => 'Berapakah luas permukaan tanah sebenarnya yang akan dipasangi paving porous?',
                        'options' => [
                            ['key' => 'A', 'text' => '30 m²'],
                            ['key' => 'B', 'text' => '60 m²'],
                            ['key' => 'C', 'text' => '150 m²'],
                            ['key' => 'D', 'text' => '300 m²'],
                        ],
                        'correct_answer' => 'B',
                        'scaffolding_hint' => 'Konversi masing-masing panjang dan lebar ke ukuran meter sebenarnya sebelum mengalikannya: 5 cm × 200 = ... m, dan 3 cm × 200 = ... m.',
                        'conceptual_explanation' => 'Panjang nyata = 5 cm × 200 = 1.000 cm = 10 m. Lebar nyata = 3 cm × 200 = 600 cm = 6 m. Luas nyata = 10 m × 6 m = 60 m².',
                    ],
                ],
                'Menantang' => [
                    [
                        'title' => 'Kapasitas Tampung Toren Silinder Air Hujan Panenan',
                        'context_scenario' => 'Sebuah instalasi pemanen air hujan menggunakan tangki silinder dengan diameter 2 meter dan tinggi 3 meter (gunakan perkiraan π ≈ 3,14).',
                        'question_text' => 'Jika tangki tersebut terisi 80% saat musim hujan, berapa liter air hujan yang tersimpan?',
                        'options' => [
                            ['key' => 'A', 'text' => '7.536 liter'],
                            ['key' => 'B', 'text' => '9.420 liter'],
                            ['key' => 'C', 'text' => '30.144 liter'],
                            ['key' => 'D', 'text' => '3.768 liter'],
                        ],
                        'correct_answer' => 'A',
                        'scaffolding_hint' => 'Jari-jari tangki r = diameter/2 = 1 meter. Volume total silinder = π × r² × t. Ingat bahwa 1 m³ = 1.000 liter, lalu kalikan 80%.',
                        'conceptual_explanation' => 'Volume silinder = 3,14 × (1 m)² × 3 m = 9,42 m³ = 9.420 liter. Terisi 80% = 0,80 × 9.420 liter = 7.536 liter.',
                    ],
                ],
            ],
            'data_ketidakpastian' => [
                'Mudah' => [
                    [
                        'title' => 'Frekuensi Pengumpulan Sampah Logam Mingguan',
                        'context_scenario' => 'Dalam 4 minggu berturut-turut, kelas XII mengumpulkan kaleng bekas seberat: 12 kg, 15 kg, 13 kg, dan 20 kg.',
                        'question_text' => 'Berapakah rata-rata (mean) berat kaleng bekas yang dikumpulkan per minggu?',
                        'options' => [
                            ['key' => 'A', 'text' => '14 kg'],
                            ['key' => 'B', 'text' => '15 kg'],
                            ['key' => 'C', 'text' => '16 kg'],
                            ['key' => 'D', 'text' => '13,5 kg'],
                        ],
                        'correct_answer' => 'B',
                        'scaffolding_hint' => 'Jumlahkan seluruh berat kaleng (12 + 15 + 13 + 20) lalu bagi dengan 4.',
                        'conceptual_explanation' => 'Total = 12 + 15 + 13 + 20 = 60 kg. Rata-rata = 60 / 4 = 15 kg/minggu.',
                    ],
                ],
                'Sedang' => [
                    [
                        'title' => 'Rata-rata Terbobot Efisiensi Energi Dua Sayap Gedung',
                        'context_scenario' => 'Gedung Sayap Barat (dihuni 100 siswa) menghemat rata-rata 3 kWh listrik per hari. Gedung Sayap Timur (dihuni 50 siswa) menghemat rata-rata 6 kWh listrik per hari.',
                        'question_text' => 'Berapakah rata-rata penghematan listrik per siswa untuk gabungan kedua sayap gedung tersebut?',
                        'options' => [
                            ['key' => 'A', 'text' => '4,5 kWh (Rerata langsung dari 3 dan 6)'],
                            ['key' => 'B', 'text' => '4,0 kWh (Rerata terbobot jumlah siswa)'],
                            ['key' => 'C', 'text' => '3,5 kWh (Mendekati gedung berpopulasi besar)'],
                            ['key' => 'D', 'text' => '5,0 kWh'],
                        ],
                        'correct_answer' => 'B',
                        'scaffolding_hint' => 'Jangan hanya merata-ratakan 3 dan 6! Kalikan dulu (100 × 3) + (50 × 6), lalu bagi dengan total seluruh 150 siswa.',
                        'conceptual_explanation' => 'Total penghematan = (100 × 3) + (50 × 6) = 300 + 300 = 600 kWh. Total siswa = 150. Rata-rata terbobot = 600 / 150 = 4 kWh per siswa.',
                    ],
                ],
                'Menantang' => [
                    [
                        'title' => 'Peluang dan Perkiraan Risiko Mutu Kompos Mandiri',
                        'context_scenario' => 'Dari hasil audit 200 kantong kompos daur ulang kantin, terdapat 170 kantong lolos uji standar mutu A, 20 kantong lolos standar mutu B, dan 10 kantong gagal standar. Jika 2 kantong diambil secara acak tanpa pengembalian.',
                        'question_text' => 'Berapakah probabilitas kedua kantong tersebut berkategori lolos uji mutu A?',
                        'options' => [
                            ['key' => 'A', 'text' => '72,25% (0,85 × 0,85)'],
                            ['key' => 'B', 'text' => '72,04% (Pengambilan tanpa pengembalian: 170/200 × 169/199)'],
                            ['key' => 'C', 'text' => '85,00%'],
                            ['key' => 'D', 'text' => '68,00%'],
                        ],
                        'correct_answer' => 'B',
                        'scaffolding_hint' => 'Pengambilan dilakukan TANPA pengembalian. Peluang pertama adalah 170/200, peluang kedua menjadi 169/199.',
                        'conceptual_explanation' => 'Peluang pengambilan tanpa pengembalian = (170/200) × (169/199) = 0,85 × 0,84924 ≈ 0,7218 (72,04%). Pengurangan sampel mempengaruhi penyebut dan pembilang.',
                    ],
                ],
            ],
            'aritmatika_sosial' => [
                'Mudah' => [
                    [
                        'title' => 'Keuntungan Penjualan Pupuk Kascing Organik',
                        'context_scenario' => 'Kelompok tani hidroponik memproduksi 50 kg pupuk dengan total modal Rp150.000. Seluruh pupuk habis terjual dengan harga Rp5.000 per kg.',
                        'question_text' => 'Berapakah keuntungan bersih yang diperoleh kelompok tani tersebut?',
                        'options' => [
                            ['key' => 'A', 'text' => 'Rp100.000'],
                            ['key' => 'B', 'text' => 'Rp250.000'],
                            ['key' => 'C', 'text' => 'Rp75.000'],
                            ['key' => 'D', 'text' => 'Rp150.000'],
                        ],
                        'correct_answer' => 'A',
                        'scaffolding_hint' => 'Hitung penerimaan total (50 × Rp5.000), lalu kurangkan dengan modal Rp150.000.',
                        'conceptual_explanation' => 'Penerimaan total = 50 × Rp5.000 = Rp250.000. Keuntungan = Rp250.000 - Rp150.000 = Rp100.000.',
                    ],
                ],
                'Sedang' => [
                    [
                        'title' => 'Diskon Bertingkat Panel Surya Ramah Anggaran',
                        'context_scenario' => 'Pak Budi membeli inverter surya seharga Rp2.000.000 dengan promo: Diskon 20% + Tambahan Potongan 5% dari harga setelah diskon pertama.',
                        'question_text' => 'Berapa total harga yang harus dibayarkan Pak Budi setelah kedua diskon diterapkan?',
                        'options' => [
                            ['key' => 'A', 'text' => 'Rp1.500.000 (Menganggap total diskon 25%)'],
                            ['key' => 'B', 'text' => 'Rp1.520.000 (Diskon 20% = Rp1.600.000, lalu diskon 5% dari Rp1.600.000)'],
                            ['key' => 'C', 'text' => 'Rp1.600.000 (Hanya menghitung diskon pertama)'],
                            ['key' => 'D', 'text' => 'Rp1.480.000'],
                        ],
                        'correct_answer' => 'B',
                        'scaffolding_hint' => 'Diskon kedua (5%) TIDAK dihitung dari Rp2.000.000, melainkan dari harga sisa setelah diskon 20%.',
                        'conceptual_explanation' => 'Setelah diskon 20%: Rp2.000.000 × 0,80 = Rp1.600.000. Diskon 5% dari Rp1.600.000 = Rp80.000. Harga akhir = Rp1.600.000 - Rp80.000 = Rp1.520.000.',
                    ],
                ],
                'Menantang' => [
                    [
                        'title' => 'Perbandingan Finansial Pembelian Tunai vs Angsuran Baterai Solar',
                        'context_scenario' => 'Sebuah unit panel surya seharga tunai Rp10.000.000 ditawarkan dengan skema cicilan: Uang muka 20%, sisa diangsur 12 bulan dengan bunga flat 1% per bulan dari sisa pokok pinjaman.',
                        'question_text' => 'Berapakah selisih total biaya yang dibayar antara skema angsuran dibandingkan harga tunai?',
                        'options' => [
                            ['key' => 'A', 'text' => 'Rp960.000'],
                            ['key' => 'B', 'text' => 'Rp1.200.000'],
                            ['key' => 'C', 'text' => 'Rp800.000'],
                            ['key' => 'D', 'text' => 'Rp1.000.000'],
                        ],
                        'correct_answer' => 'A',
                        'scaffolding_hint' => 'Uang muka = 20% × Rp10.000.000 = Rp2.000.000. Sisa pokok yang dicicil = Rp8.000.000. Bunga flat 1% per bulan selama 12 bulan = 12% dari pokok pinjaman.',
                        'conceptual_explanation' => 'Sisa pokok pinjaman = Rp8.000.000. Total bunga pinjaman = 12 × (1% × Rp8.000.000) = 12 × Rp80.000 = Rp960.000. Karena uang muka + pokok = Rp10.000.000, selisih total dengan harga tunai adalah total bunga yaitu Rp960.000.',
                    ],
                ],
            ],
        ];

        $domainGroup = $bank[$domain] ?? $bank['aritmatika_sosial'];
        $difficultyItems = $domainGroup[$difficulty] ?? $domainGroup['Sedang'] ?? reset($domainGroup);

        $selectedItem = $difficultyItems[($number - 1) % count($difficultyItems)];

        return [
            'number' => $number,
            'competency' => $competencyLabel,
            'domain' => $domain,
            'domain_label' => $categoryLabel,
            'difficulty' => $difficulty,
            'title' => $selectedItem['title'],
            'context_scenario' => $selectedItem['context_scenario'],
            'question_text' => $selectedItem['question_text'],
            'options' => $selectedItem['options'],
            'correct_answer' => $selectedItem['correct_answer'],
            'scaffolding_hint' => $selectedItem['scaffolding_hint'],
            'conceptual_explanation' => $selectedItem['conceptual_explanation'],
            'ai_model' => 'Nalaria AI Adaptive Generator Engine',
        ];
    }
}
