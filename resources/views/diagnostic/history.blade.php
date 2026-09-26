@extends('layouts.app')

@section('title', 'Riwayat Asesmen Numerasi — Nalaria')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">

    <!-- Breadcrumb & Top Tag -->
    <div class="flex items-center gap-2 text-xs font-semibold text-neutral-500 mb-6">
        <a href="{{ route('diagnostic.landing') }}" class="hover:text-black transition-colors">Beranda</a>
        <span>/</span>
        <span class="text-[#FF5500]">Riwayat Tes Saya</span>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 pb-8 border-b border-neutral-200">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-orange-50 border border-orange-200 text-xs font-bold text-[#FF5500] mb-3">
                <span class="w-2 h-2 rounded-full bg-[#FF5500] animate-pulse"></span>
                Portofolio Pembelajaran Adaptif
            </div>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-black">
                Riwayat Asesmen & Capaian Numerasi
            </h1>
            <p class="text-sm text-neutral-500 font-medium mt-2 max-w-2xl">
                Pantau progres perkembangan penalaran numerasi, analisis pola miskonsepsi terstruktur, dan efektivitas remediasi adaptif akun <strong>{{ Auth::user()->name }}</strong>.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('diagnostic.quiz') }}" class="group inline-flex items-center active:scale-95 transition-transform duration-200">
                <span class="px-6 py-3 rounded-full bg-[#FF5500] group-hover:bg-black text-white text-xs sm:text-sm font-extrabold transition-colors duration-200 shadow-lg shadow-orange-500/20">
                    + Ambil Asesmen Baru
                </span>
                <span class="rounded-full bg-black text-white flex items-center justify-center transition-all duration-200 border-2 border-white shrink-0 -ml-3 group-hover:rotate-45" style="width: 48px; height: 48px; min-width: 48px; min-height: 48px;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                    </svg>
                </span>
            </a>
        </div>
    </div>

    <!-- Aggregated Mastery Overview (Tahap 5) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-12">
        
        <!-- Big Mastery Score Card -->
        <div class="lg:col-span-7 bg-black text-white rounded-[32px] p-6 sm:p-8 relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-[#FF5500]/15 blur-3xl pointer-events-none"></div>

            <div>
                <div class="flex items-center justify-between gap-4 mb-6">
                    <span class="text-xs font-bold uppercase tracking-wider text-neutral-400">
                        Skor Penguasaan Numerasi Kumulatif
                    </span>
                    <span class="px-3 py-1 rounded-full bg-neutral-900 border border-neutral-800 text-xs font-bold text-[#FF5500]">
                        Standar AKM / PISA
                    </span>
                </div>

                <div class="flex items-baseline gap-4 mb-3">
                    <span class="text-6xl sm:text-7xl font-black tracking-tight text-white">
                        {{ $masteryData['mastery_score'] }}
                    </span>
                    <span class="text-xl sm:text-2xl font-bold text-neutral-400">/ 100</span>
                </div>

                <div class="inline-block px-4 py-1.5 rounded-full bg-[#FF5500] text-white text-xs font-extrabold tracking-wide mb-6">
                    {{ $masteryData['level'] }}
                </div>

                <p class="text-xs sm:text-sm text-neutral-300 leading-relaxed font-medium">
                    Skor penguasaan numerasi ini mengkombinasikan rerata skor tes diagnostik (bobot 70%) dengan pencapaian penyelesaian soal latihan remediasi adaptif AI (bobot 30%).
                </p>
            </div>

            <!-- Mini Metrics Row -->
            <div class="grid grid-cols-3 gap-3 pt-6 mt-6 border-t border-neutral-800">
                <div>
                    <span class="block text-[11px] text-neutral-400 font-semibold mb-1">Rerata Diagnostik</span>
                    <span class="text-base sm:text-lg font-extrabold text-white">{{ $masteryData['average_diagnostic_score'] }}%</span>
                </div>
                <div>
                    <span class="block text-[11px] text-neutral-400 font-semibold mb-1">Penyelesaian Remediasi</span>
                    <span class="text-base sm:text-lg font-extrabold text-white">{{ $masteryData['remediation_completion_rate'] }}%</span>
                </div>
                <div>
                    <span class="block text-[11px] text-neutral-400 font-semibold mb-1">Total Sesi Selesai</span>
                    <span class="text-base sm:text-lg font-extrabold text-white">{{ $masteryData['total_sessions'] }} Sesi</span>
                </div>
            </div>
        </div>

        <!-- 4 Fixed Misconception Categories Breakdown (Tahap 4) -->
        <div class="lg:col-span-5 bg-white border border-neutral-200 rounded-[32px] p-6 sm:p-8 flex flex-col justify-between shadow-sm">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-extrabold text-black">
                        Pemetaan 4 Kategori Numerasi
                    </h3>
                    <span class="text-xs font-semibold text-neutral-400">Fokus Evaluasi</span>
                </div>
                <p class="text-xs text-neutral-500 mb-6">
                    Klasifikasi miskonsepsi terstruktur yang terdeteksi di seluruh sesi penalaran Anda:
                </p>

                <div class="space-y-3.5">
                    @php
                        $categories = [
                            ['name' => 'Aritmatika Sosial', 'desc' => 'Rasio, bunga, diskon & efisiensi biaya', 'icon' => '🏷️'],
                            ['name' => 'Aljabar', 'desc' => 'Persamaan linear, pemodelan tarif & laju perubahan', 'icon' => '📈'],
                            ['name' => 'Geometri Spasial', 'desc' => 'Volume, luas permukaan & orientasi ruang', 'icon' => '📐'],
                            ['name' => 'Data & Statistik', 'desc' => 'Interpretasi tabel, diagram & estimasi peluang', 'icon' => '📊'],
                        ];
                    @endphp

                    @foreach($categories as $cat)
                        <div class="p-3.5 rounded-2xl bg-neutral-50 border border-neutral-200/70 flex items-center justify-between hover:border-[#FF5500] transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">{{ $cat['icon'] }}</span>
                                <div>
                                    <h4 class="text-xs font-bold text-black">{{ $cat['name'] }}</h4>
                                    <p class="text-[11px] text-neutral-500">{{ $cat['desc'] }}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-white border border-neutral-200 text-[10px] font-bold text-neutral-700">
                                Terpantau
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <!-- Timeline of Assessment Sessions (Tahap 3) -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl sm:text-2xl font-black tracking-tight text-black">
                Daftar Sesi Asesmen ({{ count($sessions) }})
            </h2>
            <span class="text-xs font-semibold text-neutral-500">Urutkan: Terbaru ke Terlama</span>
        </div>

        @if($sessions->isEmpty())
            <!-- Empty State -->
            <div class="bg-neutral-50 border-2 border-dashed border-neutral-200 rounded-[32px] p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-orange-100 text-[#FF5500] flex items-center justify-center mx-auto mb-4 font-bold text-2xl">
                    📝
                </div>
                <h3 class="text-lg font-extrabold text-black mb-1">Belum Ada Sesi Asesmen</h3>
                <p class="text-xs text-neutral-500 max-w-md mx-auto mb-6">
                    Anda belum menyelesaikan tes diagnostik numerasi adaptif. Ambil tes sekarang untuk mendeteksi profil penalaran kognitif Anda.
                </p>
                <a href="{{ route('diagnostic.quiz') }}" class="inline-flex items-center px-6 py-3 rounded-full bg-black hover:bg-[#FF5500] text-white text-xs font-extrabold transition-all shadow-md">
                    Mulai Asesmen Pertama Saya →
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($sessions as $sess)
                    @php
                        $solvedCount = $sess->recommendations->where('is_solved', true)->count();
                        $totalRecs = $sess->recommendations->count();
                        $dateFormatted = $sess->created_at ? $sess->created_at->format('d M Y, H:i') : '-';
                    @endphp
                    <div class="bg-white border border-neutral-200/90 rounded-[28px] p-5 sm:p-7 hover:border-black/30 hover:shadow-lg hover:shadow-black/5 transition-all">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                            
                            <!-- Left: Date, Code & Grade -->
                            <div class="space-y-2">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <span class="px-3 py-1 rounded-full bg-neutral-100 font-mono text-xs font-bold text-neutral-800">
                                        {{ $sess->session_code }}
                                    </span>
                                    <span class="text-xs font-semibold text-neutral-500">
                                        📅 {{ $dateFormatted }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-full bg-orange-50 text-[11px] font-bold text-[#FF5500] border border-orange-100">
                                        {{ $sess->student_grade }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <h3 class="text-base sm:text-lg font-black text-black">
                                        {{ $sess->student_name }}
                                    </h3>
                                    <span class="px-3 py-0.5 rounded-full text-xs font-extrabold {{ $sess->score >= 80 ? 'bg-black text-white' : ($sess->score >= 60 ? 'bg-[#FF5500] text-white' : 'bg-neutral-200 text-neutral-800') }}">
                                        {{ $sess->mastery_level ?? 'Selesai' }}
                                    </span>
                                </div>

                                @if($sess->primary_misconception)
                                    <p class="text-xs text-neutral-600 line-clamp-1">
                                        <strong class="text-black">Miskonsepsi Primer:</strong> {{ $sess->primary_misconception }}
                                    </p>
                                @endif
                            </div>

                            <!-- Right: Scores & Action -->
                            <div class="flex flex-wrap items-center justify-between sm:justify-start gap-4 sm:gap-6 pt-4 lg:pt-0 border-t lg:border-t-0 border-neutral-100">
                                <div class="text-left sm:text-center">
                                    <span class="block text-[11px] font-semibold text-neutral-400">Skor Diagnostik</span>
                                    <span class="text-xl sm:text-2xl font-black text-black">{{ $sess->score }}%</span>
                                </div>

                                <div class="text-left sm:text-center">
                                    <span class="block text-[11px] font-semibold text-neutral-400">Remediasi Adaptif</span>
                                    <span class="text-sm sm:text-base font-extrabold {{ $solvedCount === $totalRecs && $totalRecs > 0 ? 'text-[#FF5500]' : 'text-neutral-800' }}">
                                        {{ $solvedCount }}/{{ $totalRecs }} Selesai
                                    </span>
                                </div>

                                <a href="{{ route('diagnostic.result', $sess->session_code) }}" class="inline-flex items-center justify-center w-full sm:w-auto gap-2 px-5 py-3 rounded-full bg-black hover:bg-[#FF5500] text-white text-xs font-bold transition-all shadow-sm">
                                    <span>Lihat Rapor Detail</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                                    </svg>
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
