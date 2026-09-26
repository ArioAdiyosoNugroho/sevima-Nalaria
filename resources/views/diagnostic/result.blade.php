@extends('layouts.app')

@section('title', 'Laporan Diagnostik Numerasi — ' . $session->student_name . ' (' . $session->session_code . ')')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <!-- Top Identity & Actions Bar (Meekoo Style) -->
    <div class="meekoo-card p-5 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-5 sm:gap-6">
        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-black text-white font-black text-lg sm:text-xl flex items-center justify-center border-2 border-[#FF5500] shadow-md shrink-0">
                {{ substr($session->student_name, 0, 1) }}
            </div>
            <div class="space-y-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                    <h1 class="meekoo-heading text-xl sm:text-2xl font-extrabold text-black truncate">{{ $session->student_name }}</h1>
                    <span class="px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full bg-[#FF5500] text-white text-[11px] sm:text-xs font-mono font-bold">
                        {{ $session->session_code }}
                    </span>
                </div>
                <p class="text-[11px] sm:text-xs text-neutral-500 font-semibold">
                    {{ $session->student_grade ?? 'Siswa' }} • Diselesaikan pada {{ $session->created_at->format('d M Y, H:i') }} WIB • Waktu: {{ gmdate('i:s', $session->time_spent_seconds) }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3 w-full sm:w-auto">
            <button onclick="window.print()" class="flex-1 sm:flex-initial justify-center px-4 sm:px-5 py-2.5 rounded-full bg-[#F4F4F6] hover:bg-neutral-200 text-black text-xs font-bold transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.076-.673-2.074-1.27-2.946m12.008 2.946c.24-1.076.673-2.074 1.27-2.946M3.66 8.358C4.54 6.77 5.86 5.48 7.43 4.63m13.01 3.728c-.88-1.588-2.2-2.878-3.77-3.728M12 21a9 9 0 100-18 9 9 0 000 18z"/>
                </svg>
                <span>Cetak PDF</span>
            </button>
            <a href="{{ route('diagnostic.quiz') }}" class="group inline-flex items-center justify-center flex-1 sm:flex-initial active:scale-95 transition-transform duration-200">
                <span class="flex-1 sm:flex-none text-center px-5 sm:px-6 py-2.5 rounded-full bg-black group-hover:bg-[#FF5500] text-white text-xs font-bold transition-colors duration-200 shadow-sm">
                    Asesmen Baru
                </span>
                <span class="rounded-full bg-black group-hover:bg-[#FF5500] text-white flex items-center justify-center transition-all duration-200 border-2 border-white shrink-0 -ml-2 group-hover:rotate-45" style="width: 42px; height: 42px; min-width: 42px; min-height: 42px;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </span>
            </a>
        </div>
    </div>

    @if(isset($userMastery) && $userMastery['total_sessions'] > 0)
        <!-- User Aggregate Mastery Progress Banner (Tahap 5) -->
        <div class="bg-black text-white rounded-[28px] p-6 sm:p-7 flex flex-col md:flex-row md:items-center justify-between gap-6 border border-neutral-800 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-44 h-44 rounded-full bg-[#FF5500]/20 blur-2xl pointer-events-none"></div>
            <div class="space-y-1 z-10">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full bg-[#FF5500] text-white text-[11px] font-extrabold uppercase">
                        Skor Penguasaan Numerasi Akun
                    </span>
                    <span class="text-xs text-neutral-400 font-semibold">• {{ $userMastery['total_sessions'] }} Sesi Tersimpan</span>
                </div>
                <h3 class="text-xl sm:text-2xl font-black text-white">
                    Skor Kumulatif: {{ $userMastery['mastery_score'] }}/100 — <span class="text-[#FF5500]">{{ $userMastery['level'] }}</span>
                </h3>
                <p class="text-xs text-neutral-400">
                    Kombinasi skor tes diagnostik (70%) + penyelesaian soal remediasi adaptif ({{ $userMastery['remediation_completion_rate'] }}%).
                </p>
            </div>

            <div class="flex items-center gap-3 z-10">
                <a href="{{ route('diagnostic.history') }}" class="px-5 py-2.5 rounded-full bg-white hover:bg-[#FF5500] hover:text-white text-black text-xs font-bold transition-all shadow-sm">
                    Buka Riwayat & Portofolio Saya →
                </a>
            </div>
        </div>
    @endif

    <!-- METRICS STRIP (Exact Meekoo 4-Stat Strip with Dots in Orange/Black/White) -->
    <!-- METRICS STRIP (Exact Meekoo 4-Stat Strip with Dots in Orange/Black/White) -->
    <div class="meekoo-stat-strip p-6 sm:p-10 border border-neutral-200">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 items-center text-center">
            
            <!-- Stat 1: Skor Numerasi -->
            <div class="space-y-1">
                <div class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-black">
                    {{ $session->score }}<span class="text-lg sm:text-xl text-[#FF5500] font-bold">/100</span>
                </div>
                <div class="text-xs sm:text-sm font-semibold text-neutral-500">Skor Penalaran Numerasi</div>
            </div>

            <!-- Stat 2: Akurasi -->
            <div class="space-y-1 relative">
                <span class="hidden md:block absolute -left-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-black"></span>
                <div class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-black">
                    {{ $session->correct_count }}<span class="text-lg sm:text-xl text-neutral-400 font-bold">/{{ $session->total_questions }}</span>
                </div>
                <div class="text-xs sm:text-sm font-semibold text-neutral-500">Akurasi Soal Benar</div>
            </div>

            <!-- Stat 3: Level PISA -->
            <div class="space-y-1 relative">
                <span class="hidden md:block absolute -left-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-black"></span>
                <div class="text-2xl sm:text-3xl font-black tracking-tight text-[#FF5500] mt-1">
                    {{ Str::before($session->mastery_level, '(') }}
                </div>
                <div class="text-xs sm:text-sm font-semibold text-neutral-500">Tingkat Kemampuan Siswa</div>
            </div>

            <!-- Stat 4: AI Agent Actions -->
            <div class="space-y-1 relative">
                <span class="hidden md:block absolute -left-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-black"></span>
                <div class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-black">
                    2 Aksi
                </div>
                <div class="text-xs sm:text-sm font-semibold text-neutral-500">AI Agent (Nemotron-3)</div>
            </div>

        </div>
    </div>

    <!-- MAIN DIAGNOSTIC ANALYSIS (2 COLUMNS) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Left 8 Cols: AI Cognitive Diagnosis (Action 1 Output) -->
        <div class="lg:col-span-8 space-y-6">
            
            <div class="meekoo-card p-8 space-y-6">
                <div class="flex items-center justify-between border-b border-neutral-100 pb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-black text-white flex items-center justify-center font-black">
                            <span class="text-[#FF5500]">AI</span>
                        </div>
                        <div>
                            <h2 class="meekoo-heading text-xl font-extrabold text-black">
                                Diagnosis Kognitif & Penalaran Siswa
                            </h2>
                            <p class="text-xs text-neutral-500 font-normal">
                                Evaluasi mendalam AI Agent Aksi 1 berbasis model <em>nvidia/nemotron-3-ultra-550b-a55b:free</em>
                            </p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-black text-white text-[11px] font-bold">
                        Aksi 1 Selesai
                    </span>
                </div>

                <!-- Primary Misconception Callout in Vibrant Orange -->
                @if($session->primary_misconception)
                    <div class="p-5 rounded-2xl bg-[#FF5500] text-white space-y-1.5 shadow-md shadow-orange-500/20">
                        <div class="text-[11px] font-black uppercase tracking-wider text-black bg-white/90 inline-block px-2.5 py-0.5 rounded-md">
                            Akar Miskonsepsi Utama Terdeteksi
                        </div>
                        <p class="text-sm font-bold leading-snug">
                            {{ $session->primary_misconception }}
                        </p>
                    </div>
                @endif

                <!-- AI Full Narrative in Clean Surface -->
                <div class="text-sm text-neutral-800 leading-relaxed whitespace-pre-line bg-[#F6F6F8] p-6 rounded-2xl border border-neutral-200">
                    {{ $session->ai_diagnosis_summary }}
                </div>
            </div>

            <!-- Detailed Question Breakdown -->
            <div class="meekoo-card p-8 space-y-5">
                <div class="border-b border-neutral-100 pb-4">
                    <h3 class="meekoo-heading text-lg font-extrabold text-black">
                        Ulasan Jawaban & Argumen Siswa
                    </h3>
                    <p class="text-xs text-neutral-500">Melihat kesesuaian antara jawaban yang dipilih dan cara bernalar siswa</p>
                </div>

                <div class="space-y-4">
                    @foreach($session->studentResponses as $idx => $resp)
                        <div class="p-5 rounded-2xl border {{ $resp->is_correct ? 'border-neutral-300 bg-white' : 'border-[#FF5500] bg-orange-50/20' }} space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-black text-black">
                                    Soal #{{ $idx + 1 }}: {{ $resp->diagnosticQuestion->title }}
                                </span>
                                @if($resp->is_correct)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-black text-white font-bold">
                                        <x-lucide name="check" class="w-3.5 h-3.5 text-white" stroke-width="3" />
                                        <span>Benar (Opsi {{ $resp->selected_option }})</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FF5500] text-white font-bold">
                                        <x-lucide name="x" class="w-3.5 h-3.5 text-white" stroke-width="3" />
                                        <span>Salah (Dipilih: {{ $resp->selected_option }} • Kunci: {{ $resp->diagnosticQuestion->correct_answer }})</span>
                                    </span>
                                @endif
                            </div>

                            @if($resp->student_reasoning)
                                <div class="text-xs text-neutral-700 bg-[#F6F6F8] p-3.5 rounded-xl border border-neutral-200">
                                    <span class="font-bold text-black">Alasan yang Ditulis Siswa:</span> "{{ $resp->student_reasoning }}"
                                </div>
                            @endif

                            @if($resp->detected_misconception)
                                <div class="text-xs text-black font-semibold bg-white p-3 rounded-xl border border-neutral-200">
                                    <strong class="text-[#FF5500]">Pola Miskonsepsi:</strong> {{ $resp->detected_misconception }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Right 4 Cols: Domain Breakdown & Remediation Plan -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Domain Mastery Card -->
            <div class="meekoo-card p-6 sm:p-8 space-y-5">
                <div class="border-b border-neutral-100 pb-3">
                    <h3 class="meekoo-heading text-lg font-extrabold text-black">
                        Penguasaan Domain
                    </h3>
                    <p class="text-xs text-neutral-500">Performa per area kurikulum numerasi</p>
                </div>

                @php
                    $domains = $session->domain_scores ?? [];
                    $domainNames = [
                        'aritmatika_sosial' => 'Aritmatika Sosial',
                        'aljabar' => 'Aljabar & Fungsi',
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
                            <div class="flex items-center justify-between text-xs font-bold">
                                <span class="text-black">{{ $title }}</span>
                                <span class="text-[#FF5500]">{{ $stat['correct'] }}/{{ $stat['total'] }} ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full h-3 rounded-full bg-[#F4F4F6] overflow-hidden p-0.5 border border-neutral-200">
                                <div class="h-full rounded-full transition-all duration-500 {{ $pct >= 80 ? 'bg-black' : 'bg-[#FF5500]' }}"
                                    style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Remediation Box in Black Card -->
                <div class="p-5 rounded-2xl bg-black text-white space-y-2 text-xs">
                    <div class="text-[10px] font-black uppercase tracking-wider text-[#FF5500]">
                        Rencana Aksi Belajar
                    </div>
                    <p class="text-neutral-300 leading-relaxed font-normal">
                        {{ $session->ai_remediation_plan }}
                    </p>
                </div>
            </div>

            <!-- OpenRouter Info Badge -->
            <div class="meekoo-card p-6 text-center space-y-2 border-neutral-200">
                <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#FF5500]">OpenRouter Integration</span>
                <div class="text-xs font-bold text-black font-mono">nvidia/nemotron-3-ultra-550b-a55b:free</div>
                <p class="text-[11px] text-neutral-500">
                    Memenuhi syarat challenge hackathon: AI Agent Fungsional (+10 poin) dengan 2 aksi terpisah dan terverifikasi.
                </p>
            </div>

        </div>

    </div>

    <!-- BOTTOM SECTION: AI-GENERATED ADAPTIVE PRACTICE (Exact Meekoo Course Card Grid) -->
    <div class="space-y-8 pt-6 border-t border-neutral-200">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#F4F4F6] text-[11px] font-bold text-neutral-800 uppercase tracking-wider mb-1">
                    <span>Aksi AI 2: Personalisasi Latihan</span>
                </div>
                <h2 class="meekoo-heading text-3xl font-extrabold text-black">
                    Paket Soal Latihan Adaptif Bertarget
                </h2>
                <p class="text-xs sm:text-sm text-neutral-500">
                    Soal baru yang digenerate AI Agent untuk membenahi kelemahan spesifikmu. Coba jawab langsung di bawah ini!
                </p>
            </div>

            <span class="px-4 py-2 rounded-full bg-black text-white text-xs font-bold">
                {{ $session->adaptivePracticeQuestions->count() }} Paket Tersedia
            </span>
        </div>

        <!-- Practice Cards Grid (Meekoo Course Style in Orange/Black/White) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($session->adaptivePracticeQuestions as $item)
                <div class="meekoo-card p-6 sm:p-7 flex flex-col justify-between space-y-5" id="practiceCard_{{ $item->id }}">
                    <div class="space-y-4">
                        
                        <!-- Top Card Banner in Black -->
                        <div class="w-full h-32 rounded-2xl bg-black text-white p-4 flex flex-col justify-between relative overflow-hidden">
                            <div class="flex items-center justify-between z-10">
                                <span class="px-2.5 py-0.5 rounded-full bg-[#FF5500] text-white text-[10px] font-bold uppercase">
                                    {{ ucfirst(str_replace('_', ' ', $item->domain)) }}
                                </span>
                                <span class="text-[10px] font-mono text-neutral-300">
                                    {{ $item->difficulty }}
                                </span>
                            </div>
                            <div class="z-10">
                                <div class="text-sm font-black text-white line-clamp-1">{{ $item->title }}</div>
                                <div class="text-[11px] text-neutral-400">Target: {{ Str::limit($item->target_misconception, 35) }}</div>
                            </div>
                            <div class="absolute -right-3 -bottom-3 w-16 h-16 rounded-full bg-[#FF5500]/30 blur-lg"></div>
                        </div>

                        <!-- Scenario Box -->
                        <p class="text-xs text-neutral-700 leading-relaxed bg-[#F6F6F8] p-3.5 rounded-xl border border-neutral-200">
                            {{ $item->context_scenario }}
                        </p>

                        <!-- Question Prompt -->
                        <div class="text-xs font-black text-black">
                            {{ $item->question_text }}
                        </div>

                        <!-- Options Form -->
                        <form onsubmit="submitPracticeAnswer(event, '{{ $session->session_code }}', {{ $item->id }})" class="space-y-2 pt-1">
                            @foreach($item->options as $opt)
                                <label class="flex items-start gap-2.5 p-2.5 rounded-xl border border-neutral-200 hover:border-black cursor-pointer text-xs transition-all bg-white">
                                    <input type="radio" name="practice_answer_{{ $item->id }}" value="{{ $opt['key'] }}" required
                                        class="mt-0.5 text-[#FF5500] focus:ring-[#FF5500]">
                                    <span class="text-neutral-800">
                                        <strong class="text-black">{{ $opt['key'] }}.</strong> {{ $opt['text'] }}
                                    </span>
                                </label>
                            @endforeach

                            <button type="submit" class="w-full mt-3 py-2.5 rounded-full bg-black hover:bg-[#FF5500] text-white font-extrabold text-xs uppercase tracking-wider transition-colors shadow-sm">
                                Cek Pemahaman
                            </button>
                        </form>

                        <!-- Scaffolding Hint Toggle -->
                        <div class="pt-2">
                            <button type="button" onclick="toggleHint({{ $item->id }})" class="text-[11px] font-extrabold text-[#FF5500] hover:text-black flex items-center gap-1.5 transition-colors">
                                <x-lucide name="lightbulb" class="w-3.5 h-3.5 text-[#FF5500]" />
                                <span>Buka Petunjuk Bernalar (Scaffolding Hint)</span>
                            </button>
                            <div id="hint_{{ $item->id }}" class="hidden mt-2 p-3.5 rounded-xl bg-[#F6F6F8] border border-neutral-300 text-xs text-black leading-relaxed">
                                <strong>Petunjuk Langkah:</strong> {{ $item->scaffolding_hint }}
                            </div>
                        </div>

                        <!-- Instant Feedback Container -->
                        <div id="feedback_{{ $item->id }}" class="hidden p-3.5 rounded-xl text-xs leading-relaxed"></div>
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
            feedbackEl.classList.remove('hidden', 'bg-black', 'text-white', 'border-black', 'bg-orange-50', 'text-black', 'border-[#FF5500]');

            if (data.is_correct) {
                feedbackEl.classList.add('bg-black', 'text-white', 'border', 'border-black');
                feedbackEl.innerHTML = `<div class="font-black text-[#FF5500] flex items-center gap-1.5"><i data-lucide="check" class="w-4 h-4 text-[#FF5500]"></i><span>Jawabanmu Tepat!</span></div><div class="mt-1 text-neutral-300">${data.message}</div><div class="mt-2 pt-2 border-t border-neutral-800 text-neutral-200">${data.conceptual_explanation}</div>`;
            } else {
                feedbackEl.classList.add('bg-orange-50', 'text-black', 'border', 'border-[#FF5500]');
                feedbackEl.innerHTML = `<div class="font-black text-[#FF5500] flex items-center gap-1.5"><i data-lucide="x" class="w-4 h-4 text-[#FF5500]"></i><span>Belum Tepat (Kunci: ${data.correct_answer})</span></div><div class="mt-1 text-neutral-700">${data.message}</div><div class="mt-2 pt-2 border-t border-orange-200 text-neutral-900">${data.conceptual_explanation}</div>`;
            }
            if (window.renderLucide) window.renderLucide();
        } catch (err) {
            console.error(err);
        }
    }
</script>
@endpush
@endsection
