@extends('layouts.app')

@section('title', 'Laporan Diagnostik Numerasi — ' . $session->student_name . ' (' . $session->session_code . ')')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Top Action & Identity Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-700 text-white font-bold text-lg flex items-center justify-center shadow-md shadow-purple-600/20">
                {{ substr($session->student_name, 0, 1) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-extrabold text-slate-900">{{ $session->student_name }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-800 text-xs font-bold font-mono">
                        {{ $session->session_code }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">
                    {{ $session->student_grade ?? 'Siswa' }} • Selesai pada {{ $session->created_at->format('d M Y, H:i') }} WIB
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.076-.673-2.074-1.27-2.946m12.008 2.946c.24-1.076.673-2.074 1.27-2.946M3.66 8.358C4.54 6.77 5.86 5.48 7.43 4.63m13.01 3.728c-.88-1.588-2.2-2.878-3.77-3.728M12 21a9 9 0 100-18 9 9 0 000 18z"></path>
                </svg>
                <span>Cetak / PDF</span>
            </button>
            <a href="{{ route('diagnostic.quiz') }}" class="px-4 py-2 rounded-xl bg-purple-700 hover:bg-purple-800 text-white text-xs font-semibold transition-all flex items-center gap-1.5 shadow-sm">
                <span>Asesmen Baru</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- 4 KPI SUMMARY CARDS (Inspired by Zentra SaaS Dashboard) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Score & Level (Dark Hero Card) -->
        <div class="p-6 rounded-2xl bg-gradient-to-br from-[#1E1548] to-[#2D1B69] text-white shadow-lg shadow-purple-950/20 relative overflow-hidden flex flex-col justify-between">
            <div class="relative z-10">
                <span class="text-xs font-semibold text-purple-300 uppercase tracking-wider">Skor Numerasi</span>
                <div class="text-4xl font-extrabold tracking-tight mt-1 text-white">
                    {{ $session->score }}<span class="text-lg font-medium text-purple-300">/100</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-white/10 relative z-10 flex items-center justify-between">
                <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-purple-500/30 text-purple-200 border border-purple-400/30">
                    {{ $session->mastery_level }}
                </span>
                <span class="text-[10px] text-purple-300">Standar PISA</span>
            </div>
        </div>

        <!-- Card 2: Reasoning Accuracy -->
        <div class="bento-card p-6 flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Akurasi Penalaran</span>
                <div class="text-3xl font-extrabold text-slate-900 mt-1">
                    {{ $session->correct_count }} <span class="text-base font-medium text-slate-500">/ {{ $session->total_questions }} Soal</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded">
                    {{ $session->total_questions > 0 ? round(($session->correct_count / $session->total_questions) * 100) : 0 }}% Terjawab Benar
                </span>
                <span class="text-slate-400">Asesmen Awal</span>
            </div>
        </div>

        <!-- Card 3: Time Spent -->
        <div class="bento-card p-6 flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu Pengerjaan</span>
                <div class="text-3xl font-extrabold text-slate-900 mt-1 font-mono">
                    {{ gmdate('i:s', $session->time_spent_seconds) }}
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-600 font-semibold">
                    ~{{ $session->total_questions > 0 ? round($session->time_spent_seconds / $session->total_questions) : 0 }} detik / butir
                </span>
                <span class="text-purple-600 font-bold">Reflektif</span>
            </div>
        </div>

        <!-- Card 4: AI Agent Status -->
        <div class="bento-card p-6 flex flex-col justify-between border-purple-200 bg-purple-50/20">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">AI Agent Fungsional</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">
                    2 Aksi Selesai
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-purple-100 flex items-center justify-between text-[11px] text-purple-800 font-semibold">
                <span>1. Diagnosis Kognitif</span>
                <span>2. Paket Latihan</span>
            </div>
        </div>
    </div>

    <!-- MAIN DIAGNOSTIC ANALYSIS (2 COLUMNS) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Cols: AI Cognitive Diagnosis (Action 1 Output) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bento-card p-6 sm:p-8 space-y-5 border-purple-200">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                    <div class="w-9 h-9 rounded-xl bg-purple-700 text-white flex items-center justify-center font-bold shadow-md shadow-purple-600/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-bold text-slate-900">Diagnosis Kognitif & Miskonsepsi</h2>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">Aksi AI 1</span>
                        </div>
                        <p class="text-xs text-slate-500">Evaluasi pola berpikir berdasarkan pilihan jawaban dan argumen siswa</p>
                    </div>
                </div>

                <!-- Primary Misconception Banner -->
                @if($session->primary_misconception)
                    <div class="p-4 rounded-xl bg-purple-50 border border-purple-200 text-sm text-purple-900 space-y-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-purple-700">Akar Miskonsepsi Utama Terdeteksi:</span>
                        <p class="font-semibold">{{ $session->primary_misconception }}</p>
                    </div>
                @endif

                <!-- AI Full Narrative -->
                <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50 p-5 rounded-xl border border-slate-200/80">
                    {{ $session->ai_diagnosis_summary }}
                </div>
            </div>

            <!-- Detailed Question Breakdown -->
            <div class="bento-card p-6 space-y-4">
                <h3 class="text-base font-bold text-slate-900">Ulasan Jawaban & Alasan Berpikir Siswa</h3>
                <div class="space-y-3">
                    @foreach($session->studentResponses as $idx => $resp)
                        <div class="p-4 rounded-xl border {{ $resp->is_correct ? 'border-emerald-200 bg-emerald-50/20' : 'border-rose-200 bg-rose-50/20' }} space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-800">
                                    Soal #{{ $idx + 1 }}: {{ $resp->diagnosticQuestion->title }}
                                </span>
                                @if($resp->is_correct)
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-bold">Benar (Opsi {{ $resp->selected_option }})</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md bg-rose-100 text-rose-800 font-bold">Salah (Dipilih: {{ $resp->selected_option }} • Kunci: {{ $resp->diagnosticQuestion->correct_answer }})</span>
                                @endif
                            </div>

                            @if($resp->student_reasoning)
                                <div class="text-xs text-slate-600 bg-white p-2.5 rounded-lg border border-slate-200">
                                    <span class="font-semibold text-slate-500">Alasan Siswa:</span> "{{ $resp->student_reasoning }}"
                                </div>
                            @endif

                            @if($resp->detected_misconception)
                                <div class="text-xs text-rose-700 font-medium">
                                    <strong>Miskonsepsi:</strong> {{ $resp->detected_misconception }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Domain Performance Breakdown (Zentra Progress Style) -->
        <div class="space-y-6">
            <div class="bento-card p-6 space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">Penguasaan per Domain</h3>
                    <p class="text-xs text-slate-500">Distribusi penalaran berdasarkan konten kurikulum</p>
                </div>

                @php
                    $domains = $session->domain_scores ?? [];
                    $domainNames = [
                        'aritmatika_sosial' => 'Aritmatika Sosial',
                        'aljabar' => 'Aljabar & Pemodelan',
                        'geometri' => 'Geometri & Skala',
                        'data_ketidakpastian' => 'Data & Ketidakpastian',
                    ];
                @endphp

                <div class="space-y-4">
                    @foreach($domainNames as $key => $title)
                        @php
                            $stat = $domains[$key] ?? ['total' => 1, 'correct' => 0];
                            $tot = $stat['total'] > 0 ? $stat['total'] : 1;
                            $pct = round(($stat['correct'] / $tot) * 100);
                        @endphp
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-xs font-semibold">
                                <span class="text-slate-700">{{ $title }}</span>
                                <span class="text-slate-900">{{ $stat['correct'] }}/{{ $stat['total'] }} ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $pct >= 80 ? 'bg-emerald-500' : ($pct >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                    style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Remediation Overview Notice -->
                <div class="p-3.5 rounded-xl bg-purple-50 text-xs text-purple-900 space-y-1 border border-purple-200">
                    <span class="font-bold uppercase tracking-wider text-purple-800">Rencana Tindak Lanjut:</span>
                    <p>{{ $session->ai_remediation_plan }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTTOM SECTION: AI-GENERATED ADAPTIVE PRACTICE (Untitled UI Card Style) -->
    <div class="space-y-6 pt-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 pb-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs uppercase font-extrabold tracking-widest text-purple-700">Aksi AI 2: Personalisasi Latihan</span>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Interaktif</span>
                </div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">Paket Soal Latihan Adaptif Bertarget</h2>
                <p class="text-xs text-slate-500">Soal-soal baru yang digenerate AI untuk melatih area kelemahan spesifikmu</p>
            </div>
            <span class="text-xs font-medium text-slate-500">
                {{ $session->adaptivePracticeQuestions->count() }} Paket Latihan Tersedia
            </span>
        </div>

        <!-- Practice Cards Grid (Untitled UI 3-Column Responsive Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($session->adaptivePracticeQuestions as $item)
                <div class="bento-card p-6 flex flex-col justify-between space-y-5 practice-card" id="practiceCard_{{ $item->id }}">
                    <!-- Card Top Badges -->
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-purple-100 text-purple-800 uppercase tracking-wider">
                                {{ ucfirst(str_replace('_', ' ', $item->domain)) }}
                            </span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
                                {{ $item->difficulty }}
                            </span>
                        </div>

                        <!-- Target Misconception Notice -->
                        <div class="text-[11px] font-medium text-purple-900 bg-purple-50/70 p-2 rounded-lg border border-purple-100">
                            <strong>Fokus Perbaikan:</strong> {{ Str::limit($item->target_misconception, 65) }}
                        </div>

                        <h3 class="text-base font-bold text-slate-900 leading-snug">
                            {{ $item->title }}
                        </h3>

                        <!-- Scenario -->
                        <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-lg border border-slate-100">
                            {{ $item->context_scenario }}
                        </p>

                        <!-- Question Prompt -->
                        <p class="text-xs font-bold text-slate-800">
                            {{ $item->question_text }}
                        </p>

                        <!-- Options Form -->
                        <form onsubmit="submitPracticeAnswer(event, '{{ $session->session_code }}', {{ $item->id }})" class="space-y-2 pt-2">
                            @foreach($item->options as $opt)
                                <label class="flex items-start gap-2.5 p-2 rounded-lg border border-slate-200 hover:border-purple-300 hover:bg-purple-50/20 cursor-pointer text-xs transition-all">
                                    <input type="radio" name="practice_answer_{{ $item->id }}" value="{{ $opt['key'] }}" required
                                        class="mt-0.5 text-purple-600 focus:ring-purple-500">
                                    <span class="text-slate-800">
                                        <strong>{{ $opt['key'] }}.</strong> {{ $opt['text'] }}
                                    </span>
                                </label>
                            @endforeach

                            <button type="submit" class="w-full mt-3 py-2 rounded-lg bg-slate-900 hover:bg-purple-700 text-white font-bold text-xs transition-colors shadow-sm">
                                Cek Pemahaman
                            </button>
                        </form>

                        <!-- Scaffolding Accordion Toggle -->
                        <div class="pt-2">
                            <button type="button" onclick="toggleHint({{ $item->id }})" class="text-[11px] font-bold text-purple-700 hover:text-purple-900 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM12 7.5h.008v.008H12V7.5z"></path>
                                </svg>
                                <span>Lihat Petunjuk Berpikir (Scaffolding Hint)</span>
                            </button>
                            <div id="hint_{{ $item->id }}" class="hidden mt-2 p-3 rounded-lg bg-amber-50 border border-amber-200 text-xs text-amber-900 leading-relaxed">
                                💡 <strong>Petunjuk Langkah:</strong> {{ $item->scaffolding_hint }}
                            </div>
                        </div>

                        <!-- Feedback Container -->
                        <div id="feedback_{{ $item->id }}" class="hidden p-3 rounded-lg text-xs leading-relaxed"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

@push('scripts')
<script>
    function toggleHint(id) {
        const el = document.getElementById('hint_' + id);
        el.classList.toggle('hidden');
    }

    async function submitPracticeAnswer(e, sessionCode, questionId) {
        e.preventDefault();
        const form = e.target;
        const selected = form.querySelector('input[type="radio"]:checked');
        if (!selected) return;

        const answer = selected.value;
        const feedbackEl = document.getElementById('feedback_' + questionId);

        try {
            const res = await fetch(`/result/${sessionCode}/practice/${questionId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ answer: answer })
            });

            const data = await res.json();
            feedbackEl.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-900', 'border-emerald-200', 'bg-rose-50', 'text-rose-900', 'border-rose-200');

            if (data.is_correct) {
                feedbackEl.classList.add('bg-emerald-50', 'text-emerald-900', 'border', 'border-emerald-200');
                feedbackEl.innerHTML = `<strong>✓ Benar!</strong> ${data.message}<div class="mt-1 pt-1 border-t border-emerald-200 text-slate-700">${data.conceptual_explanation}</div>`;
            } else {
                feedbackEl.classList.add('bg-rose-50', 'text-rose-900', 'border', 'border-rose-200');
                feedbackEl.innerHTML = `<strong>✕ Belum tepat.</strong> ${data.message}<div class="mt-1 pt-1 border-t border-rose-200 text-slate-700">Kunci jawaban: <strong>${data.correct_answer}</strong>. ${data.conceptual_explanation}</div>`;
            }
        } catch (err) {
            console.error(err);
        }
    }
</script>
@endpush
@endsection
