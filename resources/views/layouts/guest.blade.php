<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Nalaria') }} — Autentikasi Siswa</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#F6F6F8] text-black font-sans antialiased selection:bg-[#FF5500] selection:text-white flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full sm:max-w-md space-y-6">
            <div class="text-center space-y-3">
                <a href="{{ route('diagnostic.landing') }}" class="inline-flex items-center gap-3 group">
                    <x-logo class="w-12 h-12 group-hover:scale-105 transition-transform" fill="#FF5500" />
                    <span class="text-3xl font-black tracking-tight text-black">Nalaria</span>
                </a>
                <p class="text-xs font-semibold text-neutral-500">
                    Platform AI Diagnostik Literasi-Numerasi Kontekstual
                </p>
            </div>

            <div class="bg-white p-8 sm:p-10 rounded-[28px] border border-neutral-200/80 shadow-xl shadow-black/5">
                {{ $slot }}
            </div>

            <div class="text-center text-xs text-neutral-500">
                <a href="{{ route('diagnostic.landing') }}" class="font-bold hover:text-[#FF5500] transition-colors">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </body>
</html>
