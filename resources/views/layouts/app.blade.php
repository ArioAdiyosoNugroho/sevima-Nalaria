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
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
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
            transition: all 0.2s ease;
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
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-white text-black selection:bg-orange-500 selection:text-white">

    <!-- Top Navigation Bar (Exact Meekoo Header) -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-neutral-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-22 flex items-center justify-between">
            <!-- Brand Logo (User Custom Logo) -->
            <a href="{{ route('diagnostic.landing') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <x-logo class="w-10 h-10" fill="#FF5500" />
                </div>
                <div class="flex items-center">
                    <span class="text-2xl font-black tracking-tight text-black">Nalaria</span>
                    <span class="w-2 h-2 rounded-full bg-[#FF5500] ml-0.5"></span>
                </div>
            </a>

            <!-- Center Navigation Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-neutral-700">
                <a href="{{ route('diagnostic.landing') }}" class="hover:text-[#FF5500] transition-colors {{ request()->routeIs('diagnostic.landing') ? 'text-[#FF5500] font-bold' : '' }}">
                    About Us
                </a>
                <a href="{{ route('diagnostic.quiz') }}" class="hover:text-[#FF5500] transition-colors {{ request()->routeIs('diagnostic.quiz') ? 'text-[#FF5500] font-bold' : '' }}">
                    Asesmen AI
                </a>
                <a href="{{ route('diagnostic.landing') }}#features" class="hover:text-[#FF5500] transition-colors">
                    Fitur & AI Agent
                </a>
                <a href="{{ route('diagnostic.landing') }}#stats" class="hover:text-[#FF5500] transition-colors">
                    Metodologi PISA
                </a>
                <a href="https://github.com/ArioAdiyosoNugroho/sevima-Nalaria" target="_blank" class="hover:text-[#FF5500] transition-colors">
                    GitHub Repo
                </a>
            </nav>

            <!-- Right Action (Meekoo Pill + Arrow Circle) -->
            <div class="flex items-center gap-2">
                <a href="{{ route('diagnostic.quiz') }}" class="inline-flex items-center">
                    <span class="px-6 py-3 rounded-full bg-black hover:bg-[#FF5500] text-white text-sm font-bold transition-all shadow-sm">
                        Mulai Asesmen
                    </span>
                    <span class="w-11 h-11 -ml-2 rounded-full bg-black hover:bg-[#FF5500] text-white flex items-center justify-center transition-all border-2 border-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </header>

    <!-- Flash Alert -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-4 rounded-2xl bg-black text-white text-sm font-semibold flex items-center justify-between border-l-4 border-[#FF5500] shadow-md">
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

    @stack('scripts')
</body>
</html>
