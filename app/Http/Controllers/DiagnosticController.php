<?php

namespace App\Http\Controllers;

use App\Models\AdaptivePracticeQuestion;
use App\Models\AssessmentSession;
use App\Models\DiagnosticQuestion;
use App\Models\DiagnosticSession;
use App\Models\Recommendation;
use App\Services\NumeracyAiAgentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DiagnosticController extends Controller
{
    /**
     * Halaman Landing (Visual SOSH Minimal)
     */
    public function index()
    {
        $totalQuestions = DiagnosticQuestion::count();
        $totalAssessed = DiagnosticSession::where('status', 'completed')->count();

        return view('diagnostic.landing', [
            'totalQuestions' => $totalQuestions,
            'totalAssessed' => $totalAssessed,
        ]);
    }

    /**
     * Halaman Form Kuis Diagnostik Adaptif
     */
    public function quiz()
    {
        $questions = DiagnosticQuestion::orderBy('order')->get();

        return view('diagnostic.quiz', [
            'questions' => $questions,
        ]);
    }

    /**
     * Memproses Jawaban Siswa & Menjalankan 2 Aksi AI Agent
     */
    public function submit(Request $request, NumeracyAiAgentService $aiAgent)
    {
        $request->validate([
            'student_name' => 'required|string|max:100',
            'student_grade' => 'required|string|max:50',
            'answers' => 'required|array',
            'time_spent' => 'nullable|integer',
        ]);

        $sessionCode = 'NAL-'.strtoupper(Str::random(6));

        $session = DiagnosticSession::create([
            'session_code' => $sessionCode,
            'student_name' => $request->input('student_name'),
            'student_grade' => $request->input('student_grade'),
            'time_spent_seconds' => (int) $request->input('time_spent', 0),
            'status' => 'in_progress',
        ]);

        $assessmentSession = null;
        if (auth()->check()) {
            $assessmentSession = AssessmentSession::create([
                'session_code' => $sessionCode,
                'user_id' => auth()->id(),
                'student_name' => $request->input('student_name'),
                'student_grade' => $request->input('student_grade'),
                'time_spent_seconds' => (int) $request->input('time_spent', 0),
                'status' => 'in_progress',
            ]);
        }

        $answersData = [];
        $reasonsData = $request->input('reasoning', []);

        foreach ($request->input('answers') as $qId => $selectedOption) {
            $answersData[] = [
                'question_id' => $qId,
                'selected_option' => $selectedOption,
                'reasoning' => $reasonsData[$qId] ?? '',
            ];
        }

        // AKSI 1 AI AGENT: Diagnosis Pola Miskonsepsi Kognitif Siswa
        $diagnosis = $aiAgent->diagnoseSession($session, $answersData, $assessmentSession);

        // AKSI 2 AI AGENT: Generate Paket Soal Latihan Bertarget & Scaffolding
        $aiAgent->generateAdaptivePractice($session, $diagnosis, $assessmentSession);

        return redirect()->route('diagnostic.result', ['code' => $sessionCode])
            ->with('success', 'Asesmen diagnostik numerasi dan analisis AI berhasil diselesaikan!');
    }

    /**
     * Halaman Riwayat Tes Siswa & Penguasaan Numerasi Agregat
     */
    public function history()
    {
        $user = auth()->user();
        $sessions = AssessmentSession::with(['answers', 'recommendations'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $masteryData = AssessmentSession::calculateUserMasteryScore($user->id);

        return view('diagnostic.history', [
            'sessions' => $sessions,
            'masteryData' => $masteryData,
        ]);
    }

    /**
     * Halaman Hasil Ringkas (Visual Zentra Disederhanakan + Kartu Untitled UI)
     */
    public function result(string $code)
    {
        $session = DiagnosticSession::with([
            'studentResponses.diagnosticQuestion',
            'adaptivePracticeQuestions',
        ])->where('session_code', $code)->firstOrFail();

        $userMastery = auth()->check()
            ? AssessmentSession::calculateUserMasteryScore(auth()->id())
            : null;

        return view('diagnostic.result', [
            'session' => $session,
            'userMastery' => $userMastery,
        ]);
    }

    /**
     * Interaksi Menjawab Soal Latihan Adaptif di Halaman Hasil
     */
    public function submitPractice(Request $request, string $code, int $questionId)
    {
        $request->validate([
            'answer' => 'required|string|max:10',
        ]);

        $session = DiagnosticSession::where('session_code', $code)->firstOrFail();
        $practiceQuestion = AdaptivePracticeQuestion::where('id', $questionId)
            ->where('diagnostic_session_id', $session->id)
            ->firstOrFail();

        $studentAnswer = strtoupper(trim($request->input('answer')));
        $isSolved = ($studentAnswer === $practiceQuestion->correct_answer);

        $practiceQuestion->update([
            'student_answer' => $studentAnswer,
            'is_solved' => $isSolved,
        ]);

        // Sync ke tabel recommendations jika ada sesi assessment
        $recommendation = Recommendation::where('title', $practiceQuestion->title)
            ->whereHas('session', fn ($q) => $q->where('session_code', $code))
            ->first();

        if ($recommendation) {
            $recommendation->update([
                'student_answer' => $studentAnswer,
                'is_solved' => $isSolved,
            ]);
        }

        return response()->json([
            'success' => true,
            'is_correct' => $isSolved,
            'correct_answer' => $practiceQuestion->correct_answer,
            'conceptual_explanation' => $practiceQuestion->conceptual_explanation,
            'message' => $isSolved
                ? 'Luar biasa! Penalaranmu sudah tepat dan berhasil memperbaiki miskonsepsi.'
                : 'Jawabanmu belum tepat. Simak penjelasan konsep di bawah untuk memahami langkah penalaran yang benar.',
        ]);
    }

    /**
     * Halaman Dedicated Generator Soal Adaptif AI On-Demand (Literasi & Numerasi)
     *
     * Page load HANYA menggunakan fallback bank (instant, tanpa AI API call).
     * AI hanya dipanggil saat user mengklik Generate (POST endpoint).
     */
    public function generator(Request $request, NumeracyAiAgentService $aiAgent)
    {
        $domain = $request->query('domain', 'campuran');
        $difficulty = $request->query('difficulty', 'Sedang');
        $count = max(1, min(8, (int) $request->query('count', 3)));
        $context = $request->query('context');

        // Langsung pakai fallback bank — cepat, tidak bergantung API eksternal
        $initialPackage = $aiAgent->getFallbackOnDemandPackage(
            $domain,
            $difficulty,
            $count,
            $context ?? ''
        );

        return view('diagnostic.generator', [
            'initialPackage' => $initialPackage,
            'initialQuestion' => $initialPackage['questions'][0] ?? null,
            'selectedDomain' => $domain,
            'selectedDifficulty' => $difficulty,
            'selectedCount' => $count,
            'selectedContext' => $context,
        ]);
    }

    /**
     * Endpoint API / Form untuk Men-generate Paket Soal Baru Secara Instan
     */
    public function generateQuestion(Request $request, NumeracyAiAgentService $aiAgent)
    {
        $request->validate([
            'domain' => 'nullable|string|in:campuran,literasi,literasi_informasi,aljabar,geometri,data_ketidakpastian,aritmatika_sosial',
            'difficulty' => 'nullable|string|in:Mudah,Sedang,Menantang',
            'count' => 'nullable|integer|min:1|max:8',
            'context' => 'nullable|string|max:200',
            'engine' => 'nullable|string|in:fast,ai',
        ]);

        $domain = $request->input('domain', 'campuran');
        $difficulty = $request->input('difficulty', 'Sedang');
        $count = (int) $request->input('count', 3);
        $context = $request->input('context');
        $engine = $request->input('engine');

        // Mode Kilat (Instan): langsung sajikan paket soal kurikulum adaptif terverifikasi dalam hitungan milidetik
        if ($engine === 'fast') {
            $package = $aiAgent->getFallbackOnDemandPackage($domain, $difficulty, $count, $context ?? '');
        } else {
            $package = $aiAgent->generateOnDemandPackage($domain, $difficulty, $count, $context);
        }

        if ($request->wantsJson() || $request->ajax() || $request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'package' => $package,
                'question' => $package['questions'][0] ?? null,
            ]);
        }

        return redirect()->route('diagnostic.generator', [
            'domain' => $domain,
            'difficulty' => $difficulty,
            'count' => $count,
            'context' => $context,
        ])->with('success', 'Paket soal adaptif literasi-numerasi berhasil di-generate!');
    }
}
