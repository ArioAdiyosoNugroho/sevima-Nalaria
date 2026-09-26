<?php

namespace App\Http\Controllers;

use App\Models\AdaptivePracticeQuestion;
use App\Models\DiagnosticQuestion;
use App\Models\DiagnosticSession;
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
        $diagnosis = $aiAgent->diagnoseSession($session, $answersData);

        // AKSI 2 AI AGENT: Generate Paket Soal Latihan Bertarget & Scaffolding
        $aiAgent->generateAdaptivePractice($session, $diagnosis);

        return redirect()->route('diagnostic.result', ['code' => $sessionCode])
            ->with('success', 'Asesmen diagnostik numerasi dan analisis AI berhasil diselesaikan!');
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

        return view('diagnostic.result', [
            'session' => $session,
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
}
