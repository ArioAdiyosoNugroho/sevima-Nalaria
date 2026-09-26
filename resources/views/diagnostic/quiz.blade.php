@extends('layouts.app')

@section('title', 'Kuis Diagnostik Numerasi Adaptif — Nalaria')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">

    <!-- Form Container -->
    <form id="diagnosticForm" action="{{ route('diagnostic.submit') }}" method="POST" class="space-y-8">
        @csrf
        <input type="hidden" name="time_spent" id="timeSpentInput" value="0">

        <!-- Header Information Card (Meekoo Style) -->
        <div class="meekoo-card p-8 sm:p-10 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-neutral-100 pb-6">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#F4F4F6] text-[11px] font-bold text-neutral-800 uppercase tracking-wider">
                        <span>Asesmen Diagnostik Adaptif</span>
                    </div>
                    <h1 class="meekoo-heading text-3xl font-extrabold text-black">
                        Uji Penalaran Numerasi Kontekstual
                    </h1>
                    <p class="text-xs sm:text-sm text-neutral-500 font-normal">
                        Pilihlah opsi jawaban terbaik dan sertakan alasan cara berpikirmu untuk dianalisis oleh AI Agent.
                    </p>
                </div>

                <!-- Timer Badge in Meekoo Black Pill Style -->
                <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-black text-white text-xs font-bold self-start sm:self-auto shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-[#FF5500] animate-ping"></span>
                    <span>Waktu: <span id="timerDisplay" class="font-mono text-[#FF5500] ml-1">00:00</span></span>
                </div>
            </div>

            <!-- Student Profile Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="student_name" class="block text-xs font-bold text-black uppercase tracking-wider mb-2">
                        Nama Lengkap Siswa <span class="text-[#FF5500]">*</span>
                    </label>
                    <input type="text" name="student_name" id="student_name" required placeholder="Contoh: Ario Adiyoso Nugroho"
                        class="w-full px-4 py-3 rounded-2xl border border-neutral-300 focus:outline-none focus:border-[#FF5500] focus:ring-1 focus:ring-[#FF5500] text-sm font-semibold transition-all bg-white"
                        value="{{ old('student_name', 'Siswa Semesta') }}">
                </div>
                <div>
                    <label for="student_grade" class="block text-xs font-bold text-black uppercase tracking-wider mb-2">
                        Jenjang / Kelas <span class="text-[#FF5500]">*</span>
                    </label>
                    <select name="student_grade" id="student_grade" required
                        class="w-full px-4 py-3 rounded-2xl border border-neutral-300 focus:outline-none focus:border-[#FF5500] focus:ring-1 focus:ring-[#FF5500] text-sm font-semibold transition-all bg-white">
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
                        'aljabar' => ['label' => 'Aljabar & Fungsi', 'badge' => 'bg-black text-white'],
                        'geometri' => ['label' => 'Geometri & Skala', 'badge' => 'bg-black text-white'],
                        'aritmatika_sosial' => ['label' => 'Aritmatika Sosial', 'badge' => 'bg-[#FF5500] text-white'],
                        'data_ketidakpastian' => ['label' => 'Data & Ketidakpastian', 'badge' => 'bg-[#FF5500] text-white'],
                    ];
                    $domainInfo = $domainLabels[$q->domain] ?? ['label' => ucfirst($q->domain), 'badge' => 'bg-neutral-800 text-white'];
                @endphp

                <div class="meekoo-card p-6 sm:p-8 space-y-6 question-card" data-question-id="{{ $q->id }}">
                    <!-- Card Top Header -->
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-black text-white font-black text-xs flex items-center justify-center">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-xs font-bold text-neutral-500 uppercase tracking-wider">
                                Soal {{ $index + 1 }} dari {{ count($questions) }}
                            </span>
                        </div>
                        <span class="text-[11px] font-bold px-3 py-1 rounded-full {{ $domainInfo['badge'] }}">
                            {{ $domainInfo['label'] }}
                        </span>
                    </div>

                    <!-- Contextual Scenario Box in Soft Neutral Surface -->
                    <div class="p-5 rounded-2xl bg-[#F6F6F8] border border-neutral-200 text-sm text-neutral-800 space-y-2 leading-relaxed">
                        <div class="flex items-center gap-2 text-xs font-black text-black uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-[#FF5500]"></span>
                            <span>Konteks Studi Kasus: {{ $q->title }}</span>
                        </div>
                        <p class="font-normal">{{ $q->context_scenario }}</p>
                    </div>

                    <!-- Question Text -->
                    <div class="text-base font-extrabold text-black">
                        {{ $q->question_text }}
                    </div>

                    <!-- Options Grid -->
                    <div class="space-y-3 pt-1">
                        @foreach($q->options as $opt)
                            <label class="option-label flex items-start gap-3.5 p-4 rounded-2xl border border-neutral-200 hover:border-black cursor-pointer transition-all bg-white">
                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt['key'] }}" required
                                    class="mt-1 w-4 h-4 text-[#FF5500] focus:ring-[#FF5500] border-neutral-400">
                                <div class="text-sm text-neutral-800 leading-snug">
                                    <span class="font-black text-black mr-2">{{ $opt['key'] }}.</span>
                                    <span>{{ $opt['text'] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- Student Reasoning Field (Crucial for AI Agent Action 1) -->
                    <div class="pt-2">
                        <label for="reasoning_{{ $q->id }}" class="block text-xs font-bold text-black uppercase tracking-wider mb-2">
                            Alasan / Cara Berpikir Kamu (Diperlukan AI Agent untuk Mendiagnosis Miskonsepsi):
                        </label>
                        <textarea name="reasoning[{{ $q->id }}]" id="reasoning_{{ $q->id }}" rows="2"
                            placeholder="Tuliskan mengapa kamu memilih opsi di atas atau bagaimana caramu menghitungnya..."
                            class="w-full px-4 py-3 rounded-2xl border border-neutral-200 focus:outline-none focus:border-[#FF5500] focus:ring-1 focus:ring-[#FF5500] text-sm font-normal transition-all"></textarea>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sticky Floating Bottom Submit Bar (Meekoo Pill + Arrow Circle) -->
        <div class="sticky bottom-6 z-30 p-5 rounded-3xl bg-black text-white shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-4 border border-neutral-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#FF5500] text-white flex items-center justify-center font-black text-sm">
                    <span id="answeredCount">0</span>/{{ count($questions) }}
                </div>
                <div>
                    <div class="text-xs font-black text-white uppercase tracking-wider">Progres Pengerjaan</div>
                    <div class="text-[11px] text-neutral-400">Pastikan semua soal terisi sebelum submit</div>
                </div>
            </div>

            <button type="submit" id="submitBtn" class="group inline-flex items-center self-stretch sm:self-auto">
                <span class="px-8 py-3.5 rounded-full bg-[#FF5500] group-hover:bg-white group-hover:text-black text-white text-sm font-extrabold uppercase tracking-wider transition-all shadow-md">
                    Submit & Analisis AI
                </span>
                <span class="w-12 h-12 -ml-2 rounded-full bg-[#FF5500] group-hover:bg-white group-hover:text-black text-white flex items-center justify-center transition-all border-2 border-black">
                    <svg class="w-4 h-4 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                    </svg>
                </span>
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
                label.classList.add('border-[#FF5500]', 'bg-orange-50/40', 'ring-1', 'ring-[#FF5500]');
            } else {
                label.classList.remove('border-[#FF5500]', 'bg-orange-50/40', 'ring-1', 'ring-[#FF5500]');
            }
        });
    }

    radioInputs.forEach(input => {
        input.addEventListener('change', updateAnswerCount);
    });
</script>
@endpush
@endsection
