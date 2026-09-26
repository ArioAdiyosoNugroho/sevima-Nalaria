@extends('layouts.app')

@section('title', 'Kuis Diagnostik Numerasi Adaptif — Nalaria')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">

    <!-- Form Container -->
    <form id="diagnosticForm" action="{{ route('diagnostic.submit') }}" method="POST" class="space-y-8">
        @csrf
        <input type="hidden" name="time_spent" id="timeSpentInput" value="0">

        <!-- Header Information Card -->
        <div class="bento-card p-6 sm:p-8 space-y-6 border-purple-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div>
                    <span class="text-xs uppercase font-extrabold tracking-widest text-purple-700">Asesmen Diagnostik</span>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Uji Penalaran Numerasi Kontekstual</h1>
                    <p class="text-xs text-slate-500 mt-1">Pilihlah jawaban terbaik dan tuliskan alasan singkat bagaimana caramu berpikir.</p>
                </div>
                <!-- Timer Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-700 self-start sm:self-auto">
                    <svg class="w-4 h-4 text-purple-600 animate-spin" style="animation-duration: 4s;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle>
                        <path stroke-linecap="round" d="M12 6v6l4 2"></path>
                    </svg>
                    <span>Waktu: <span id="timerDisplay" class="font-mono text-purple-700">00:00</span></span>
                </div>
            </div>

            <!-- Student Profile Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="student_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Lengkap Siswa <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="student_name" id="student_name" required placeholder="Contoh: Ario Adiyoso"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent text-sm font-medium transition-all"
                        value="{{ old('student_name', 'Siswa Semesta') }}">
                </div>
                <div>
                    <label for="student_grade" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jenjang / Kelas <span class="text-rose-500">*</span>
                    </label>
                    <select name="student_grade" id="student_grade" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent text-sm font-medium transition-all bg-white">
                        <option value="Kelas 8 SMP">Kelas 8 SMP</option>
                        <option value="Kelas 9 SMP">Kelas 9 SMP</option>
                        <option value="Kelas 10 SMA">Kelas 10 SMA</option>
                        <option value="Kelas 10 SMK (RPL / Vokasi)" selected>Kelas 10 SMK (RPL / Vokasi)</option>
                        <option value="Kelas 11 SMA/SMK">Kelas 11 SMA/SMK</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Questions List -->
        <div class="space-y-6">
            @foreach($questions as $index => $q)
                @php
                    $domainLabels = [
                        'aljabar' => ['label' => 'Aljabar & Fungsi', 'color' => 'bg-blue-50 text-blue-700 border-blue-200'],
                        'geometri' => ['label' => 'Geometri & Spasial', 'color' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                        'aritmatika_sosial' => ['label' => 'Aritmatika Sosial', 'color' => 'bg-amber-50 text-amber-700 border-amber-200'],
                        'data_ketidakpastian' => ['label' => 'Data & Ketidakpastian', 'color' => 'bg-purple-50 text-purple-700 border-purple-200'],
                    ];
                    $domainInfo = $domainLabels[$q->domain] ?? ['label' => ucfirst($q->domain), 'color' => 'bg-slate-50 text-slate-700 border-slate-200'];
                @endphp

                <div class="bento-card p-6 sm:p-8 space-y-5 question-card" data-question-id="{{ $q->id }}">
                    <!-- Card Top Header -->
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-xs font-semibold text-slate-500">Soal {{ $index + 1 }} dari {{ count($questions) }}</span>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-md border {{ $domainInfo['color'] }}">
                            {{ $domainInfo['label'] }}
                        </span>
                    </div>

                    <!-- Contextual Scenario Box -->
                    <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200/80 text-sm text-slate-700 space-y-2 leading-relaxed">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253"></path>
                            </svg>
                            <span>Konteks Studi Kasus: {{ $q->title }}</span>
                        </div>
                        <p>{{ $q->context_scenario }}</p>
                    </div>

                    <!-- Question Text -->
                    <div class="text-base font-bold text-slate-900">
                        {{ $q->question_text }}
                    </div>

                    <!-- Options Grid -->
                    <div class="space-y-2.5 pt-1">
                        @foreach($q->options as $opt)
                            <label class="option-label flex items-start gap-3 p-3.5 rounded-xl border border-slate-200 hover:border-purple-300 hover:bg-purple-50/20 cursor-pointer transition-all">
                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt['key'] }}" required
                                    class="mt-1 w-4 h-4 text-purple-600 focus:ring-purple-500 border-slate-300">
                                <div class="text-sm text-slate-800">
                                    <span class="font-bold text-purple-700 mr-1.5">{{ $opt['key'] }}.</span>
                                    <span>{{ $opt['text'] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- Student Reasoning Field (Crucial for AI Agent Action 1) -->
                    <div class="pt-2">
                        <label for="reasoning_{{ $q->id }}" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                            Alasan / Cara Berpikir Kamu (Wajib untuk Analisis Kognitif AI):
                        </label>
                        <textarea name="reasoning[{{ $q->id }}]" id="reasoning_{{ $q->id }}" rows="2"
                            placeholder="Ceritakan mengapa kamu memilih opsi di atas atau bagaimana caramu menghitungnya..."
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent text-sm transition-all"></textarea>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sticky Floating Bottom Submit Bar -->
        <div class="sticky bottom-4 z-30 p-4 rounded-2xl bg-white/95 backdrop-blur-md border border-slate-200 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm">
                    <span id="answeredCount">0</span>/{{ count($questions) }}
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-800">Progres Pengisian</div>
                    <div class="text-[11px] text-slate-500">Pastikan seluruh soal terjawab sebelum submit</div>
                </div>
            </div>

            <button type="submit" id="submitBtn"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl bg-purple-700 hover:bg-purple-800 text-white font-bold text-sm shadow-md shadow-purple-700/30 transition-all active:scale-95">
                <span>Submit & Analisis AI</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                </svg>
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
    // Timer Tracking
    let secondsElapsed = 0;
    const timerDisplay = document.getElementById('timerDisplay');
    const timeSpentInput = document.getElementById('timeSpentInput');

    setInterval(() => {
        secondsElapsed++;
        timeSpentInput.value = secondsElapsed;

        const minutes = Math.floor(secondsElapsed / 60);
        const secs = secondsElapsed % 60;
        timerDisplay.textContent = 
            (minutes < 10 ? '0' : '') + minutes + ':' + 
            (secs < 10 ? '0' : '') + secs;
    }, 1000);

    // Answer Counter & Visual Highlighting
    const totalQuestions = {{ count($questions) }};
    const radioInputs = document.querySelectorAll('input[type="radio"]');
    const answeredCountEl = document.getElementById('answeredCount');

    function updateAnswerCount() {
        const checkedRadios = document.querySelectorAll('input[type="radio"]:checked');
        answeredCountEl.textContent = checkedRadios.length;

        // Visual highlight for selected parent label
        document.querySelectorAll('.option-label').forEach(label => {
            const input = label.querySelector('input[type="radio"]');
            if (input && input.checked) {
                label.classList.add('border-purple-600', 'bg-purple-50/60', 'ring-1', 'ring-purple-600');
            } else {
                label.classList.remove('border-purple-600', 'bg-purple-50/60', 'ring-1', 'ring-purple-600');
            }
        });
    }

    radioInputs.forEach(input => {
        input.addEventListener('change', updateAnswerCount);
    });
</script>
@endpush
@endsection
