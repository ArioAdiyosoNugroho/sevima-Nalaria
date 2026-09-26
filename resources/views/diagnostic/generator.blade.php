@extends('layouts.app')

@section('title', 'Generator Soal AI Literasi & Numerasi — Nalaria')

@section('content')




<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    {{-- Page Hero --}}
    <div class="meekoo-card p-6 sm:p-10 bg-gradient-to-br from-white via-neutral-50 to-[#FFF6F0] border border-orange-200/60 shadow-sm relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-72 h-72 rounded-full bg-[#FF5500]/5 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 bottom-0 w-48 h-48 rounded-full bg-amber-300/10 blur-3xl pointer-events-none"></div>
        <div class="max-w-3xl space-y-3 relative z-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#0A0A0A] text-white text-[11px] font-black uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-[#FF5500] animate-pulse"></span>
                <span>AI Agent Aksi 2 • On-Demand Adaptive Generator</span>
            </div>
            <h1 class="meekoo-heading text-3xl sm:text-4xl lg:text-5xl font-extrabold text-black tracking-tight">
                Generator Soal Literasi & Numerasi
            </h1>
            <p class="text-xs sm:text-sm text-neutral-600 leading-relaxed font-normal">
                Hasilkan paket soal kontekstual berstandar AKM & PISA secara instan — pilih <strong>1 hingga 8 butir soal</strong> dalam mode Numerasi, Literasi, atau Terpadu Literasi-Numerasi. Ditenagai oleh <strong>OpenRouter LLM</strong>.
            </p>
        </div>
    </div>

    {{-- Main Generator Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- Left Column: Controls --}}
        <div class="lg:col-span-5 space-y-5">
            <div class="meekoo-card p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between border-b border-neutral-100 pb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center font-black text-sm">
                            <x-lucide name="sliders" class="w-4 h-4 text-white" />
                        </span>
                        <h2 class="text-base font-extrabold text-black">Konfigurasi Paket Soal</h2>
                    </div>
                    <span class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider">Real-time AI</span>
                </div>

                <form id="generatorForm" action="{{ route('diagnostic.generator.generate') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- 0. Metode Generator --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-black uppercase tracking-wider flex items-center justify-between">
                            <span>Metode Generator <span class="text-[#FF5500]">*</span></span>
                            <span class="text-[10px] text-[#FF5500] font-extrabold uppercase flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#FF5500] animate-pulse"></span>
                                AI Agent Aktif
                            </span>
                        </label>
                        <div class="grid grid-cols-2 gap-2.5" id="engineSelector">
                            <label class="engine-card flex flex-col p-3 rounded-2xl border-2 cursor-pointer transition-all duration-200 border-[#FF5500] bg-orange-50/60 shadow-xs">
                                <input type="radio" name="engine" value="ai" class="sr-only" checked>
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="text-sm">🤖</span>
                                    <span class="text-xs font-black text-black">AI Agent</span>
                                    <span class="px-1.5 py-0.5 rounded-full bg-orange-100 text-[#FF5500] text-[9px] font-black uppercase ml-auto">Pintar</span>
                                </div>
                                <span class="text-[10px] text-neutral-600 font-medium leading-snug">Soal baru unik dirancang on-demand oleh AI Agent</span>
                            </label>
                            <label class="engine-card flex flex-col p-3 rounded-2xl border-2 cursor-pointer transition-all duration-200 border-neutral-200 bg-white hover:border-neutral-300">
                                <input type="radio" name="engine" value="fast" class="sr-only">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="text-sm">⚡</span>
                                    <span class="text-xs font-black text-black">Bank Kurikulum</span>
                                    <span class="px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[9px] font-black uppercase ml-auto">Instan</span>
                                </div>
                                <span class="text-[10px] text-neutral-500 font-medium leading-snug">Soal terverifikasi kurikulum tanpa menunggu API</span>
                            </label>
                        </div>
                    </div>

                    {{-- 1. Domain / Mode --}}
                    <div class="space-y-2.5">
                        <label class="block text-xs font-bold text-black uppercase tracking-wider">
                            Mode & Domain <span class="text-[#FF5500]">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5" id="domainSelector">
                            @php
                                $domains = [
                                    ['id' => 'campuran',            'icon' => 'layers',      'title' => 'Terpadu',          'desc' => 'Literasi + Numerasi'],
                                    ['id' => 'aritmatika_sosial',   'icon' => 'tag',         'title' => 'Aritmatika Sosial', 'desc' => 'Diskon & Finansial'],
                                    ['id' => 'aljabar',             'icon' => 'trending-up', 'title' => 'Aljabar',          'desc' => 'Pemodelan Linier'],
                                    ['id' => 'geometri',            'icon' => 'box',         'title' => 'Geometri Spasial', 'desc' => 'Skala, Luas & Volume'],
                                    ['id' => 'data_ketidakpastian', 'icon' => 'bar-chart-2', 'title' => 'Data & Statistik', 'desc' => 'Rerata & Peluang'],
                                    ['id' => 'literasi_informasi',  'icon' => 'book-open',   'title' => 'Literasi Sains',   'desc' => 'Teks Informasi & Inferensi'],
                                ];
                            @endphp

                            @foreach($domains as $d)
                                <label class="domain-card flex flex-col p-3.5 rounded-2xl border-2 cursor-pointer transition-all duration-200 {{ ($selectedDomain ?? 'campuran') === $d['id'] ? 'border-[#FF5500] bg-orange-50/60 shadow-sm' : 'border-neutral-200 bg-white hover:border-neutral-300' }}">
                                    <input type="radio" name="domain" value="{{ $d['id'] }}" class="sr-only" {{ ($selectedDomain ?? 'campuran') === $d['id'] ? 'checked' : '' }}>
                                    <div class="flex items-center gap-2 mb-1">
                                        <x-lucide :name="$d['icon']" class="w-4 h-4 text-[#FF5500]" />
                                        <span class="text-xs font-black text-black leading-tight">{{ $d['title'] }}</span>
                                    </div>
                                    <span class="text-[10px] text-neutral-500 font-medium">{{ $d['desc'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- 2. Jumlah Soal --}}
                    <div class="space-y-2.5">
                        <label class="block text-xs font-bold text-black uppercase tracking-wider">
                            Jumlah Soal <span class="text-[#FF5500]">*</span>
                            <span id="countDisplay" class="ml-1.5 px-2 py-0.5 rounded-full bg-[#FF5500] text-white font-mono text-[11px] font-black">{{ $selectedCount ?? 3 }}</span>
                        </label>
                        <div class="space-y-2">
                            <input type="range" name="count" id="countRange" min="1" max="8" value="{{ $selectedCount ?? 3 }}"
                                class="w-full h-2 rounded-full appearance-none cursor-pointer accent-[#FF5500] bg-neutral-200">
                            <div class="flex justify-between text-[10px] text-neutral-400 font-bold px-0.5">
                                @for($i = 1; $i <= 8; $i++)
                                    <span>{{ $i }}</span>
                                @endfor
                            </div>
                        </div>
                        <p class="text-[10px] text-neutral-500">Pilih 1–8 butir soal untuk satu paket asesmen</p>
                    </div>

                    {{-- 3. Tingkat Kesulitan --}}
                    <div class="space-y-2.5">
                        <label class="block text-xs font-bold text-black uppercase tracking-wider">
                            Tingkat Kesulitan <span class="text-[#FF5500]">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-2" id="difficultySelector">
                            @php
                                $difficulties = [
                                    ['level' => 'Mudah',     'pisa' => 'Level 2 PISA',   'desc' => 'Konsep Dasar'],
                                    ['level' => 'Sedang',    'pisa' => 'Level 3 PISA',   'desc' => 'Aplikasi 2 Langkah'],
                                    ['level' => 'Menantang', 'pisa' => 'Level 4-5 HOTS', 'desc' => 'Multivariabel'],
                                ];
                            @endphp

                            @foreach($difficulties as $diff)
                                <label class="difficulty-card text-center p-3 rounded-2xl border-2 cursor-pointer transition-all duration-200 {{ ($selectedDifficulty ?? 'Sedang') === $diff['level'] ? 'border-[#FF5500] bg-[#FF5500] text-white shadow-sm' : 'border-neutral-200 bg-white text-black hover:border-neutral-300' }}">
                                    <input type="radio" name="difficulty" value="{{ $diff['level'] }}" class="sr-only" {{ ($selectedDifficulty ?? 'Sedang') === $diff['level'] ? 'checked' : '' }}>
                                    <div class="text-xs font-black tracking-tight">{{ $diff['level'] }}</div>
                                    <div class="text-[10px] {{ ($selectedDifficulty ?? 'Sedang') === $diff['level'] ? 'text-white/80' : 'text-neutral-500' }} font-bold mt-0.5">{{ $diff['pisa'] }}</div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- 4. Konteks / Topik --}}
                    <div class="space-y-2">
                        <label for="contextInput" class="block text-xs font-bold text-black uppercase tracking-wider">
                            Konteks / Skenario (Opsional)
                        </label>
                        <input type="text" name="context" id="contextInput" value="{{ $selectedContext ?? '' }}"
                            placeholder="Contoh: Efisiensi Panel Surya, Daur Ulang Plastik"
                            class="w-full px-4 py-3 rounded-2xl border border-neutral-300 focus:outline-none focus:border-[#FF5500] focus:ring-1 focus:ring-[#FF5500] text-xs font-semibold transition-all bg-white">
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            <button type="button" class="quick-chip text-[11px] font-bold px-3 py-1 rounded-full bg-neutral-100 hover:bg-neutral-200 text-neutral-700 transition-colors inline-flex items-center gap-1.5" data-topic="Efisiensi Panel Surya Sekolah">
                                <x-lucide name="sun" class="w-3.5 h-3.5 text-amber-500" />
                                <span>Panel Surya</span>
                            </button>
                            <button type="button" class="quick-chip text-[11px] font-bold px-3 py-1 rounded-full bg-neutral-100 hover:bg-neutral-200 text-neutral-700 transition-colors inline-flex items-center gap-1.5" data-topic="Bank Sampah & Daur Ulang">
                                <x-lucide name="recycle" class="w-3.5 h-3.5 text-emerald-500" />
                                <span>Daur Ulang</span>
                            </button>
                            <button type="button" class="quick-chip text-[11px] font-bold px-3 py-1 rounded-full bg-neutral-100 hover:bg-neutral-200 text-neutral-700 transition-colors inline-flex items-center gap-1.5" data-topic="Audit Debit Air Bersih">
                                <x-lucide name="droplets" class="w-3.5 h-3.5 text-sky-500" />
                                <span>Konservasi Air</span>
                            </button>
                            <button type="button" class="quick-chip text-[11px] font-bold px-3 py-1 rounded-full bg-neutral-100 hover:bg-neutral-200 text-neutral-700 transition-colors inline-flex items-center gap-1.5" data-topic="Kebun Hidroponik Komunitas">
                                <x-lucide name="sprout" class="w-3.5 h-3.5 text-green-500" />
                                <span>Hidroponik</span>
                            </button>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" id="generateBtn"
                        class="w-full py-4 rounded-full bg-[#FF5500] hover:bg-[#E04B00] text-white text-xs font-black tracking-wide uppercase transition-all duration-200 shadow-lg shadow-orange-500/20 active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                        <span id="btnIcon" class="flex items-center">
                            <x-lucide name="sparkles" class="w-4 h-4 text-white" />
                        </span>
                        <span id="btnText">Generate Soal dengan AI Agent</span>
                    </button>
                </form>
            </div>

            {{-- Package Score Card --}}
            <div id="packageScoreCard" class="meekoo-card p-5 bg-gradient-to-br from-black to-neutral-900 text-white space-y-3 hidden">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#FF5500]"></span>
                        <span class="text-[11px] font-black uppercase tracking-wider text-neutral-300">Skor Paket Saat Ini</span>
                    </div>
                    <span id="pkgProgressText" class="text-[11px] font-bold text-neutral-400">0 / 0 dijawab</span>
                </div>
                <div class="flex items-end gap-3">
                    <span id="pkgScoreDisplay" class="text-4xl font-black tabular-nums">0</span>
                    <span class="text-neutral-400 text-sm pb-1 font-bold">/ 100</span>
                </div>
                <div class="w-full h-2 bg-neutral-700 rounded-full overflow-hidden">
                    <div id="pkgScoreBar" class="h-full rounded-full bg-[#FF5500] transition-all duration-500" style="width:0%"></div>
                </div>
                <p id="pkgScoreLabel" class="text-[11px] text-neutral-400 font-medium">Jawab soal untuk mulai mengakumulasi skor</p>
            </div>

            {{-- Asesmen Full CTA --}}
            <div class="meekoo-card p-6 bg-gradient-to-br from-neutral-900 to-black text-white space-y-4">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#FF5500]"></span>
                    <h3 class="text-xs font-black uppercase tracking-wider text-neutral-300">Asesmen AI Lengkap</h3>
                </div>
                <p class="text-xs text-neutral-400 leading-relaxed">
                    Ingin diagnosa miskonsepsi kognitif otomatis & rapor personal? Ikuti tes diagnostik 5 soal terstandar.
                </p>
                <a href="{{ route('diagnostic.quiz') }}"
                    class="w-full py-2.5 rounded-full bg-white hover:bg-[#FF5500] text-black hover:text-white text-xs font-extrabold text-center transition-all duration-200 flex items-center justify-center gap-2">
                    <span>Mulai Asesmen Diagnostik (5 Soal)</span>
                    <span>→</span>
                </a>
            </div>
        </div>

        {{-- Right Column: Question Display --}}
        <div class="lg:col-span-7 space-y-5">

            {{-- Notification Toast / Banner --}}
            <div id="generatorNotification" class="hidden"></div>

            {{-- AI Agent Inline Loading State (Sleek, Clean & Responsive) --}}
            <div id="aiInlineLoading" class="hidden meekoo-card p-8 sm:p-12 bg-gradient-to-br from-[#FFF9F5] via-white to-orange-50/40 border-2 border-orange-200/90 text-center space-y-5 transition-all">
                <div class="relative w-16 h-16 mx-auto flex items-center justify-center">
                    <div class="absolute inset-0 rounded-full bg-[#FF5500]/15 animate-ping"></div>
                    <div class="relative w-14 h-14 rounded-full bg-gradient-to-tr from-[#FF5500] to-amber-500 flex items-center justify-center shadow-lg shadow-orange-500/25">
                        <svg class="w-7 h-7 text-white animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-100 text-[#FF5500] text-[11px] font-black uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-[#FF5500] animate-pulse"></span>
                        <span>AI Agent Aktif</span>
                    </div>
                    <h3 id="inlineLoadingTitle" class="meekoo-heading text-lg sm:text-xl font-black text-black">
                        Merancang Soal Adaptif...
                    </h3>
                    <p id="inlineLoadingDesc" class="text-xs sm:text-sm text-neutral-600 max-w-md mx-auto leading-relaxed font-medium">
                        AI Agent sedang menyusun stimulus narasi PISA/AKM, perumusan pertanyaan penalaran kritis, dan validasi kunci jawaban.
                    </p>
                </div>
                <div class="w-48 h-1.5 bg-neutral-200 rounded-full mx-auto overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-[#FF5500] to-amber-500 rounded-full animate-pulse w-3/4"></div>
                </div>
                <p class="text-[11px] text-neutral-400 font-medium">Biasanya 3–6 detik • Instant fallback jika API lambat</p>
            </div>

            {{-- Package Info Bar + Tab Navigation --}}
            <div id="packageInfoBar" class="meekoo-card p-4 sm:p-5 space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span id="pkgTitle" class="text-xs font-black text-black uppercase tracking-wider">
                            {{ $initialPackage['package_title'] ?? 'Paket Soal AI' }}
                        </span>
                        <span id="pkgCompetencyBadge" class="px-2.5 py-0.5 rounded-full bg-[#FF5500] text-white text-[10px] font-black uppercase tracking-wider">
                            {{ $initialPackage['competency'] ?? 'Literasi & Numerasi' }}
                        </span>
                        <span id="pkgSourceBadge" aria-live="polite" class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-wider">
                            Soal Dummy Lokal
                        </span>
                    </div>
                    <span id="pkgDifficultyBadge" class="px-2.5 py-0.5 rounded-full bg-black text-white text-[10px] font-black uppercase tracking-wider">
                        Level: {{ $initialPackage['difficulty'] ?? 'Sedang' }}
                    </span>
                </div>

                {{-- Question Tabs --}}
                <div id="questionTabs" class="flex flex-wrap gap-2">
                    {{-- Rendered by JS --}}
                </div>
            </div>

            {{-- Question Card --}}
            <div id="questionDisplayCard" class="meekoo-card p-6 sm:p-8 space-y-6 bg-white border border-neutral-200/90 shadow-sm transition-all duration-300">

                {{-- Card Header --}}
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span id="qNumberBadge" class="px-3 py-1 rounded-full bg-black text-white text-[11px] font-black uppercase tracking-wider">
                            Soal 1
                        </span>
                        <span id="qDomainBadge" class="px-3 py-1 rounded-full bg-[#FF5500] text-white text-[11px] font-black uppercase tracking-wider shadow-xs">
                            {{ $initialPackage['questions'][0]['domain_label'] ?? 'Aritmatika Sosial' }}
                        </span>
                        <span id="qCompetencyBadge" class="px-3 py-1 rounded-full border border-neutral-300 text-neutral-700 text-[11px] font-black uppercase tracking-wider">
                            {{ $initialPackage['questions'][0]['competency'] ?? 'Numerasi' }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] font-mono text-neutral-500">
                        <span id="qStatusDot" class="w-2 h-2 rounded-full bg-neutral-300"></span>
                        <span id="qStatusText">Belum Dijawab</span>
                    </div>
                </div>

                {{-- Title & Scenario --}}
                <div class="space-y-4">
                    <h3 id="qTitle" class="meekoo-heading text-xl sm:text-2xl font-black text-black">
                        {{ $initialPackage['questions'][0]['title'] ?? 'Latihan Numerasi Adaptif' }}
                    </h3>

                    <div id="qScenarioWrapper" class="p-4 sm:p-5 rounded-2xl bg-neutral-50 border border-neutral-200/80 space-y-2">
                        <div class="flex items-center gap-2 text-xs font-black text-neutral-700">
                            <x-lucide name="book-open" class="w-4 h-4 text-[#FF5500]" />
                            <span>Skenario Kontekstual:</span>
                        </div>
                        <div id="qScenario" class="ai-markdown-content text-xs sm:text-sm text-neutral-700 leading-relaxed font-medium">
                            {!! Str::markdown($initialPackage['questions'][0]['context_scenario'] ?? '', ['html_input' => 'strip', 'allow_unsafe_links' => false, 'renderer' => ['soft_break' => "<br>\n"]]) !!}
                        </div>
                    </div>

                    <div class="pt-1">
                        <p class="text-xs font-bold uppercase tracking-wider text-neutral-400 mb-1.5">Pertanyaan:</p>
                        <div id="qText" class="ai-markdown-content text-sm sm:text-base font-extrabold text-black leading-relaxed">
                            {!! Str::inlineMarkdown($initialPackage['questions'][0]['question_text'] ?? '') !!}
                        </div>
                    </div>
                </div>

                {{-- Options --}}
                <div class="space-y-3 pt-1">
                    <p class="text-xs font-bold uppercase tracking-wider text-black">Pilih Opsi Jawaban:</p>
                    <div id="optionsContainer" class="space-y-2.5">
                        @foreach(($initialPackage['questions'][0]['options'] ?? []) as $opt)
                            <label class="option-row flex items-start gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 border-neutral-200 hover:border-black cursor-pointer transition-all duration-200 bg-white">
                                <input type="radio" name="practice_answer" value="{{ $opt['key'] }}" class="sr-only">
                                <span class="opt-key w-7 h-7 rounded-full bg-neutral-100 text-black text-xs font-black flex items-center justify-center shrink-0 border border-neutral-300">
                                    {{ $opt['key'] }}
                                </span>
                                <span class="opt-text text-xs sm:text-sm font-semibold text-neutral-800 leading-snug pt-0.5">
                                    {{ $opt['text'] }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Check Answer + Feedback --}}
                <div class="pt-2 space-y-4">
                    <button type="button" id="checkAnswerBtn"
                        class="w-full sm:w-auto px-6 py-3 rounded-full bg-black hover:bg-[#FF5500] text-white text-xs font-black uppercase tracking-wider transition-colors duration-200 active:scale-95 cursor-pointer">
                        Periksa Jawaban Saya
                    </button>

                    <div id="answerFeedbackBox" class="hidden p-4 rounded-2xl border transition-all duration-300">
                        <div class="flex items-start gap-3">
                            <span id="feedbackIcon" class="flex items-center shrink-0 mt-0.5"></span>
                            <div class="space-y-1">
                                <p id="feedbackTitle" class="text-xs font-black uppercase tracking-wider"></p>
                                <p id="feedbackMessage" class="text-xs sm:text-sm font-medium leading-relaxed"></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hint Accordion --}}
                <div class="border-t border-neutral-100 pt-5 space-y-3">
                    <button type="button" id="hintToggleBtn"
                        class="flex items-center justify-between w-full p-3.5 rounded-2xl bg-[#FFF6F0] hover:bg-[#FFEBDC] text-[#FF5500] transition-colors cursor-pointer">
                        <div class="flex items-center gap-2.5">
                            <x-lucide name="lightbulb" class="w-4 h-4 text-[#FF5500]" />
                            <span class="text-xs font-extrabold uppercase tracking-wider">Butuh Bantuan? Buka Petunjuk Bernalar</span>
                        </div>
                        <span id="hintChevron" class="text-sm font-bold transition-transform duration-200">↓</span>
                    </button>
                    <div id="hintContent" class="hidden p-4 sm:p-5 rounded-2xl bg-[#FFFBF8] border border-orange-200/80 text-xs sm:text-sm text-neutral-800 leading-relaxed font-medium">
                        <div id="qHint" class="ai-markdown-content"></div>
                    </div>
                </div>

                {{-- Explanation Accordion --}}
                <div class="border-t border-neutral-100 pt-5 space-y-3">
                    <button type="button" id="explanationToggleBtn"
                        class="flex items-center justify-between w-full p-3.5 rounded-2xl bg-neutral-100 hover:bg-neutral-200 text-neutral-800 transition-colors cursor-pointer">
                        <div class="flex items-center gap-2.5">
                            <x-lucide name="book-open" class="w-4 h-4 text-neutral-700" />
                            <span class="text-xs font-extrabold uppercase tracking-wider">Lihat Pembahasan & Kunci Jawaban</span>
                        </div>
                        <span id="explanationChevron" class="text-sm font-bold transition-transform duration-200">↓</span>
                    </button>
                    <div id="explanationContent" class="hidden p-4 sm:p-5 rounded-2xl bg-neutral-50 border border-neutral-200 text-xs sm:text-sm text-neutral-800 leading-relaxed font-medium space-y-2">
                        <div class="flex items-center gap-2 text-xs font-black text-black">
                            <span>Kunci Jawaban:</span>
                            <span id="qCorrectKey" class="px-2.5 py-0.5 rounded-full bg-black text-white font-mono font-bold"></span>
                        </div>
                        <div id="qExplanation" class="ai-markdown-content"></div>
                    </div>
                </div>

            </div>

            {{-- Package Completion Card (hidden until all answered) --}}
            <div id="packageCompleteCard" class="meekoo-card p-8 bg-gradient-to-br from-emerald-50 to-white border border-emerald-200 text-center space-y-5 hidden">
                <div class="w-16 h-16 rounded-full bg-emerald-500 flex items-center justify-center mx-auto shadow-lg shadow-emerald-300/40">
                    <i data-lucide="trophy" class="w-8 h-8 text-white"></i>
                </div>
                <div class="space-y-2">
                    <h3 class="text-xl font-black text-black">Paket Selesai! 🎉</h3>
                    <p class="text-sm text-neutral-600 font-medium">Kamu telah menyelesaikan seluruh soal dalam paket ini.</p>
                </div>
                <div class="flex items-center justify-center gap-6">
                    <div class="text-center">
                        <div id="finalScore" class="text-4xl font-black text-[#FF5500]">0</div>
                        <div class="text-xs text-neutral-500 font-bold uppercase tracking-wider mt-0.5">Skor Akhir</div>
                    </div>
                    <div class="w-px h-12 bg-neutral-200"></div>
                    <div class="text-center">
                        <div id="finalCorrect" class="text-4xl font-black text-emerald-600">0</div>
                        <div class="text-xs text-neutral-500 font-bold uppercase tracking-wider mt-0.5">Benar</div>
                    </div>
                    <div class="w-px h-12 bg-neutral-200"></div>
                    <div class="text-center">
                        <div id="finalTotal" class="text-4xl font-black text-neutral-700">0</div>
                        <div class="text-xs text-neutral-500 font-bold uppercase tracking-wider mt-0.5">Total Soal</div>
                    </div>
                </div>
                <button type="button" id="regenerateBtn"
                    class="mx-auto px-8 py-3.5 rounded-full bg-[#FF5500] hover:bg-[#E04B00] text-white text-xs font-black uppercase tracking-wide transition-all duration-200 shadow-lg shadow-orange-500/20 active:scale-95 cursor-pointer flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    <span>Generate Paket Baru</span>
                </button>
            </div>

        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ─────────────── MARKDOWN HELPER ───────────────
    function formatMarkdown(text) {
        if (!text || typeof text !== 'string') return '';
        let escaped = text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong class="font-extrabold text-black">$1</strong>');
        escaped = escaped.replace(/__(.*?)__/g, '<strong class="font-extrabold text-black">$1</strong>');
        escaped = escaped.replace(/\*(.*?)\*/g, '<em>$1</em>');
        escaped = escaped.replace(/^\s*[-*]\s+(.*)$/gm, '<li class="ml-4 list-disc">$1</li>');
        escaped = escaped.replace(/\n\n+/g, '</p><p class="mt-2.5">');
        escaped = escaped.replace(/\n/g, '<br>');
        return `<p>${escaped}</p>`;
    }

    function formatInlineMarkdown(text) {
        if (!text || typeof text !== 'string') return '';
        let escaped = text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong class="font-extrabold text-black">$1</strong>');
        escaped = escaped.replace(/__(.*?)__/g, '<strong class="font-extrabold text-black">$1</strong>');
        return escaped;
    }

    // ─────────────── STATE ───────────────
    let currentPackage = @json($initialPackage);
    let currentQuestionIndex = 0;

    // Map: questionIndex → { selectedKey, isCorrect }
    let studentAnswers = {};

    // ─────────────── ELEMENTS ───────────────
    const form              = document.getElementById('generatorForm');
    const generateBtn       = document.getElementById('generateBtn');
    const btnText           = document.getElementById('btnText');
    const btnIcon           = document.getElementById('btnIcon');
    const countRange        = document.getElementById('countRange');
    const countDisplay      = document.getElementById('countDisplay');

    const packageInfoBar    = document.getElementById('packageInfoBar');
    const pkgTitle          = document.getElementById('pkgTitle');
    const pkgCompetencyBadge= document.getElementById('pkgCompetencyBadge');
    const pkgSourceBadge    = document.getElementById('pkgSourceBadge');
    const pkgDifficultyBadge= document.getElementById('pkgDifficultyBadge');
    const questionTabs      = document.getElementById('questionTabs');

    const packageScoreCard  = document.getElementById('packageScoreCard');
    const pkgProgressText   = document.getElementById('pkgProgressText');
    const pkgScoreDisplay   = document.getElementById('pkgScoreDisplay');
    const pkgScoreBar       = document.getElementById('pkgScoreBar');
    const pkgScoreLabel     = document.getElementById('pkgScoreLabel');

    const questionDisplayCard  = document.getElementById('questionDisplayCard');
    const packageCompleteCard  = document.getElementById('packageCompleteCard');

    const qNumberBadge      = document.getElementById('qNumberBadge');
    const qDomainBadge      = document.getElementById('qDomainBadge');
    const qCompetencyBadge  = document.getElementById('qCompetencyBadge');
    const qStatusDot        = document.getElementById('qStatusDot');
    const qStatusText       = document.getElementById('qStatusText');
    const qTitle            = document.getElementById('qTitle');
    const qScenario         = document.getElementById('qScenario');
    const qText             = document.getElementById('qText');
    const optionsContainer  = document.getElementById('optionsContainer');
    const qHint             = document.getElementById('qHint');
    const qExplanation      = document.getElementById('qExplanation');
    const qCorrectKey       = document.getElementById('qCorrectKey');

    const checkAnswerBtn    = document.getElementById('checkAnswerBtn');
    const answerFeedbackBox = document.getElementById('answerFeedbackBox');
    const feedbackIcon      = document.getElementById('feedbackIcon');
    const feedbackTitle     = document.getElementById('feedbackTitle');
    const feedbackMessage   = document.getElementById('feedbackMessage');

    const hintToggleBtn     = document.getElementById('hintToggleBtn');
    const hintContent       = document.getElementById('hintContent');
    const hintChevron       = document.getElementById('hintChevron');

    const explanationToggleBtn  = document.getElementById('explanationToggleBtn');
    const explanationContent    = document.getElementById('explanationContent');
    const explanationChevron    = document.getElementById('explanationChevron');

    const finalScore        = document.getElementById('finalScore');
    const finalCorrect      = document.getElementById('finalCorrect');
    const finalTotal        = document.getElementById('finalTotal');
    const regenerateBtn     = document.getElementById('regenerateBtn');

    // ─────────────── HELPERS ───────────────

    function renderLucideIcons() {
        try {
            if (typeof window.renderLucide === 'function') {
                window.renderLucide();
            } else if (window.lucide && typeof window.lucide.createIcons === 'function') {
                if (window.lucide.icons) {
                    window.lucide.createIcons({ icons: window.lucide.icons });
                } else {
                    window.lucide.createIcons();
                }
            }
        } catch (e) {
            // Silently swallow Lucide errors so UI never breaks
        }
    }

    function computePackageScore() {
        const questions = currentPackage.questions || [];
        const total = questions.length;
        const answered = Object.keys(studentAnswers).length;
        const correct = Object.values(studentAnswers).filter(a => a.isCorrect).length;
        const score = total > 0 ? Math.round((correct / total) * 100) : 0;
        return { total, answered, correct, score };
    }

    function updatePackageScoreCard() {
        const { total, answered, correct, score } = computePackageScore();
        packageScoreCard.classList.remove('hidden');
        pkgProgressText.textContent = `${answered} / ${total} dijawab`;
        pkgScoreDisplay.textContent = score;
        pkgScoreBar.style.width = score + '%';

        let label = 'Jawab soal untuk mulai mengakumulasi skor';
        if (answered > 0) {
            if (score >= 80) label = 'Sangat Baik — Pertahankan!';
            else if (score >= 60) label = 'Cukup Baik — Terus Tingkatkan';
            else if (score >= 40) label = 'Perlu Latihan Lebih — Jangan Menyerah';
            else label = 'Butuh Remedial — Baca Pembahasan dengan Teliti';
        }
        pkgScoreLabel.textContent = label;
    }

    function renderTabs() {
        const questions = currentPackage.questions || [];
        questionTabs.innerHTML = '';
        questions.forEach((q, idx) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.dataset.idx = idx;

            const ans = studentAnswers[idx];
            let stateClass = '';
            if (ans === undefined) {
                stateClass = idx === currentQuestionIndex
                    ? 'bg-black text-white border-black'
                    : 'bg-white text-neutral-700 border-neutral-300 hover:border-neutral-500';
            } else if (ans.isCorrect) {
                stateClass = idx === currentQuestionIndex
                    ? 'bg-emerald-600 text-white border-emerald-600 ring-2 ring-emerald-400'
                    : 'bg-emerald-100 text-emerald-800 border-emerald-400';
            } else {
                stateClass = idx === currentQuestionIndex
                    ? 'bg-red-600 text-white border-red-600 ring-2 ring-red-400'
                    : 'bg-red-100 text-red-800 border-red-400';
            }

            btn.className = `w-8 h-8 rounded-full border-2 text-xs font-black flex items-center justify-center transition-all duration-200 cursor-pointer ${stateClass}`;
            btn.textContent = idx + 1;

            btn.addEventListener('click', () => switchQuestion(idx));
            questionTabs.appendChild(btn);
        });
    }

    function switchQuestion(idx) {
        const questions = currentPackage?.questions || [];
        if (idx < 0 || idx >= questions.length) return;
        currentQuestionIndex = idx;

        const q = questions[idx];
        if (!q) return;

        // Update question card UI
        if (qNumberBadge) qNumberBadge.textContent = `Soal ${idx + 1}`;
        if (qDomainBadge) qDomainBadge.textContent = q.domain_label || '';
        if (qCompetencyBadge) qCompetencyBadge.textContent = q.competency || 'Numerasi';
        if (qTitle) qTitle.textContent = q.title || '';
        if (qScenario) qScenario.innerHTML = formatMarkdown(q.context_scenario || '');
        if (qText) qText.innerHTML = formatMarkdown(q.question_text || '');
        if (qHint) qHint.innerHTML = formatMarkdown(q.scaffolding_hint || '');
        if (qExplanation) qExplanation.innerHTML = formatMarkdown(q.conceptual_explanation || '');
        if (qCorrectKey) qCorrectKey.textContent = q.correct_answer || '';

        // Status badge
        const ans = studentAnswers[idx];
        if (qStatusDot && qStatusText) {
            if (ans === undefined) {
                qStatusDot.className = 'w-2 h-2 rounded-full bg-neutral-300';
                qStatusText.textContent = 'Belum Dijawab';
            } else if (ans.isCorrect) {
                qStatusDot.className = 'w-2 h-2 rounded-full bg-emerald-500';
                qStatusText.textContent = 'Benar ✓';
            } else {
                qStatusDot.className = 'w-2 h-2 rounded-full bg-red-500';
                qStatusText.textContent = 'Salah ✗';
            }
        }

        // Re-render options
        if (optionsContainer) {
            optionsContainer.innerHTML = '';
            const rawOptions = Array.isArray(q.options)
                ? q.options
                : (q.options && typeof q.options === 'object' ? Object.values(q.options) : []);

            rawOptions.forEach(opt => {
                const optKey = (opt && opt.key) ? opt.key : '';
                const optText = (opt && opt.text) ? opt.text : (typeof opt === 'string' ? opt : '');
                const label = document.createElement('label');
                let rowClass = 'option-row flex items-start gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200 ';
                let keyClass = 'opt-key w-7 h-7 rounded-full text-xs font-black flex items-center justify-center shrink-0 border ';

                if (ans !== undefined) {
                    // Show result state
                    if (optKey === q.correct_answer) {
                        rowClass += 'border-emerald-400 bg-emerald-50';
                        keyClass += 'bg-emerald-500 text-white border-emerald-500';
                    } else if (ans.selectedKey === optKey) {
                        rowClass += 'border-red-400 bg-red-50';
                        keyClass += 'bg-red-500 text-white border-red-500';
                    } else {
                        rowClass += 'border-neutral-200 bg-white';
                        keyClass += 'bg-neutral-100 text-black border-neutral-300';
                    }
                } else if (ans === undefined) {
                    rowClass += 'border-neutral-200 bg-white hover:border-black';
                    keyClass += 'bg-neutral-100 text-black border-neutral-300';
                }

                label.className = rowClass;
                label.innerHTML = `
                    <input type="radio" name="practice_answer" value="${optKey}" class="sr-only" ${ans?.selectedKey === optKey ? 'checked' : ''}>
                    <span class="${keyClass}">${optKey}</span>
                    <span class="opt-text text-xs sm:text-sm font-semibold text-neutral-800 leading-snug pt-0.5">${formatInlineMarkdown(optText)}</span>
                `;
                optionsContainer.appendChild(label);
            });
        }

        // Reset accordions
        if (hintContent) hintContent.classList.add('hidden');
        if (hintChevron) hintChevron.textContent = '↓';
        if (explanationContent) explanationContent.classList.add('hidden');
        if (explanationChevron) explanationChevron.textContent = '↓';

        // Feedback box
        if (answerFeedbackBox) {
            answerFeedbackBox.classList.add('hidden');
            answerFeedbackBox.classList.remove('bg-emerald-50', 'border-emerald-300', 'text-emerald-900', 'bg-red-50', 'border-red-300', 'text-red-900');

            if (ans !== undefined) {
                // Re-show feedback for already answered questions
                answerFeedbackBox.classList.remove('hidden');
                if (ans.isCorrect) {
                    answerFeedbackBox.classList.add('bg-emerald-50', 'border-emerald-300', 'text-emerald-900');
                    if (feedbackIcon) feedbackIcon.innerHTML = '<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>';
                    if (feedbackTitle) feedbackTitle.textContent = 'Jawabanmu Tepat!';
                    if (feedbackMessage) feedbackMessage.textContent = 'Luar biasa! Penalaran yang kamu gunakan sudah benar.';
                } else {
                    answerFeedbackBox.classList.add('bg-red-50', 'border-red-300', 'text-red-900');
                    if (feedbackIcon) feedbackIcon.innerHTML = '<i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>';
                    if (feedbackTitle) feedbackTitle.textContent = 'Jawabanmu Belum Tepat';
                    if (feedbackMessage) feedbackMessage.textContent = `Kamu memilih opsi ${ans.selectedKey}. Kunci jawaban yang benar adalah opsi ${q.correct_answer}. Lihat pembahasan di bawah.`;
                }
                if (explanationContent) explanationContent.classList.remove('hidden');
                if (explanationChevron) explanationChevron.textContent = '↑';
            }
        }

        // Check if answer was submitted — lock radio buttons
        if (checkAnswerBtn) {
            if (ans !== undefined) {
                if (optionsContainer) {
                    optionsContainer.querySelectorAll('.option-row').forEach(row => {
                        row.style.cursor = 'default';
                        row.style.pointerEvents = 'none';
                    });
                }
                checkAnswerBtn.disabled = true;
                checkAnswerBtn.className = checkAnswerBtn.className.replace('hover:bg-[#FF5500] cursor-pointer', 'opacity-40 cursor-not-allowed');
            } else {
                checkAnswerBtn.disabled = false;
                checkAnswerBtn.className = 'w-full sm:w-auto px-6 py-3 rounded-full bg-black hover:bg-[#FF5500] text-white text-xs font-black uppercase tracking-wider transition-colors duration-200 active:scale-95 cursor-pointer';
                setupOptionRowEvents();
            }
        }

        renderTabs();
        renderLucideIcons();
    }

    function loadPackage(pkg) {
        if (!pkg || !pkg.questions || !pkg.questions.length) return;
        currentPackage = pkg;
        currentQuestionIndex = 0;
        studentAnswers = {};

        // Update package info bar
        if (pkgTitle) pkgTitle.textContent = pkg.package_title || 'Paket Soal AI';
        if (pkgCompetencyBadge) pkgCompetencyBadge.textContent = pkg.competency || 'Numerasi';
        if (pkgSourceBadge) {
            const aiGeneratedCount = Number(pkg.ai_generated_count || 0);
            pkgSourceBadge.textContent = aiGeneratedCount > 0
                ? `AI Generatif ${aiGeneratedCount}/${pkg.questions.length}`
                : 'Soal Dummy Lokal';
            pkgSourceBadge.className = aiGeneratedCount > 0
                ? 'px-2.5 py-0.5 rounded-full bg-violet-100 text-violet-700 text-[10px] font-black uppercase tracking-wider'
                : 'px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-wider';
        }
        if (pkgDifficultyBadge) pkgDifficultyBadge.textContent = 'Level: ' + (pkg.difficulty || 'Sedang');

        // Show question card, hide completion card
        if (questionDisplayCard) questionDisplayCard.classList.remove('hidden');
        if (packageCompleteCard) packageCompleteCard.classList.add('hidden');

        updatePackageScoreCard();
        renderTabs();
        switchQuestion(0);
    }

    function checkIfPackageComplete() {
        const questions = currentPackage.questions || [];
        const answeredCount = Object.keys(studentAnswers).length;
        if (answeredCount >= questions.length && questions.length > 0) {
            const { correct, score } = computePackageScore();
            finalScore.textContent = score;
            finalCorrect.textContent = correct;
            finalTotal.textContent = questions.length;
            questionDisplayCard.classList.add('hidden');
            packageCompleteCard.classList.remove('hidden');
            renderLucideIcons();
        }
    }

    // ─────────────── OPTION EVENTS ───────────────

    function setupOptionRowEvents() {
        document.querySelectorAll('.option-row').forEach(row => {
            row.addEventListener('click', function () {
                document.querySelectorAll('.option-row').forEach(r => {
                    r.classList.remove('border-[#FF5500]', 'bg-orange-50/50');
                    r.classList.add('border-neutral-200', 'bg-white');
                    const key = r.querySelector('.opt-key');
                    if (key) {
                        key.classList.remove('bg-[#FF5500]', 'text-white', 'border-[#FF5500]');
                        key.classList.add('bg-neutral-100', 'text-black', 'border-neutral-300');
                    }
                });
                this.classList.remove('border-neutral-200', 'bg-white');
                this.classList.add('border-[#FF5500]', 'bg-orange-50/50');
                const key = this.querySelector('.opt-key');
                if (key) {
                    key.classList.remove('bg-neutral-100', 'text-black', 'border-neutral-300');
                    key.classList.add('bg-[#FF5500]', 'text-white', 'border-[#FF5500]');
                }
                const radio = this.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
            });
        });
    }

    // ─────────────── CHECK ANSWER ───────────────

    checkAnswerBtn.addEventListener('click', function () {
        const selectedRadio = document.querySelector('input[name="practice_answer"]:checked');
        if (!selectedRadio) {
            alert('Pilihlah salah satu opsi jawaban (A, B, C, atau D) terlebih dahulu!');
            return;
        }

        const q = currentPackage.questions[currentQuestionIndex];
        const selectedKey = selectedRadio.value.toUpperCase();
        const correctKey  = (q.correct_answer || '').toUpperCase();
        const isCorrect   = (selectedKey === correctKey);

        // Record answer
        studentAnswers[currentQuestionIndex] = { selectedKey, isCorrect };

        // Show feedback
        answerFeedbackBox.classList.remove('hidden', 'bg-emerald-50', 'border-emerald-300', 'text-emerald-900', 'bg-red-50', 'border-red-300', 'text-red-900');

        if (isCorrect) {
            answerFeedbackBox.classList.add('bg-emerald-50', 'border-emerald-300', 'text-emerald-900');
            feedbackIcon.innerHTML = '<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>';
            feedbackTitle.textContent = 'Jawabanmu Tepat!';
            feedbackMessage.textContent = 'Luar biasa! Penalaran matematis dan konseptual yang kamu gunakan sudah benar.';
        } else {
            answerFeedbackBox.classList.add('bg-red-50', 'border-red-300', 'text-red-900');
            feedbackIcon.innerHTML = '<i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>';
            feedbackTitle.textContent = 'Jawabanmu Belum Tepat';
            feedbackMessage.textContent = `Kamu memilih opsi ${selectedKey}. Kunci jawaban yang benar adalah opsi ${correctKey}. Buka pembahasan di bawah untuk memahami langkah bernalar yang tepat.`;
        }

        // Auto open explanation
        explanationContent.classList.remove('hidden');
        explanationChevron.textContent = '↑';

        // Lock options
        optionsContainer.querySelectorAll('.option-row').forEach(row => {
            const optKey = row.querySelector('input').value.toUpperCase();
            if (optKey === correctKey) {
                row.classList.remove('border-neutral-200', 'bg-white', 'border-[#FF5500]', 'bg-orange-50/50');
                row.classList.add('border-emerald-400', 'bg-emerald-50');
                const k = row.querySelector('.opt-key');
                if (k) { k.className = 'opt-key w-7 h-7 rounded-full text-xs font-black flex items-center justify-center shrink-0 border bg-emerald-500 text-white border-emerald-500'; }
            } else if (optKey === selectedKey) {
                row.classList.remove('border-neutral-200', 'bg-white', 'border-[#FF5500]', 'bg-orange-50/50');
                row.classList.add('border-red-400', 'bg-red-50');
                const k = row.querySelector('.opt-key');
                if (k) { k.className = 'opt-key w-7 h-7 rounded-full text-xs font-black flex items-center justify-center shrink-0 border bg-red-500 text-white border-red-500'; }
            }
            row.style.pointerEvents = 'none';
            row.style.cursor = 'default';
        });

        checkAnswerBtn.disabled = true;
        checkAnswerBtn.className = 'w-full sm:w-auto px-6 py-3 rounded-full bg-neutral-400 text-white text-xs font-black uppercase tracking-wider opacity-40 cursor-not-allowed';

        renderLucideIcons();
        renderTabs();
        updatePackageScoreCard();

        // Auto-advance to next unanswered question after a brief delay
        const questions = currentPackage.questions || [];
        const nextUnanswered = questions.findIndex((_, i) => i > currentQuestionIndex && studentAnswers[i] === undefined);
        if (nextUnanswered !== -1) {
            setTimeout(() => switchQuestion(nextUnanswered), 1800);
        } else {
            // All answered or no more after current — check if complete
            setTimeout(() => checkIfPackageComplete(), 1800);
        }
    });

    // ─────────────── ACCORDIONS ───────────────

    hintToggleBtn.addEventListener('click', function () {
        const isHidden = hintContent.classList.contains('hidden');
        hintContent.classList.toggle('hidden', !isHidden);
        hintChevron.textContent = isHidden ? '↑' : '↓';
    });

    explanationToggleBtn.addEventListener('click', function () {
        const isHidden = explanationContent.classList.contains('hidden');
        explanationContent.classList.toggle('hidden', !isHidden);
        explanationChevron.textContent = isHidden ? '↑' : '↓';
    });

    // ─────────────── QUICK CHIPS ───────────────

    document.querySelectorAll('.quick-chip').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('contextInput').value = this.dataset.topic;
        });
    });

    // ─────────────── COUNT RANGE ───────────────

    countRange.addEventListener('input', function () {
        countDisplay.textContent = this.value;
    });

    // ─────────────── DOMAIN CARDS ───────────────

    document.querySelectorAll('.domain-card').forEach(card => {
        card.addEventListener('click', function () {
            document.querySelectorAll('.domain-card').forEach(c => {
                c.classList.remove('border-[#FF5500]', 'bg-orange-50/60', 'shadow-sm');
                c.classList.add('border-neutral-200', 'bg-white');
            });
            this.classList.remove('border-neutral-200', 'bg-white');
            this.classList.add('border-[#FF5500]', 'bg-orange-50/60', 'shadow-sm');
        });
    });

    // ─────────────── DIFFICULTY CARDS ───────────────

    document.querySelectorAll('.difficulty-card').forEach(card => {
        card.addEventListener('click', function () {
            document.querySelectorAll('.difficulty-card').forEach(c => {
                c.classList.remove('border-[#FF5500]', 'bg-[#FF5500]', 'text-white', 'shadow-sm');
                c.classList.add('border-neutral-200', 'bg-white', 'text-black');
                const sub = c.querySelector('div:last-child');
                if (sub) { sub.classList.remove('text-white/80'); sub.classList.add('text-neutral-500'); }
            });
            this.classList.remove('border-neutral-200', 'bg-white', 'text-black');
            this.classList.add('border-[#FF5500]', 'bg-[#FF5500]', 'text-white', 'shadow-sm');
            const sub = this.querySelector('div:last-child');
            if (sub) { sub.classList.remove('text-neutral-500'); sub.classList.add('text-white/80'); }
        });
    });

    // ─────────────── ENGINE SELECTOR ───────────────

    document.querySelectorAll('.engine-card').forEach(card => {
        card.addEventListener('click', function () {
            document.querySelectorAll('.engine-card').forEach(c => {
                c.classList.remove('border-[#FF5500]', 'bg-orange-50/60', 'shadow-xs');
                c.classList.add('border-neutral-200', 'bg-white');
                const radio = c.querySelector('input[type="radio"]');
                if (radio) radio.checked = false;
            });
            this.classList.remove('border-neutral-200', 'bg-white');
            this.classList.add('border-[#FF5500]', 'bg-orange-50/60', 'shadow-xs');
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                if (radio.value === 'fast') {
                    btnText.textContent = 'Muat Soal dari Bank Kurikulum';
                } else {
                    btnText.textContent = 'Generate Soal dengan AI Agent';
                }
            }
        });
    });

    // ─────────────── REGENERATE BUTTON ───────────────

    regenerateBtn.addEventListener('click', function () {
        packageCompleteCard.classList.add('hidden');
        questionDisplayCard.classList.remove('hidden');
        form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
    });

    // ─────────────── SIMPLE & ROBUST GENERATOR SUBMIT (AI AGENT) ───────────────

    let isGenerating = false;
    const aiInlineLoading = document.getElementById('aiInlineLoading');
    const generatorNotification = document.getElementById('generatorNotification');

    function showNotification(message, type = 'success') {
        if (!generatorNotification) return;
        generatorNotification.className = `p-3.5 rounded-2xl text-xs font-bold transition-all duration-300 flex items-center justify-between gap-3 shadow-sm ${
            type === 'success' ? 'bg-emerald-50 text-emerald-900 border border-emerald-300' : 'bg-red-50 text-red-900 border border-red-300'
        }`;
        generatorNotification.innerHTML = `
            <div class="flex items-center gap-2">
                <span class="text-sm">${type === 'success' ? '✓' : '⚠️'}</span>
                <span>${message}</span>
            </div>
            <button type="button" onclick="this.parentElement.classList.add('hidden')" class="text-sm font-black cursor-pointer opacity-60 hover:opacity-100">&times;</button>
        `;
        generatorNotification.classList.remove('hidden');
        setTimeout(() => {
            if (generatorNotification) generatorNotification.classList.add('hidden');
        }, 6000);
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (isGenerating) return;

        const selectedCount = parseInt(countRange.value, 10);
        const engineInput = document.querySelector('input[name="engine"]:checked');
        const selectedEngine = engineInput ? engineInput.value : 'ai';
        const isAi = selectedEngine === 'ai';

        isGenerating = true;
        generateBtn.disabled = true;
        btnText.textContent = isAi ? `AI Agent Sedang Merancang Soal...` : `Memuat ${selectedCount} Soal...`;
        btnIcon.innerHTML = '<svg class="w-4 h-4 animate-spin text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        // Tampilkan state loading inline yang bersih pada panel soal
        if (questionDisplayCard) questionDisplayCard.classList.add('hidden');
        if (packageCompleteCard) packageCompleteCard.classList.add('hidden');
        if (aiInlineLoading) {
            aiInlineLoading.classList.remove('hidden');
            const inlineTitle = document.getElementById('inlineLoadingTitle');
            const inlineDesc = document.getElementById('inlineLoadingDesc');
            if (inlineTitle) inlineTitle.textContent = isAi ? `AI Agent Sedang Merancang ${selectedCount} Soal...` : `Memuat ${selectedCount} Soal...`;
            if (inlineDesc) inlineDesc.textContent = isAi 
                ? 'AI Agent sedang menyusun stimulus narasi PISA/AKM, perumusan penalaran kritis, dan opsi jawaban...'
                : 'Menyiapkan paket butir soal kurikulum adaptif...';
            if (window.innerWidth < 1024) {
                aiInlineLoading.scrollIntoView({ behavior: 'smooth' });
            }
        }

        const formData = new FormData(form);
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 25000);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                signal: controller.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
            });

            clearTimeout(timeoutId);

            if (!response.ok) {
                const errJson = await response.json().catch(() => null);
                throw new Error(errJson?.message || ('Server error ' + response.status));
            }

            const resData = await response.json();
            if (resData && resData.package && resData.package.questions && resData.package.questions.length > 0) {
                if (aiInlineLoading) aiInlineLoading.classList.add('hidden');
                loadPackage(resData.package);
                const modelUsed = resData.package.ai_model || 'AI Agent';
                showNotification(`Paket ${resData.package.questions.length} butir soal siap digunakan! (${modelUsed})`, 'success');
                if (window.innerWidth < 1024) {
                    document.getElementById('packageInfoBar')?.scrollIntoView({ behavior: 'smooth' });
                }
            } else {
                throw new Error('Format data paket soal tidak valid.');
            }
        } catch (err) {
            clearTimeout(timeoutId);
            console.error('Generator error:', err);
            if (aiInlineLoading) aiInlineLoading.classList.add('hidden');
            if (questionDisplayCard) questionDisplayCard.classList.remove('hidden');
            const msg = err.name === 'AbortError' 
                ? 'Waktu proses melebihi batas. Silakan coba kembali.' 
                : (err.message || 'Koneksi ke generator bermasalah.');
            showNotification(msg, 'error');
        } finally {
            clearTimeout(timeoutId);
            isGenerating = false;
            generateBtn.disabled = false;
            const currentEngineInput = document.querySelector('input[name="engine"]:checked');
            btnText.textContent = (currentEngineInput && currentEngineInput.value === 'fast')
                ? 'Muat Soal dari Bank Kurikulum'
                : 'Generate Soal dengan AI Agent';
            btnIcon.innerHTML = '<svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3Z"/></svg>';
            renderLucideIcons();
        }
    });

    // ─────────────── INITIAL LOAD ───────────────
    loadPackage(currentPackage);
});
</script>
@endpush
@endsection
