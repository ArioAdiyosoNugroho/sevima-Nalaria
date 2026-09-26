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

                $generatedQuestions[] = $this->buildAdaptivePracticeItem(
                    $session,
                    $targetDomain,
                    $misconceptionName,
                    $idx + 1
                );
            }
        } else {
            // Jika siswa benar semua atau tidak ada miskonsepsi eksplisit, generate tantangan pengayaan
            $generatedQuestions[] = $this->buildAdaptivePracticeItem(
                $session,
                'aritmatika_sosial',
                'Pengayaan: Optimasi Penganggaran Proyek Energi Mandiri',
                1,
                'Menantang'
            );
            $generatedQuestions[] = $this->buildAdaptivePracticeItem(
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
     * Memanggil LLM API jika tersedia, atau menggunakan intelligent rule-based generator
     */
    protected function callAiForDiagnosis(
        string $studentName,
        int $score,
        string $masteryLevel,
        array $domainScores,
        array $detectedMisconceptions
    ): string {
        $apiKey = env('GEMINI_API_KEY') ?: env('OPENAI_API_KEY');

        // Jika API Key tersedia dan terkonfigurasi, panggil LLM
        if ($apiKey && env('GEMINI_API_KEY')) {
            try {
                $prompt = 'Kamu adalah Pakar AI Diagnostik Literasi-Numerasi Indonesia. '
                    ."Berikan diagnosis kognitif ringkas (3-4 paragraf empatik dan konstruktif) untuk siswa bernama {$studentName}. "
                    ."Skor: {$score}/100. Tingkat: {$masteryLevel}. "
                    .'Miskonsepsi terdeteksi: '.json_encode($detectedMisconceptions).'. '
                    .'Fokuskan analisis pada penyebab cara berpikir kontekstualnya dan apa langkah pembenahan kognitifnya.';

                $response = Http::timeout(5)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]],
                    ],
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($text) {
                        return trim($text);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('LLM call failed, fallback to heuristic generator: '.$e->getMessage());
            }
        }

        // Heuristic Generator berkualitas tinggi (Jaminan 100% reliabel & cepat)
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
