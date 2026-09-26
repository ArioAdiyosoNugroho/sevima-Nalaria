<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nalaria — Shape Your Future with Adaptive AI Numeracy')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    
    <!-- Google Fonts: Plus Jakarta Sans for exact Meekoo geometric bold typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --font-sans: 'Plus Jakarta Sans', system-ui, sans-serif;
            /* 3-Color Strict Palette: Orange, Black, White */
            --color-primary: #FF5500;
            --color-primary-hover: #E04B00;
            --color-black: #0A0A0A;
            --color-card-dark: #121212;
            --color-white: #FFFFFF;
            --color-surface: #F6F6F8;
            --color-border: #E8E8EC;
            --color-text-main: #0A0A0A;
            --color-text-muted: #6B7280;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-sans);
            background-color: #FFFFFF;
            color: var(--color-text-main);
            -webkit-font-smoothing: antialiased;
        }

        .meekoo-heading {
            font-family: var(--font-sans);
            letter-spacing: -0.035em;
            font-weight: 800;
        }

        .meekoo-pill-btn {
            background-color: #0A0A0A;
            color: #FFFFFF;
            border-radius: 9999px;
            font-weight: 700;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .meekoo-pill-btn:hover {
            background-color: #FF5500;
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        .meekoo-arrow-circle {
            width: 44px;
            height: 44px;
            border-radius: 9999px;
            background-color: #0A0A0A;
            color: #FFFFFF;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .meekoo-arrow-circle:hover {
            background-color: #FF5500;
            transform: rotate(45deg);
        }

        .meekoo-stat-strip {
            background-color: #F6F6F8;
            border-radius: 28px;
        }

        .meekoo-badge-orange {
            background-color: #FF5500;
            color: #FFFFFF;
            border-radius: 9999px;
        }

        .meekoo-card {
            background: #FFFFFF;
            border: 1px solid #ECECEF;
            border-radius: 24px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .meekoo-card:hover {
            border-color: #FF5500;
            box-shadow: 0 16px 36px -12px rgba(10, 10, 10, 0.08);
            transform: translateY(-2px);
        }

        /* Glassmorphism Dynamic Navbar Scrolled State */
        .navbar-scrolled {
            background-color: rgba(255, 255, 255, 0.96) !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            border-bottom-color: rgba(230, 230, 235, 0.9) !important;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.06) !important;
            padding-top: 12px !important;
            padding-bottom: 12px !important;
        }

        /* Responsive Desktop vs Mobile Navigation Guarantee */
        @media (min-width: 1024px) {
            #mobile-menu-btn {
                display: none !important;
            }
            #mobile-menu-drawer {
                display: none !important;
            }
            .nav-desktop-menu {
                display: flex !important;
                align-items: center !important;
            }
        }

        @media (max-width: 1023px) {
            #mobile-menu-btn {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .nav-desktop-menu {
                display: none !important;
            }
        }

        /* Smooth Pill Links */
        .nav-pill-link {
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .nav-pill-link:hover {
            transform: translateY(-1px);
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-white text-black selection:bg-[#FF5500] selection:text-white">

    <!-- Top Navigation Bar (Enhanced Smooth Meekoo Header with Generous Breathing Room) -->
    <header id="main-navbar" class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-neutral-100/90 transition-all duration-300 py-4 sm:py-5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 min-h-[56px] sm:min-h-[64px] flex items-center justify-between gap-6">
            
            <!-- Brand Logo (User Custom Logo) -->
            <a href="{{ route('diagnostic.landing') }}" class="flex items-center gap-3.5 shrink-0 group active:scale-95 transition-transform duration-200">
                <div class="w-10 h-10 sm:w-11 sm:h-11 flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
                    <x-logo class="w-10 h-10 sm:w-11 sm:h-11" fill="#FF5500" />
                </div>
                <div class="flex items-center">
                    <span class="text-2xl sm:text-3xl font-black tracking-tight text-black">Nalaria</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-[#FF5500] ml-1 group-hover:scale-125 transition-transform duration-200"></span>
                </div>
            </a>

            <!-- Center Navigation Links (Visible ONLY on Desktop, Perfectly Centered) -->
            <nav class="nav-desktop-menu hidden lg:flex items-center gap-1.5 text-xs font-bold text-neutral-600">
                <a href="{{ route('diagnostic.landing') }}" class="nav-pill-link px-4 py-2.5 rounded-full {{ request()->routeIs('diagnostic.landing') ? 'bg-[#FFF3EB] text-[#FF5500] shadow-sm font-extrabold' : 'hover:text-black hover:bg-neutral-100' }}">
                    About Us
                </a>
                <a href="{{ route('diagnostic.quiz') }}" class="nav-pill-link px-4 py-2.5 rounded-full {{ request()->routeIs('diagnostic.quiz') ? 'bg-[#FFF3EB] text-[#FF5500] shadow-sm font-extrabold' : 'hover:text-black hover:bg-neutral-100' }}">
                    Asesmen AI
                </a>
                @auth
                <a href="{{ route('diagnostic.history') }}" class="nav-pill-link px-4 py-2.5 rounded-full {{ request()->routeIs('diagnostic.history') ? 'bg-[#FFF3EB] text-[#FF5500] shadow-sm font-extrabold' : 'hover:text-black hover:bg-neutral-100' }}">
                    Riwayat Tes Saya
                </a>
                @endauth
                <a href="{{ route('diagnostic.landing') }}#features" class="nav-pill-link px-4 py-2.5 rounded-full hover:text-black hover:bg-neutral-100">
                    Fitur & AI Agent
                </a>
                <a href="{{ route('diagnostic.landing') }}#stats" class="nav-pill-link px-4 py-2.5 rounded-full hover:text-black hover:bg-neutral-100">
                    Metodologi PISA
                </a>
            </nav>

            <!-- Right Actions (Auth & Smooth Meekoo Pill, Perfectly Aligned) -->
            <div class="flex items-center gap-3 shrink-0">
                @auth
                    <!-- Interactive Profile Pill with Dropdown -->
                    <div class="relative" id="user-menu-wrapper">
                        <button id="user-menu-btn" type="button" class="flex items-center gap-2.5 px-4 py-2 rounded-full bg-neutral-100 hover:bg-neutral-200 border border-neutral-200/80 transition-all duration-200 cursor-pointer active:scale-95" aria-expanded="false">
                            <span class="w-6 h-6 rounded-full bg-black text-white text-[11px] font-black flex items-center justify-center border border-[#FF5500]">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="text-xs font-bold text-neutral-800 max-w-[120px] truncate">
                                {{ Auth::user()->name }}
                            </span>
                            <svg id="user-menu-chevron" class="w-3.5 h-3.5 text-neutral-500 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <!-- Smooth User Dropdown Menu -->
                        <div id="user-dropdown-menu" class="hidden absolute right-0 mt-2.5 w-60 rounded-2xl bg-white border border-neutral-200/90 shadow-2xl p-2 z-50 transform origin-top-right transition-all duration-200">
                            <div class="px-3 py-2.5 border-b border-neutral-100 mb-1.5">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-neutral-400">Akun Siswa</p>
                                <p class="text-xs font-black text-black truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-neutral-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('diagnostic.history') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-neutral-700 hover:text-[#FF5500] hover:bg-orange-50 transition-colors">
                                <span>📊</span>
                                <span>Riwayat Tes Saya</span>
                            </a>
                            <a href="{{ route('diagnostic.quiz') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-neutral-700 hover:text-black hover:bg-neutral-100 transition-colors">
                                <span>🎯</span>
                                <span>Mulai Asesmen AI</span>
                            </a>
                            <div class="my-1.5 border-t border-neutral-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-neutral-500 hover:text-[#FF5500] hover:bg-neutral-100 transition-colors cursor-pointer text-left">
                                    <span>🚪</span>
                                    <span>Keluar (Logout)</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Meekoo CTA Pill Button -->
                    <a href="{{ route('diagnostic.quiz') }}" class="hidden sm:inline-flex items-center group active:scale-95 transition-transform duration-200">
                        <span class="px-5 py-2.5 rounded-full bg-black group-hover:bg-[#FF5500] text-white text-xs font-bold transition-colors duration-200 shadow-sm">
                            Mulai Asesmen
                        </span>
                        <span class="rounded-full bg-black group-hover:bg-[#FF5500] text-white flex items-center justify-center transition-all duration-200 border-2 border-white group-hover:rotate-45 shrink-0 -ml-2" style="width: 44px; height: 44px; min-width: 44px; min-height: 44px;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                            </svg>
                        </span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-pill-link text-xs font-bold text-neutral-700 hover:text-[#FF5500] px-4 py-2.5 rounded-full hover:bg-neutral-100 transition-all duration-200">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center group active:scale-95 transition-transform duration-200">
                        <span class="px-5 py-2.5 rounded-full bg-black group-hover:bg-[#FF5500] text-white text-xs font-bold transition-colors duration-200 shadow-sm">
                            Daftar Siswa
                        </span>
                        <span class="rounded-full bg-black group-hover:bg-[#FF5500] text-white flex items-center justify-center transition-all duration-200 border-2 border-white group-hover:rotate-45 shrink-0 -ml-2" style="width: 44px; height: 44px; min-width: 44px; min-height: 44px;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                            </svg>
                        </span>
                    </a>
                @endauth

                <!-- Mobile Hamburger Toggle Button (STRICTLY HIDDEN on desktop via lg:hidden and scoped CSS) -->
                <button id="mobile-menu-btn" type="button" class="lg:hidden flex items-center justify-center w-10 h-10 rounded-full bg-neutral-100 hover:bg-neutral-200 text-neutral-800 transition-colors cursor-pointer active:scale-90" aria-label="Menu Navigasi">
                    <svg id="hamburger-icon" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg id="close-icon" class="w-5 h-5 hidden transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Responsive Navigation Drawer (STRICTLY HIDDEN on desktop via lg:hidden and scoped CSS) -->
        <div id="mobile-menu-drawer" class="hidden lg:hidden border-b border-neutral-200 bg-white/95 backdrop-blur-xl px-4 pt-3 pb-6 transition-all duration-300">
            <div class="flex flex-col space-y-1.5">
                <a href="{{ route('diagnostic.landing') }}" class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-colors {{ request()->routeIs('diagnostic.landing') ? 'bg-orange-50 text-[#FF5500]' : 'text-neutral-700 hover:bg-neutral-100' }}">
                    About Us
                </a>
                <a href="{{ route('diagnostic.quiz') }}" class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-colors {{ request()->routeIs('diagnostic.quiz') ? 'bg-orange-50 text-[#FF5500]' : 'text-neutral-700 hover:bg-neutral-100' }}">
                    Asesmen AI
                </a>
                @auth
                <a href="{{ route('diagnostic.history') }}" class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-colors {{ request()->routeIs('diagnostic.history') ? 'bg-orange-50 text-[#FF5500]' : 'text-neutral-700 hover:bg-neutral-100' }}">
                    Riwayat Tes Saya
                </a>
                @endauth
                <a href="{{ route('diagnostic.landing') }}#features" class="px-4 py-2.5 rounded-2xl text-xs font-bold text-neutral-700 hover:bg-neutral-100 transition-colors">
                    Fitur & AI Agent
                </a>
                <a href="{{ route('diagnostic.landing') }}#stats" class="px-4 py-2.5 rounded-2xl text-xs font-bold text-neutral-700 hover:bg-neutral-100 transition-colors">
                    Metodologi PISA
                </a>
                <a href="https://github.com/ArioAdiyosoNugroho/sevima-Nalaria" target="_blank" class="px-4 py-2.5 rounded-2xl text-xs font-bold text-neutral-700 hover:bg-neutral-100 transition-colors flex items-center justify-between">
                    <span>GitHub Repository</span>
                    <span class="text-[10px] text-neutral-400">↗</span>
                </a>

                <div class="pt-3 border-t border-neutral-100 mt-2 flex flex-col gap-2">
                    @auth
                        <div class="flex items-center justify-between px-4 py-2.5 rounded-2xl bg-neutral-50 text-xs font-semibold">
                            <span class="text-neutral-700 font-bold">👤 {{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="font-extrabold text-[#FF5500] hover:underline cursor-pointer">Keluar</button>
                            </form>
                        </div>
                        <a href="{{ route('diagnostic.quiz') }}" class="w-full py-3 rounded-full bg-[#FF5500] text-white text-xs font-extrabold text-center shadow-lg shadow-orange-500/20 active:scale-95 transition-transform">
                            Mulai Asesmen Sekarang →
                        </a>
                    @else
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('login') }}" class="w-full py-2.5 rounded-full bg-neutral-100 hover:bg-neutral-200 text-black text-xs font-bold text-center active:scale-95 transition-transform">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="w-full py-2.5 rounded-full bg-[#FF5500] text-white text-xs font-bold text-center active:scale-95 transition-transform shadow-md shadow-orange-500/20">
                                Daftar Siswa
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Alert -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-4 rounded-2xl bg-black text-white text-sm font-semibold flex items-center justify-between border-l-4 border-[#FF5500] shadow-md animate-fade-in">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-[#FF5500] text-white flex items-center justify-center font-bold text-xs">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer (Black & White Clean Editorial) -->
    <footer class="bg-black text-white pt-16 pb-12 border-t border-neutral-900 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8 pb-10 border-b border-neutral-800">
                <div class="space-y-3 max-w-md">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center">
                            <x-logo class="w-9 h-9" fill="#FF5500" />
                        </div>
                        <span class="text-2xl font-black tracking-tight text-white">Nalaria</span>
                    </div>
                    <p class="text-xs text-neutral-400 leading-relaxed">
                        AI Diagnostik Literasi-Numerasi Kontekstual Indonesia. Powered by <strong>Nvidia Nemotron (OpenRouter)</strong> & Laravel 13 untuk Hackathon SEMESTA 8 by SEVIMA.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-xs text-neutral-400 font-semibold mr-2">Tech Stack & AI Model:</span>
                    <span class="px-3 py-1 rounded-full bg-neutral-900 border border-neutral-800 text-xs font-mono text-[#FF5500]">nvidia/nemotron-3-ultra-550b-a55b:free</span>
                    <span class="px-3 py-1 rounded-full bg-neutral-900 border border-neutral-800 text-xs font-mono text-white">OpenRouter API</span>
                    <span class="px-3 py-1 rounded-full bg-neutral-900 border border-neutral-800 text-xs font-mono text-white">Laravel 13</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-500">
                <div>
                    © 2026 Nalaria. Built for Hackathon SEMESTA 8. Theme: Empowering Youth for a Sustainable Future: Build with AI.
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ route('diagnostic.quiz') }}" class="hover:text-white transition-colors">Asesmen Kuis</a>
                    <a href="https://github.com/ArioAdiyosoNugroho/sevima-Nalaria" target="_blank" class="hover:text-white transition-colors">GitHub Repository</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Interactive Navigation Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dynamic scroll shadow and elevation
            const header = document.getElementById('main-navbar');
            if (header) {
                const handleScroll = function () {
                    if (window.scrollY > 15) {
                        header.classList.add('navbar-scrolled');
                    } else {
                        header.classList.remove('navbar-scrolled');
                    }
                };
                window.addEventListener('scroll', handleScroll, { passive: true });
                handleScroll();
            }

            // User dropdown menu toggle
            const userBtn = document.getElementById('user-menu-btn');
            const userDropdown = document.getElementById('user-dropdown-menu');
            const chevron = document.getElementById('user-menu-chevron');

            if (userBtn && userDropdown) {
                userBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isClosed = userDropdown.classList.contains('hidden');
                    if (isClosed) {
                        userDropdown.classList.remove('hidden');
                        if (chevron) chevron.style.transform = 'rotate(180deg)';
                    } else {
                        userDropdown.classList.add('hidden');
                        if (chevron) chevron.style.transform = 'rotate(0deg)';
                    }
                });

                document.addEventListener('click', function (e) {
                    if (!userDropdown.contains(e.target) && !userBtn.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                        if (chevron) chevron.style.transform = 'rotate(0deg)';
                    }
                });
            }

            // Mobile menu drawer toggle
            const mobileBtn = document.getElementById('mobile-menu-btn');
            const mobileDrawer = document.getElementById('mobile-menu-drawer');
            const hamburgerIcon = document.getElementById('hamburger-icon');
            const closeIcon = document.getElementById('close-icon');

            if (mobileBtn && mobileDrawer) {
                mobileBtn.addEventListener('click', function () {
                    const isClosed = mobileDrawer.classList.contains('hidden');
                    if (isClosed) {
                        mobileDrawer.classList.remove('hidden');
                        if (hamburgerIcon) hamburgerIcon.classList.add('hidden');
                        if (closeIcon) closeIcon.classList.remove('hidden');
                    } else {
                        mobileDrawer.classList.add('hidden');
                        if (hamburgerIcon) hamburgerIcon.classList.remove('hidden');
                        if (closeIcon) closeIcon.classList.add('hidden');
                    }
                });

                // Auto-close mobile drawer when any link is clicked
                mobileDrawer.querySelectorAll('a').forEach(function (link) {
                    link.addEventListener('click', function () {
                        mobileDrawer.classList.add('hidden');
                        if (hamburgerIcon) hamburgerIcon.classList.remove('hidden');
                        if (closeIcon) closeIcon.classList.add('hidden');
                    });
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
