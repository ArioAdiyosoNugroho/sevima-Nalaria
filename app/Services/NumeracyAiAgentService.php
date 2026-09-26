<?php

namespace App\Services;

use App\Models\AdaptivePracticeQuestion;
use App\Models\DiagnosticQuestion;
use App\Models\DiagnosticSession;
use App\Models\StudentResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NumeracyAiAgentService
{
    /**
     * ACTION 1: Diagnosa Miskonsepsi & Profiling Kognitif Penalaran Siswa
     *
     * @param  array  $submittedAnswers  Array of ['question_id' => int, 'selected_option' => string, 'reasoning' => string]
     */
    public function diagnoseSession(DiagnosticSession $session, array $submittedAnswers): array
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

            // Simpan record response siswa
            StudentResponse::create([
                'diagnostic_session_id' => $session->id,
                'diagnostic_question_id' => $question->id,
                'selected_option' => $selectedOption,
                'is_correct' => $isCorrect,
                'student_reasoning' => $studentReasoning,
                'detected_misconception' => $misconception,
            ]);
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

        // Update Sesi
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
     * ACTION 2: Generate Paket Soal Latihan Bertarget & Scaffolding Remedial
     */
    public function generateAdaptivePractice(DiagnosticSession $session, array $diagnosis): Collection
    {
        $detectedMisconceptions = $diagnosis['detected_misconceptions'] ?? [];
        $domainScores = $diagnosis['domain_scores'] ?? [];

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

                // Coba generate via OpenRouter nemotron LLM jika API key tersedia
                $aiQuestion = $this->generatePracticeWithOpenRouter(
                    $session,
                    $targetDomain,
                    $misconceptionName,
                    $idx + 1
                );

                $generatedQuestions[] = $aiQuestion ?: $this->buildAdaptivePracticeItem(
                    $session,
                    $targetDomain,
                    $misconceptionName,
                    $idx + 1
                );
            }
        } else {
            // Jika siswa benar semua atau tidak ada miskonsepsi eksplisit, generate tantangan pengayaan
            $aiQuestion1 = $this->generatePracticeWithOpenRouter(
                $session,
                'aritmatika_sosial',
                'Pengayaan: Optimasi Penganggaran Proyek Energi Mandiri',
                1
            );
            $generatedQuestions[] = $aiQuestion1 ?: $this->buildAdaptivePracticeItem(
                $session,
                'aritmatika_sosial',
                'Pengayaan: Optimasi Penganggaran Proyek Energi Mandiri',
                1,
                'Menantang'
            );

            $aiQuestion2 = $this->generatePracticeWithOpenRouter(
                $session,
                'data_ketidakpastian',
                'Pengayaan: Analisis Tren Emisi Karbon Sekolah',
                2
            );
            $generatedQuestions[] = $aiQuestion2 ?: $this->buildAdaptivePracticeItem(
                $session,
                'data_ketidakpastian',
                'Pengayaan: Analisis Tren Emisi Karbon Sekolah',
                2,
                'Menantang'
            );
        }

        // Simpan rencana remediasi umum di sesi
        $remediationOverview = 'Paket latihan ini difokuskan untuk memperbaiki pola pikir pada domain '
            .implode(', ', array_map(fn ($q) => ucfirst(str_replace('_', ' ', $q->domain)), $generatedQuestions))
            .'. Setiap soal dilengkapi petunjuk penalaran (scaffolding hint) untuk memandu proses berpikir tanpa langsung mengungkap jawaban.';

        $session->update([
            'ai_remediation_plan' => $remediationOverview,
        ]);

        return collect($generatedQuestions);
    }

    /**
     * Generate 1 soal latihan adaptif kontekstual secara dinamis menggunakan OpenRouter (nemotron LLM)
     */
    protected function generatePracticeWithOpenRouter(
        DiagnosticSession $session,
        string $domain,
        string $misconception,
        int $number
    ): ?AdaptivePracticeQuestion {
        $apiKey = env('OPENROUTER_API_KEY');
        if (empty($apiKey)) {
            return null;
        }

        $systemPrompt = 'Kamu adalah Pakar Desain Soal Numerasi Kontekstual Indonesia berstandar PISA. '
            .'Hasilkan 1 soal latihan adaptif kontekstual bertema keberlanjutan masa depan (energi surya, daur ulang sampah, penghematan air, atau alokasi anggaran hijau) dalam format JSON valid.';

        $userPrompt = "Buat 1 soal latihan untuk memperbaiki miskonsepsi: '{$misconception}' pada domain numerasi: '{$domain}'.\n"
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
                    'difficulty' => 'Sedang',
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
}
