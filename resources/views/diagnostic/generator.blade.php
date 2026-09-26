@extends('layouts.app')

@section('title', 'Generator Soal Numerasi Adaptif AI — Nalaria')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    <!-- Page Header Hero Card -->
    <div class="meekoo-card p-6 sm:p-10 bg-gradient-to-br from-white via-neutral-50 to-[#FFF6F0] border border-orange-200/60 shadow-sm relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-[#FF5500]/5 blur-3xl pointer-events-none"></div>
        <div class="max-w-3xl space-y-3 relative z-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#0A0A0A] text-white text-[11px] font-black uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-[#FF5500] animate-pulse"></span>
                <span>AI Agent Aksi 2 • On-Demand Adaptive Generator</span>
            </div>
            <h1 class="meekoo-heading text-3xl sm:text-4xl lg:text-5xl font-extrabold text-black tracking-tight">
                Generator Soal Numerasi Adaptif
            </h1>
            <p class="text-xs sm:text-sm text-neutral-600 leading-relaxed font-normal">
                Hasilkan butir soal numerasi berstandar PISA dengan skenario kontekstual keberlanjutan masa depan secara instan. Ditenagai oleh <strong>NVIDIA Nemotron LLM</strong> via OpenRouter & algoritma kesulitan bertingkat adaptif.
            </p>
        </div>
    </div>

    <!-- Main Generator Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left Column: Generator Controls (4 cols on large screen) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="meekoo-card p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between border-b border-neutral-100 pb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center font-black text-sm">
                            ⚙️
                        </span>
                        <h2 class="text-base font-extrabold text-black">
                            Konfigurasi Soal AI
                        </h2>
                    </div>
                    <span class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider">Real-time</span>
                </div>

                <form id="generatorForm" action="{{ route('diagnostic.generator.generate') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- 1. Domain Numerasi Selection -->
                    <div class="space-y-2.5">
                        <label class="block text-xs font-bold text-black uppercase tracking-wider">
                            Pilih Domain Numerasi <span class="text-[#FF5500]">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5" id="domainSelector">
                            @php
                                $domains = [
                                    ['id' => 'aritmatika_sosial', 'icon' => '🏷️', 'title' => 'Aritmatika Sosial', 'desc' => 'Diskon & Finansial'],
                                    ['id' => 'aljabar', 'icon' => '📐', 'title' => 'Aljabar', 'desc' => 'Pemodelan Linier'],
                                    ['id' => 'geometri', 'icon' => '🗺️', 'title' => 'Geometri Spasial', 'desc' => 'Skala, Luas & Volume'],
                                    ['id' => 'data_ketidakpastian', 'icon' => '📊', 'title' => 'Data & Statistik', 'desc' => 'Rerata & Peluang'],
                                ];
                            @endphp

                            @foreach($domains as $d)
                                <label class="domain-card flex flex-col p-3.5 rounded-2xl border-2 cursor-pointer transition-all duration-200 {{ ($selectedDomain ?? 'aritmatika_sosial') === $d['id'] ? 'border-[#FF5500] bg-orange-50/60 shadow-sm' : 'border-neutral-200 bg-white hover:border-neutral-300' }}">
                                    <input type="radio" name="domain" value="{{ $d['id'] }}" class="sr-only" {{ ($selectedDomain ?? 'aritmatika_sosial') === $d['id'] ? 'checked' : '' }}>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-base">{{ $d['icon'] }}</span>
                                        <span class="text-xs font-black text-black leading-tight">{{ $d['title'] }}</span>
                                    </div>
                                    <span class="text-[10px] text-neutral-500 font-medium">{{ $d['desc'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- 2. Tingkat Kesulitan Adaptif -->
                    <div class="space-y-2.5">
                        <label class="block text-xs font-bold text-black uppercase tracking-wider">
                            Tingkat Kesulitan Adaptif <span class="text-[#FF5500]">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-2" id="difficultySelector">
                            @php
                                $difficulties = [
                                    ['level' => 'Mudah', 'pisa' => 'Level 2 PISA', 'desc' => 'Konsep Dasar'],
                                    ['level' => 'Sedang', 'pisa' => 'Level 3 PISA', 'desc' => 'Aplikasi 2 Langkah'],
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

                    <!-- 3. Topik / Konteks Keberlanjutan -->
                    <div class="space-y-2">
                        <label for="contextInput" class="block text-xs font-bold text-black uppercase tracking-wider">
                            Konteks / Skenario Masalah (Opsional)
                        </label>
                        <input type="text" name="context" id="contextInput" value="{{ $selectedContext ?? '' }}" placeholder="Contoh: Efisiensi Panel Surya, Daur Ulang Plastik"
                            class="w-full px-4 py-3 rounded-2xl border border-neutral-300 focus:outline-none focus:border-[#FF5500] focus:ring-1 focus:ring-[#FF5500] text-xs font-semibold transition-all bg-white">
                        
                        <!-- Quick Context Chips -->
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            <button type="button" class="quick-chip text-[11px] font-bold px-3 py-1 rounded-full bg-neutral-100 hover:bg-neutral-200 text-neutral-700 transition-colors" data-topic="Efisiensi Panel Surya Sekolah">
                                ☀️ Panel Surya
                            </button>
                            <button type="button" class="quick-chip text-[11px] font-bold px-3 py-1 rounded-full bg-neutral-100 hover:bg-neutral-200 text-neutral-700 transition-colors" data-topic="Bank Sampah & Daur Ulang">
                                ♻️ Daur Ulang
                            </button>
                            <button type="button" class="quick-chip text-[11px] font-bold px-3 py-1 rounded-full bg-neutral-100 hover:bg-neutral-200 text-neutral-700 transition-colors" data-topic="Audit Debit Air Bersih">
                                💧 Konservasi Air
                            </button>
                            <button type="button" class="quick-chip text-[11px] font-bold px-3 py-1 rounded-full bg-neutral-100 hover:bg-neutral-200 text-neutral-700 transition-colors" data-topic="Kebun Hidroponik Komunitas">
                                🌱 Hidroponik
                            </button>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button type="submit" id="generateBtn" class="w-full py-4 rounded-full bg-[#FF5500] hover:bg-[#E04B00] text-white text-xs font-black tracking-wide uppercase transition-all duration-200 shadow-lg shadow-orange-500/20 active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                        <span id="btnIcon" class="text-sm">⚡</span>
                        <span id="btnText">Generate Soal AI Sekarang</span>
                    </button>
                </form>
            </div>

            <!-- Full Assessment Promotion Card -->
            <div class="meekoo-card p-6 bg-black text-white space-y-4">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#FF5500]"></span>
                    <h3 class="text-xs font-black uppercase tracking-wider text-neutral-300">Asesmen AI Lengkap</h3>
                </div>
                <p class="text-xs text-neutral-400 leading-relaxed">
                    Ingin diagnosa miskonsepsi kognitif secara otomatis dan rapor hasil personal? Ikuti tes 5 soal diagnostik terstandar kami.
                </p>
                <a href="{{ route('diagnostic.quiz') }}" class="w-full py-2.5 rounded-full bg-white hover:bg-[#FF5500] text-black hover:text-white text-xs font-extrabold text-center transition-all duration-200 flex items-center justify-center gap-2">
                    <span>Mulai Asesmen Diagnostik (5 Soal)</span>
                    <span>→</span>
                </a>
            </div>
        </div>

        <!-- Right Column: Interactive Generated Question Display (7 cols on large screen) -->
        <div class="lg:col-span-7 space-y-6">

            <!-- Question Card Container -->
            <div id="questionDisplayCard" class="meekoo-card p-6 sm:p-9 space-y-6 bg-white border border-neutral-200/90 shadow-sm transition-all duration-300">
                
                <!-- Card Header with Badges & Meta -->
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-5">
                    <div class="flex flex-wrap items-center gap-2">
                        <span id="qDomainBadge" class="px-3 py-1 rounded-full bg-[#FF5500] text-white text-[11px] font-black uppercase tracking-wider shadow-xs">
                            {{ $initialQuestion['domain_label'] ?? 'Aritmatika Sosial' }}
                        </span>
                        <span id="qDifficultyBadge" class="px-3 py-1 rounded-full bg-black text-white text-[11px] font-black uppercase tracking-wider">
                            Level: {{ $initialQuestion['difficulty'] ?? 'Sedang' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2 text-[11px] font-mono text-neutral-500">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span id="qAiModel">Model: {{ $initialQuestion['ai_model'] ?? 'NVIDIA Nemotron-3' }}</span>
                    </div>
                </div>

                <!-- Title & Context Scenario -->
                <div class="space-y-4">
                    <h3 id="qTitle" class="meekoo-heading text-xl sm:text-2xl font-black text-black">
                        {{ $initialQuestion['title'] ?? 'Latihan Numerasi Adaptif' }}
                    </h3>

                    <div id="qScenarioWrapper" class="p-4 sm:p-5 rounded-2xl bg-neutral-50 border border-neutral-200/80 space-y-2">
                        <div class="flex items-center gap-2 text-xs font-black text-neutral-700">
                            <span>📖</span>
                            <span>Skenario Kontekstual Berkelanjutan:</span>
                        </div>
                        <p id="qScenario" class="text-xs sm:text-sm text-neutral-700 leading-relaxed font-medium">
                            {{ $initialQuestion['context_scenario'] ?? '' }}
                        </p>
                    </div>

                    <!-- Question Prompt -->
                    <div class="pt-2">
                        <p class="text-xs font-bold uppercase tracking-wider text-neutral-400 mb-1">Pertanyaan Soal:</p>
                        <p id="qText" class="text-sm sm:text-base font-extrabold text-black leading-relaxed">
                            {{ $initialQuestion['question_text'] ?? '' }}
                        </p>
                    </div>
                </div>

                <!-- Multiple Choice Interactive Options -->
                <div class="space-y-3 pt-2">
                    <p class="text-xs font-bold uppercase tracking-wider text-black">Pilih Opsi Jawaban:</p>
                    <div id="optionsContainer" class="space-y-2.5">
                        @foreach(($initialQuestion['options'] ?? []) as $opt)
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

                <!-- Answer Checker Action & Feedback Box -->
                <div class="pt-2 space-y-4">
                    <button type="button" id="checkAnswerBtn" class="w-full sm:w-auto px-6 py-3 rounded-full bg-black hover:bg-[#FF5500] text-white text-xs font-black uppercase tracking-wider transition-colors duration-200 active:scale-95 cursor-pointer">
                        Periksa Jawaban Saya
                    </button>

                    <!-- Feedback Alert (Hidden by default) -->
                    <div id="answerFeedbackBox" class="hidden p-4 rounded-2xl border transition-all duration-300">
                        <div class="flex items-start gap-3">
                            <span id="feedbackIcon" class="text-lg"></span>
                            <div class="space-y-1">
                                <p id="feedbackTitle" class="text-xs font-black uppercase tracking-wider"></p>
                                <p id="feedbackMessage" class="text-xs sm:text-sm font-medium leading-relaxed"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scaffolding Hint (Accordion) -->
                <div class="border-t border-neutral-100 pt-5 space-y-3">
                    <button type="button" id="hintToggleBtn" class="flex items-center justify-between w-full p-3.5 rounded-2xl bg-[#FFF6F0] hover:bg-[#FFEBDC] text-[#FF5500] transition-colors cursor-pointer">
                        <div class="flex items-center gap-2.5">
                            <span class="text-base">💡</span>
                            <span class="text-xs font-extrabold uppercase tracking-wider">Butuh Bantuan? Buka Petunjuk Bernalar (Scaffolding Hint)</span>
                        </div>
                        <span id="hintChevron" class="text-sm font-bold transition-transform duration-200">↓</span>
                    </button>

                    <div id="hintContent" class="hidden p-4 sm:p-5 rounded-2xl bg-[#FFFBF8] border border-orange-200/80 text-xs sm:text-sm text-neutral-800 leading-relaxed font-medium">
                        <p id="qHint">
                            {{ $initialQuestion['scaffolding_hint'] ?? 'Analisis besaran yang diketahui dan tentukan model keterikatan variabelnya sebelum melakukan operasi hitung.' }}
                        </p>
                    </div>
                </div>

                <!-- Conceptual Explanation (Accordion) -->
                <div class="border-t border-neutral-100 pt-5 space-y-3">
                    <button type="button" id="explanationToggleBtn" class="flex items-center justify-between w-full p-3.5 rounded-2xl bg-neutral-100 hover:bg-neutral-200 text-neutral-800 transition-colors cursor-pointer">
                        <div class="flex items-center gap-2.5">
                            <span class="text-base">📚</span>
                            <span class="text-xs font-extrabold uppercase tracking-wider">Lihat Pembahasan Konsep & Kunci Jawaban</span>
                        </div>
                        <span id="explanationChevron" class="text-sm font-bold transition-transform duration-200">↓</span>
                    </button>

                    <div id="explanationContent" class="hidden p-4 sm:p-5 rounded-2xl bg-neutral-50 border border-neutral-200 text-xs sm:text-sm text-neutral-800 leading-relaxed font-medium space-y-2">
                        <div class="flex items-center gap-2 text-xs font-black text-black">
                            <span>Kunci Jawaban:</span>
                            <span id="qCorrectKey" class="px-2.5 py-0.5 rounded-full bg-black text-white font-mono font-bold">{{ $initialQuestion['correct_answer'] ?? 'B' }}</span>
                        </div>
                        <p id="qExplanation">
                            {{ $initialQuestion['conceptual_explanation'] ?? 'Lakukan perhitungan sistematis sesuai kaidah konsep materi.' }}
                        </p>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentQuestionData = @json($initialQuestion);

    const form = document.getElementById('generatorForm');
    const generateBtn = document.getElementById('generateBtn');
    const btnText = document.getElementById('btnText');
    const btnIcon = document.getElementById('btnIcon');

    // UI Elements
    const qDomainBadge = document.getElementById('qDomainBadge');
    const qDifficultyBadge = document.getElementById('qDifficultyBadge');
    const qAiModel = document.getElementById('qAiModel');
    const qTitle = document.getElementById('qTitle');
    const qScenario = document.getElementById('qScenario');
    const qText = document.getElementById('qText');
    const optionsContainer = document.getElementById('optionsContainer');
    const qHint = document.getElementById('qHint');
    const qExplanation = document.getElementById('qExplanation');
    const qCorrectKey = document.getElementById('qCorrectKey');

    const checkAnswerBtn = document.getElementById('checkAnswerBtn');
    const answerFeedbackBox = document.getElementById('answerFeedbackBox');
    const feedbackIcon = document.getElementById('feedbackIcon');
    const feedbackTitle = document.getElementById('feedbackTitle');
    const feedbackMessage = document.getElementById('feedbackMessage');

    const hintToggleBtn = document.getElementById('hintToggleBtn');
    const hintContent = document.getElementById('hintContent');
    const hintChevron = document.getElementById('hintChevron');

    const explanationToggleBtn = document.getElementById('explanationToggleBtn');
    const explanationContent = document.getElementById('explanationContent');
    const explanationChevron = document.getElementById('explanationChevron');

    // Quick chip click
    document.querySelectorAll('.quick-chip').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('contextInput').value = this.dataset.topic;
        });
    });

    // Domain selection radio styling
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

    // Difficulty selection styling
    document.querySelectorAll('.difficulty-card').forEach(card => {
        card.addEventListener('click', function () {
            document.querySelectorAll('.difficulty-card').forEach(c => {
                c.classList.remove('border-[#FF5500]', 'bg-[#FF5500]', 'text-white', 'shadow-sm');
                c.classList.add('border-neutral-200', 'bg-white', 'text-black');
                const sub = c.querySelector('div:last-child');
                if (sub) {
                    sub.classList.remove('text-white/80');
                    sub.classList.add('text-neutral-500');
                }
            });
            this.classList.remove('border-neutral-200', 'bg-white', 'text-black');
            this.classList.add('border-[#FF5500]', 'bg-[#FF5500]', 'text-white', 'shadow-sm');
            const sub = this.querySelector('div:last-child');
            if (sub) {
                sub.classList.remove('text-neutral-500');
                sub.classList.add('text-white/80');
            }
        });
    });

    // Hint toggle
    hintToggleBtn.addEventListener('click', function () {
        const isHidden = hintContent.classList.contains('hidden');
        hintContent.classList.toggle('hidden', !isHidden);
        hintChevron.textContent = isHidden ? '↑' : '↓';
    });

    // Explanation toggle
    explanationToggleBtn.addEventListener('click', function () {
        const isHidden = explanationContent.classList.contains('hidden');
        explanationContent.classList.toggle('hidden', !isHidden);
        explanationChevron.textContent = isHidden ? '↑' : '↓';
    });

    // Option row click handler setup
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

    setupOptionRowEvents();

    // Check Answer
    checkAnswerBtn.addEventListener('click', function () {
        const selectedRadio = document.querySelector('input[name="practice_answer"]:checked');
        if (!selectedRadio) {
            alert('Pilihlah salah satu opsi jawaban (A, B, C, atau D) terlebih dahulu!');
            return;
        }

        const selectedVal = selectedRadio.value.toUpperCase();
        const correctVal = (currentQuestionData.correct_answer || '').toUpperCase();
        const isCorrect = (selectedVal === correctVal);

        answerFeedbackBox.classList.remove('hidden', 'bg-emerald-50', 'border-emerald-300', 'text-emerald-900', 'bg-red-50', 'border-red-300', 'text-red-900');

        if (isCorrect) {
            answerFeedbackBox.classList.add('bg-emerald-50', 'border-emerald-300', 'text-emerald-900');
            feedbackIcon.textContent = '🎉';
            feedbackTitle.textContent = 'Jawabanmu Tepat Sekali!';
            feedbackMessage.textContent = 'Luar biasa! Penalaran matematis dan pemodelan konsep yang kamu gunakan sudah benar.';
        } else {
            answerFeedbackBox.classList.add('bg-red-50', 'border-red-300', 'text-red-900');
            feedbackIcon.textContent = '⚠️';
            feedbackTitle.textContent = 'Jawabanmu Belum Tepat';
            feedbackMessage.textContent = `Kamu memilih opsi ${selectedVal}. Kunci jawaban yang benar adalah opsi ${correctVal}. Buka bagian Pembahasan Konsep di bawah untuk meninjau langkah bernalar yang tepat.`;
        }

        // Auto open explanation
        explanationContent.classList.remove('hidden');
        explanationChevron.textContent = '↑';
    });

    // Handle AJAX Form Submit for seamless on-demand generation
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // UI Loading State
        generateBtn.disabled = true;
        btnText.textContent = 'Sedang Meng-generate Soal AI...';
        btnIcon.textContent = '⏳';

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error('Gagal menghubungi server generator');
            }

            const resData = await response.json();
            if (resData.success && resData.question) {
                const q = resData.question;
                currentQuestionData = q;

                // Update UI fields
                qDomainBadge.textContent = q.domain_label;
                qDifficultyBadge.textContent = 'Level: ' + q.difficulty;
                qAiModel.textContent = 'Model: ' + (q.ai_model || 'NVIDIA Nemotron-3');
                qTitle.textContent = q.title;
                qScenario.textContent = q.context_scenario;
                qText.textContent = q.question_text;
                qHint.textContent = q.scaffolding_hint;
                qExplanation.textContent = q.conceptual_explanation;
                qCorrectKey.textContent = q.correct_answer;

                // Re-render options
                optionsContainer.innerHTML = '';
                (q.options || []).forEach(opt => {
                    const label = document.createElement('label');
                    label.className = 'option-row flex items-start gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 border-neutral-200 hover:border-black cursor-pointer transition-all duration-200 bg-white';
                    label.innerHTML = `
                        <input type="radio" name="practice_answer" value="${opt.key}" class="sr-only">
                        <span class="opt-key w-7 h-7 rounded-full bg-neutral-100 text-black text-xs font-black flex items-center justify-center shrink-0 border border-neutral-300">
                            ${opt.key}
                        </span>
                        <span class="opt-text text-xs sm:text-sm font-semibold text-neutral-800 leading-snug pt-0.5">
                            ${opt.text}
                        </span>
                    `;
                    optionsContainer.appendChild(label);
                });

                setupOptionRowEvents();

                // Reset feedback & accordions
                answerFeedbackBox.classList.add('hidden');
                hintContent.classList.add('hidden');
                hintChevron.textContent = '↓';
                explanationContent.classList.add('hidden');
                explanationChevron.textContent = '↓';

                // Scroll smoothly to question display on mobile
                if (window.innerWidth < 1024) {
                    document.getElementById('questionDisplayCard').scrollIntoView({ behavior: 'smooth' });
                }
            }
        } catch (err) {
            console.error(err);
            // Fallback: normal submit if fetch fails
            form.submit();
        } finally {
            generateBtn.disabled = false;
            btnText.textContent = 'Generate Soal AI Sekarang';
            btnIcon.textContent = '⚡';
        }
    });
});
</script>
@endpush
@endsection
