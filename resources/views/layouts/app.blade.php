<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nalaria — AI Diagnostik Numerasi Adaptif | Hackathon SEMESTA 8')</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --font-sans: 'Plus Jakarta Sans', system-ui, sans-serif;
            --color-bg: #F8FAFC;
            --color-surface: #FFFFFF;
            --color-text-main: #0F172A;
            --color-text-muted: #64748B;
            --color-primary: #7C3AED;
            --color-primary-dark: #1E1548;
            --color-primary-light: #EDE9FE;
            --color-border: #E2E8F0;
            --radius-md: 12px;
            --radius-lg: 18px;
            --radius-xl: 24px;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--color-bg);
            color: var(--color-text-main);
            -webkit-font-smoothing: antialiased;
        }

        .bento-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: var(--radius-lg);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .bento-card:hover {
            border-color: #CBD5E1;
            box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.06);
        }

        .hero-banner {
            background: linear-gradient(135deg, #1E1548 0%, #2D1B69 50%, #4C1D95 100%);
            border-radius: var(--radius-xl);
            position: relative;
            overflow: hidden;
        }

        .hero-pattern {
            position: absolute;
            inset: 0;
            opacity: 0.12;
            background-image: radial-gradient(#FFFFFF 1.5px, transparent 1.5px);
            background-size: 24px 24px;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-slate-50 text-slate-900 selection:bg-purple-100 selection:text-purple-900">

    <!-- Top Navigation Bar (SOSH Inspired) -->
    <header class="sticky top-0 z-40 bg-white/85 backdrop-blur-md border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('diagnostic.landing') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-purple-700 text-white flex items-center justify-center font-bold shadow-md shadow-purple-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-extrabold tracking-tight text-slate-900">NALARIA</span>
                        <span class="text-xs uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">AI Agent</span>
                    </div>
                    <p class="text-xs text-slate-500 font-medium hidden sm:block">Diagnostik Numerasi Adaptif — Hackathon SEMESTA 8</p>
                </div>
            </a>

            <!-- Navigation Links & Action Button -->
            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center gap-2 text-xs font-semibold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Tema: Sustainable Future with AI</span>
                </div>

                <a href="{{ route('diagnostic.quiz') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-purple-700 hover:bg-purple-800 text-white text-sm font-semibold shadow-sm shadow-purple-700/30 transition-all hover:shadow-md active:scale-95">
                    <span>Mulai Asesmen</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Flash Alerts -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content Container -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Minimal Modern Footer -->
    <footer class="bg-white border-t border-slate-200 py-8 mt-16 text-slate-500 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-800">NALARIA AI</span>
                <span>• Solusi Diagnostik Kognitif Penalaran Numerasi</span>
            </div>
            <div class="text-xs text-slate-400">
                Hackathon SEMESTA 8 by SEVIMA © 2026. Built with Laravel 13 & AI Cognitive Profiling.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
