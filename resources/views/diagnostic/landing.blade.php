@extends('layouts.app')

@section('title', 'Nalaria — Shape Your Future with the Right Knowledge | AI Numerasi Adaptif')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-16">

    <!-- HERO SECTION (Exact Meekoo Reference Layout) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center pt-4 pb-8">
        
        <!-- Left Column: Headline & CTA -->
        <div class="lg:col-span-7 space-y-7">
            <!-- Pill Tag -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#F4F4F6] text-xs font-bold text-neutral-800 tracking-wide">
                <span>#1 AI Numerasi Adaptif 2026</span>
            </div>

            <!-- Big Display Headline (Exact Meekoo Typography) -->
            <h1 class="meekoo-heading text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold text-black leading-[1.1] sm:leading-[1.08] break-words">
                Shape Your<br>
                Future with the<br>
                Right Knowledge
            </h1>

            <!-- Subtitle -->
            <p class="text-sm sm:text-base lg:text-lg text-neutral-600 max-w-xl leading-relaxed font-normal">
                Bukan sekadar hafalan rumus. <strong>Nalaria AI Agent</strong> membedah akar miskonsepsi berpikir kontekstual dan menyusun paket latihan adaptif personal untuk masa depan berkelanjutan.
            </p>

            <!-- CTA Button (Meekoo Pill + Arrow Circle) -->
            <div class="pt-2 flex flex-wrap items-center gap-3">
                <a href="{{ route('diagnostic.quiz') }}" class="group inline-flex items-center active:scale-95 transition-transform duration-200">
                    <span class="px-6 sm:px-8 py-3.5 sm:py-4 rounded-full bg-black group-hover:bg-[#FF5500] text-white text-xs sm:text-sm lg:text-base font-extrabold tracking-wider uppercase transition-colors duration-200 shadow-lg">
                        Get Started
                    </span>
                    <span class="rounded-full bg-black group-hover:bg-[#FF5500] text-white flex items-center justify-center transition-all duration-200 border-[3px] border-white shadow-md shrink-0 -ml-3 group-hover:rotate-45" style="width: 48px; height: 48px; min-width: 48px; min-height: 48px;">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                        </svg>
                    </span>
                </a>

                <div class="hidden sm:flex flex-col ml-4 text-xs text-neutral-500 font-semibold">
                    <span class="text-black font-bold">5 Soal Kontekstual • ~5 Menit</span>
                    <span>100% Gratis & Langsung Dianalisis AI</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Student Illustration & Floating Badges (Exact Meekoo Reference) -->
        <div class="lg:col-span-5 relative">
            <!-- Background Container with soft rounded shape -->
            <div class="relative w-full aspect-[4/5] max-w-md mx-auto">
                <!-- Inner card with rounded background, warm arch, and student -->
                <div class="relative w-full h-full rounded-[36px] flex items-end justify-center overflow-hidden">
                    
                    <!-- Soft Warm Arch/Semicircle behind student (like in image.png) -->
                    <div class="absolute -bottom-16 w-80 h-80 rounded-full bg-[#FFE8D6] z-0 opacity-80"></div>

                    <!-- Student Portrait (Full Height Cutout Style from reference image.png) -->
                    <img src="{{ asset('images/hero.png') }}" alt="Student Nalaria" 
                        class="relative z-10 w-full h-[80%] object-cover object-top filter contrast-[1.03]">
                </div>

                <!-- Floating Badge 1: Top Right (Dark Pill with Student Count) -->
                <div class="absolute top-4 sm:top-6 right-2 sm:-right-4 z-20 px-3 sm:px-4 py-2 sm:py-2.5 rounded-full bg-black text-white text-[11px] sm:text-xs font-bold flex items-center gap-2 sm:gap-2.5 shadow-xl border border-neutral-800">
                    <div class="flex -space-x-1.5 sm:-space-x-2">
                        <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-[#FF5500] border-2 border-black flex items-center justify-center text-[9px] sm:text-[10px] font-black">1</span>
                        <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-white text-black border-2 border-black flex items-center justify-center text-[9px] sm:text-[10px] font-black">2</span>
                        <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-neutral-700 border-2 border-black flex items-center justify-center text-[9px] sm:text-[10px] font-black">+</span>
                    </div>
                    <span>600k+ Siswa Terbantu</span>
                </div>

                <!-- Floating Badge 2: Middle Right (Bright Orange Pill - Best Collaboration / AI Agent) -->
                <div class="absolute top-1/2 right-1 sm:-right-6 z-20 px-3.5 sm:px-5 py-2 sm:py-3 rounded-2xl bg-[#FF5500] text-white text-[11px] sm:text-xs font-black flex items-center gap-2 sm:gap-2.5 shadow-xl shadow-orange-600/30 border-2 border-white">
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-white text-[#FF5500] flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <span>Best Collaboration</span>
                </div>

                <!-- Floating Badge 3: Bottom Left (Orange Card - Exact Meekoo Price/Stats Badge) -->
                <div class="absolute bottom-4 sm:bottom-8 left-2 sm:-left-6 z-20 p-3 sm:p-4 rounded-2xl bg-[#FF5500] text-white space-y-1 sm:space-y-1.5 shadow-2xl shadow-orange-600/40 min-w-[150px] sm:min-w-[180px] text-left border-2 border-white/90">
                    <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-wider bg-black text-white px-2 py-0.5 rounded-md inline-block">AKM & PISA 2026</span>
                    <div class="text-2xl sm:text-3xl font-black tracking-tight leading-none pt-0.5">100%</div>
                    <div class="text-[10px] sm:text-[11px] font-bold text-white/90 leading-tight">Diagnostik Adaptif & Rekomendasi AI</div>
                </div>

            </div>
        </div>

    </div>

    <!-- METRICS STRIP (Exact Meekoo 4-Stat Strip with Black Dots) -->
    <div class="meekoo-stat-strip p-6 sm:p-10 lg:p-12 border border-neutral-200/80">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 items-center text-center">
            
            <!-- Stat 1 -->
            <div class="space-y-1">
                <div class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-black">100%</div>
                <div class="text-xs sm:text-sm font-semibold text-neutral-500">Akurasi Deteksi Miskonsepsi</div>
            </div>

            <!-- Stat 2 -->
            <div class="space-y-1 relative">
                <span class="hidden md:block absolute -left-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-black"></span>
                <div class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-black">12+</div>
                <div class="text-xs sm:text-sm font-semibold text-neutral-500">Studi Kasus Berkelanjutan</div>
            </div>

            <!-- Stat 3 -->
            <div class="space-y-1 relative">
                <span class="hidden md:block absolute -left-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-black"></span>
                <div class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-black">20k+</div>
                <div class="text-xs sm:text-sm font-semibold text-neutral-500">Latihan Adaptif Tergenerate</div>
            </div>

            <!-- Stat 4 -->
            <div class="space-y-1 relative">
                <span class="hidden md:block absolute -left-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-black"></span>
                <div class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-black">4 Domain</div>
                <div class="text-xs sm:text-sm font-semibold text-neutral-500">Aljabar, Geometri, Data & Sosial</div>
            </div>

        </div>
    </div>

    <!-- COURSES / DIAGNOSTIC MODULES SECTION (Exact Meekoo Course Grid) -->
    <div id="features" class="space-y-8 pt-6">
        
        <!-- Section Title & Subtitle -->
        <div class="text-center space-y-3 max-w-3xl mx-auto">
            <div class="inline-block px-3.5 py-1 rounded-full bg-[#F4F4F6] text-xs font-bold text-neutral-800 uppercase tracking-wider">
                Modul Asesmen
            </div>
            <h2 class="meekoo-heading text-3xl sm:text-4xl lg:text-5xl font-extrabold text-black">
                Courses Designed for Success
            </h2>
            <p class="text-sm sm:text-base text-neutral-600 font-normal">
                Uji penalaranmu pada situasi nyata keberlanjutan masa depan. AI Agent akan mengidentifikasi jenis kesalahan dan merancang latihan perbaikan personal.
            </p>
        </div>

        <!-- Filter Tabs Row (Exact Meekoo Nav Pills) -->
        <div class="flex items-center justify-start sm:justify-center gap-2 overflow-x-auto pb-2 text-xs font-bold">
            <span class="px-4 py-2 rounded-full bg-black text-white cursor-pointer">All Domains</span>
            <span class="px-4 py-2 rounded-full bg-[#F4F4F6] text-neutral-700 hover:bg-[#FF5500] hover:text-white cursor-pointer transition-colors">Aritmatika Sosial</span>
            <span class="px-4 py-2 rounded-full bg-[#F4F4F6] text-neutral-700 hover:bg-[#FF5500] hover:text-white cursor-pointer transition-colors">Aljabar & PLTS</span>
            <span class="px-4 py-2 rounded-full bg-[#F4F4F6] text-neutral-700 hover:bg-[#FF5500] hover:text-white cursor-pointer transition-colors">Geometri & Rooftop</span>
            <span class="px-4 py-2 rounded-full bg-[#F4F4F6] text-neutral-700 hover:bg-[#FF5500] hover:text-white cursor-pointer transition-colors">Data Sampah Organik</span>
        </div>

        <!-- Course Cards Grid (2 rows x 3 columns in Meekoo Style) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Card 1: Aritmatika Sosial -->
            <div class="meekoo-card p-6 flex flex-col justify-between space-y-5">
                <div class="space-y-4">
                    <!-- Thumbnail header -->
                    <div class="w-full h-44 rounded-2xl bg-black text-white p-5 flex flex-col justify-between relative overflow-hidden">
                        <div class="flex items-center justify-between z-10">
                            <span class="px-2.5 py-1 rounded-md bg-[#FF5500] text-white text-[11px] font-bold">Aritmatika Sosial</span>
                            <span class="text-xs text-neutral-400 font-mono">Soal 01</span>
                        </div>
                        <div class="z-10">
                            <div class="text-lg font-black text-white">Diskon Bertingkat Daur Ulang</div>
                            <div class="text-xs text-neutral-300">Tumbler Ramah Lingkungan 50% + 20%</div>
                        </div>
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 rounded-full bg-[#FF5500]/20 blur-xl"></div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs text-neutral-500 font-semibold">
                            <span class="text-black font-bold">Miskonsepsi Kunci:</span>
                            <span class="text-[#FF5500]">Additive Sequential Trap</span>
                        </div>
                        <p class="text-xs text-neutral-600 leading-relaxed">
                            Mendiagnosis apakah siswa menjumlahkan 50% + 20% menjadi 70%, atau memahami bahwa diskon kedua dihitung dari harga sisa.
                        </p>
                    </div>
                </div>

                <div class="pt-4 border-t border-neutral-100 flex items-center justify-between">
                    <div class="flex items-center gap-1 text-[#FF5500] text-xs font-bold">
                        <span>★★★★★</span>
                        <span class="text-neutral-500 font-semibold">(5.0 PISA)</span>
                    </div>
                    <a href="{{ route('diagnostic.quiz') }}" class="px-5 py-2.5 rounded-full bg-black hover:bg-[#FF5500] text-white font-bold text-xs transition-colors">
                        Mulai Soal →
                    </a>
                </div>
            </div>

            <!-- Card 2: Aljabar -->
            <div class="meekoo-card p-6 flex flex-col justify-between space-y-5">
                <div class="space-y-4">
                    <!-- Thumbnail header -->
                    <div class="w-full h-44 rounded-2xl bg-neutral-900 text-white p-5 flex flex-col justify-between relative overflow-hidden">
                        <div class="flex items-center justify-between z-10">
                            <span class="px-2.5 py-1 rounded-md bg-[#FF5500] text-white text-[11px] font-bold">Aljabar & Fungsi</span>
                            <span class="text-xs text-neutral-400 font-mono">Soal 02</span>
                        </div>
                        <div class="z-10">
                            <div class="text-lg font-black text-white">Tarif Listrik Panel Surya</div>
                            <div class="text-xs text-neutral-300">Pemodelan Linear T(x) = mx + b</div>
                        </div>
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 rounded-full bg-white/10 blur-xl"></div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs text-neutral-500 font-semibold">
                            <span class="text-black font-bold">Miskonsepsi Kunci:</span>
                            <span class="text-[#FF5500]">Variable Reversal Error</span>
                        </div>
                        <p class="text-xs text-neutral-600 leading-relaxed">
                            Mendiagnosis apakah siswa membalikkan konstanta biaya abonemen tetap dengan variabel tarif per kilowatt-jam.
                        </p>
                    </div>
                </div>

                <div class="pt-4 border-t border-neutral-100 flex items-center justify-between">
                    <div class="flex items-center gap-1 text-[#FF5500] text-xs font-bold">
                        <span>★★★★★</span>
                        <span class="text-neutral-500 font-semibold">(5.0 PISA)</span>
                    </div>
                    <a href="{{ route('diagnostic.quiz') }}" class="px-5 py-2.5 rounded-full bg-black hover:bg-[#FF5500] text-white font-bold text-xs transition-colors">
                        Mulai Soal →
                    </a>
                </div>
            </div>

            <!-- Card 3: Geometri -->
            <div class="meekoo-card p-6 flex flex-col justify-between space-y-5">
                <div class="space-y-4">
                    <!-- Thumbnail header -->
                    <div class="w-full h-44 rounded-2xl bg-neutral-950 text-white p-5 flex flex-col justify-between relative overflow-hidden">
                        <div class="flex items-center justify-between z-10">
                            <span class="px-2.5 py-1 rounded-md bg-[#FF5500] text-white text-[11px] font-bold">Geometri & Spasial</span>
                            <span class="text-xs text-neutral-400 font-mono">Soal 03</span>
                        </div>
                        <div class="z-10">
                            <div class="text-lg font-black text-white">Denah Skala Panel Surya</div>
                            <div class="text-xs text-neutral-300">Skala 1:100 pada Luas Dua Dimensi</div>
                        </div>
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 rounded-full bg-[#FF5500]/30 blur-xl"></div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs text-neutral-500 font-semibold">
                            <span class="text-black font-bold">Miskonsepsi Kunci:</span>
                            <span class="text-[#FF5500]">Linear Scaling in 2D Area</span>
                        </div>
                        <p class="text-xs text-neutral-600 leading-relaxed">
                            Mendiagnosis apakah siswa keliru mengalikan luas dengan skala linier k, bukan kuadrat skala k² atau konversi sisi nyata.
                        </p>
                    </div>
                </div>

                <div class="pt-4 border-t border-neutral-100 flex items-center justify-between">
                    <div class="flex items-center gap-1 text-[#FF5500] text-xs font-bold">
                        <span>★★★★★</span>
                        <span class="text-neutral-500 font-semibold">(5.0 PISA)</span>
                    </div>
                    <a href="{{ route('diagnostic.quiz') }}" class="px-5 py-2.5 rounded-full bg-black hover:bg-[#FF5500] text-white font-bold text-xs transition-colors">
                        Mulai Soal →
                    </a>
                </div>
            </div>

        </div>

        <!-- Load More / Action Pill -->
        <div class="text-center pt-4">
            <a href="{{ route('diagnostic.quiz') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full bg-black hover:bg-[#FF5500] text-white font-extrabold text-xs uppercase tracking-wider transition-all shadow-md">
                <span>Kerjakan Semua Soal Sekarang</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- POWERFUL FEATURES SECTION (Exact Meekoo Feature Split) -->
    <div id="stats" class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center py-10 border-t border-neutral-200">
        
        <!-- Left: Student Portrait with Progress Card -->
        <div class="lg:col-span-5 relative">
            <div class="w-full aspect-square rounded-[32px] bg-[#F6F6F8] p-8 flex items-center justify-center relative overflow-hidden border border-neutral-200">
                
                <div class="text-center space-y-4">
                    <!-- Progress Ring 85% -->
                    <div class="relative w-32 h-32 mx-auto flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-neutral-200" stroke-width="3.5" stroke="currentColor" fill="none"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="text-[#FF5500]" stroke-width="3.5" stroke-dasharray="85, 100" stroke-linecap="round" stroke="currentColor" fill="none"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-2xl font-black text-black">85%</span>
                            <span class="text-[10px] font-bold text-neutral-500 uppercase">AKURASI</span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <div class="text-lg font-black text-black">Peningkatan Penalaran</div>
                        <div class="text-xs text-neutral-500 font-semibold">Setelah Menuntaskan Remediasi AI</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Powerful Features Checklist -->
        <div class="lg:col-span-7 space-y-6">
            <div class="space-y-2">
                <span class="text-xs font-bold text-[#FF5500] uppercase tracking-wider">Keunggulan Sistem</span>
                <h3 class="meekoo-heading text-3xl sm:text-4xl font-extrabold text-black">
                    Powerful Features for<br>Your Learning Journey
                </h3>
                <p class="text-sm text-neutral-600 leading-relaxed font-normal">
                    Dibangun secara spesifik untuk mengatasi kesenjangan literasi-numerasi Indonesia melalui integrasi AI Agent yang melakukan minimal 2 aksi nyata.
                </p>
            </div>

            <div class="space-y-4 pt-2">
                <!-- Feature Item 1 -->
                <div class="flex items-start gap-3.5 p-3.5 rounded-2xl hover:bg-[#F6F6F8] transition-colors">
                    <span class="w-8 h-8 rounded-full bg-[#FF5500] text-white flex items-center justify-center font-bold text-sm flex-shrink-0 mt-0.5">
                        ✓
                    </span>
                    <div>
                        <div class="text-base font-bold text-black">AI Aksi 1: Deteksi Akar Miskonsepsi Kognitif</div>
                        <p class="text-xs text-neutral-600 leading-relaxed mt-0.5">
                            AI mengevaluasi pola penalaran dari argumen yang ditulis siswa, membedakan antara ketidaktelitian hitung vs kesalahan konseptual fundamental.
                        </p>
                    </div>
                </div>

                <!-- Feature Item 2 -->
                <div class="flex items-start gap-3.5 p-3.5 rounded-2xl hover:bg-[#F6F6F8] transition-colors">
                    <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center font-bold text-sm flex-shrink-0 mt-0.5">
                        ✓
                    </span>
                    <div>
                        <div class="text-base font-bold text-black">AI Aksi 2: Generator Soal Latihan Adaptif Bertarget</div>
                        <p class="text-xs text-neutral-600 leading-relaxed mt-0.5">
                            Menghasilkan 2-3 paket latihan kontekstual baru dengan tingkat kesulitan bertahap dan petunjuk berpikir (scaffolding hint).
                        </p>
                    </div>
                </div>

                <!-- Feature Item 3 -->
                <div class="flex items-start gap-3.5 p-3.5 rounded-2xl hover:bg-[#F6F6F8] transition-colors">
                    <span class="w-8 h-8 rounded-full bg-[#FF5500] text-white flex items-center justify-center font-bold text-sm flex-shrink-0 mt-0.5">
                        ✓
                    </span>
                    <div>
                        <div class="text-base font-bold text-black">Umpan Balik Instan Tanpa Reload Halaman</div>
                        <p class="text-xs text-neutral-600 leading-relaxed mt-0.5">
                            Siswa dapat langsung mencoba menjawab latihan adaptif di dashboard hasil dan menerima penjelasan logika interaktif secara real-time.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
