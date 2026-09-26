@extends('layouts.app')

@section('title', 'Nalaria — Asesmen Numerasi Adaptif Berbasis AI')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">

    <!-- HERO SECTION (Inspired by SOSH Hero Banner) -->
    <div class="hero-banner text-white p-8 sm:p-12 lg:p-16 shadow-xl shadow-purple-950/20">
        <div class="hero-pattern"></div>
        <div class="relative z-10 max-w-3xl space-y-6">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold text-purple-200">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                <span>Hackathon SEMESTA 8 • Solusi Krisis Literasi-Numerasi Indonesia</span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight text-white">
                Penalaran numerasi kontekstual untuk masa depan berkelanjutan.
            </h1>

            <p class="text-base sm:text-lg text-purple-100 font-normal leading-relaxed">
                Hasil Asesmen Nasional & PISA membuktikan kelemahan terbesar siswa bukan pada hafalan rumus, melainkan penerapan konsep ke situasi nyata. <strong>Nalaria AI Agent</strong> mendiagnosis akar miskonsepsi kognitif dan menghasilkan latihan adaptif personal.
            </p>

            <!-- Dual Keypoints -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-white/15 text-sm text-purple-200">
                <div class="flex items-start gap-2.5">
                    <svg class="w-5 h-5 text-amber-300 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path>
                    </svg>
                    <span><strong>1 dari 2 Siswa</strong> belum mencapai kompetensi minimum numerasi (Kemendikdasmen).</span>
                </div>
                <div class="flex items-start gap-2.5">
                    <svg class="w-5 h-5 text-emerald-300 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span><strong>AI Agent 2-Aksi:</strong> Diagnosis Miskonsepsi + Latihan Scaffolding Terarah.</span>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="pt-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <a href="{{ route('diagnostic.quiz') }}" class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl bg-white text-purple-900 font-bold text-base hover:bg-purple-50 transition-all shadow-lg shadow-black/10 active:scale-95 group">
                    <span>Mulai Asesmen Diagnostik</span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-purple-100 text-purple-800 font-semibold">5 Soal • ~5 Menit</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                    </svg>
                </a>
                <span class="text-xs text-purple-300 text-center sm:text-left">
                    Tanpa biaya • Langsung dapat diagnosis kognitif & paket belajar
                </span>
            </div>
        </div>
    </div>

    <!-- BENTO GRID SECTION (SOSH Bento Style) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs uppercase font-extrabold tracking-widest text-purple-700">Metodologi Asesmen</span>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">Bagaimana Nalaria Membantu Siswa</h2>
            </div>
            <div class="text-sm text-slate-500 hidden sm:block">
                Standar Penalaran PISA & AKM Kemendikdasmen
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Bento Card 1: Pengantar Masalah -->
            <div class="bento-card p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold">
                        01
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Konteks Nyata Keberlanjutan</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Soal tidak lagi berupa hafalan x dan y yang abstrak. Siswa memecahkan masalah efisiensi panel surya, diskon pengelolaan daur ulang, perbesaran kebun toga, dan statistik sampah sekolah.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center gap-2 text-xs font-semibold text-slate-500">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>4 Domain: Aljabar, Geometri, Data & Aritmatika</span>
                </div>
            </div>

            <!-- Bento Card 2: AI Action 1 -->
            <div class="bento-card p-6 flex flex-col justify-between space-y-4 border-purple-200 bg-purple-50/30">
                <div class="space-y-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-700 text-white flex items-center justify-center font-bold shadow-md shadow-purple-700/20">
                        02
                    </div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-bold text-slate-900">AI Aksi 1: Diagnosis Miskonsepsi</h3>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-200 text-purple-900">Kognitif</span>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Bukan sekadar mencatat salah atau benar. AI mengevaluasi pilihan opsi beserta uraian alasan berpikir siswa untuk mengidentifikasi jebakan logika seperti <em>additive percentage error</em> atau <em>unweighted mean trap</em>.
                    </p>
                </div>
                <div class="pt-3 border-t border-purple-100 flex items-center gap-2 text-xs font-semibold text-purple-700">
                    <span>Memetakan Akar Kelemahan Siswa</span>
                </div>
            </div>

            <!-- Bento Card 3: AI Action 2 -->
            <div class="bento-card p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                        03
                    </div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-bold text-slate-900">AI Aksi 2: Latihan & Scaffolding</h3>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700">Adaptif</span>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Secara otomatis menghasilkan paket latihan bertarget yang disesuaikan persis dengan miskonsepsi siswa, dilengkapi petunjuk langkah bernalar (<em>scaffolding hints</em>) tanpa membocorkan jawaban langsung.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center gap-2 text-xs font-semibold text-slate-500">
                    <span>Disimpan ke Rencana Belajar Siswa</span>
                </div>
            </div>
        </div>
    </div>

    <!-- CALLOUT CARD (SOSH Classrooms Style) -->
    <div class="bento-card p-8 sm:p-10 bg-gradient-to-r from-slate-900 to-slate-800 text-white flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 max-w-xl">
            <span class="text-xs uppercase font-extrabold tracking-widest text-purple-400">Siap Menguji Penalaranmu?</span>
            <h3 class="text-2xl font-bold tracking-tight text-white">Ikuti 5 Soal Diagnostik Numerasi Sekarang</h3>
            <p class="text-sm text-slate-300 leading-relaxed">
                Cukup luangkan waktu 5 menit. Dapatkan gambaran objektif tentang bagaimana kamu bernalar dan temukan cara memperbaikinya sebelum ujian sekolah maupun asesmen nasional.
            </p>
        </div>
        <a href="{{ route('diagnostic.quiz') }}" class="px-7 py-3.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm shadow-lg shadow-purple-600/30 transition-all hover:scale-105 active:scale-95 whitespace-nowrap">
            Mulai Sekarang →
        </a>
    </div>

</div>
@endsection
