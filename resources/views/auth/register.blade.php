<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Create your account — Nalaria</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-[#F8F8FA] text-black antialiased selection:bg-[#FF5500] selection:text-white flex items-center justify-center p-4 sm:p-6 lg:p-10">

    <!-- Outer Card: 2-Column Split Screen -->
    <div class="w-full max-w-5xl bg-white rounded-[32px] sm:rounded-[40px] shadow-2xl shadow-black/5 border border-neutral-200/80 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">
        
        <!-- Left Column: Lottie Animation Container -->
        <div class="lg:col-span-6 p-4 sm:p-6 lg:p-7 flex">
            <div class="w-full h-full min-h-[360px] sm:min-h-[440px] lg:min-h-[580px] rounded-[28px] sm:rounded-[36px] bg-[#EFECE6] flex items-center justify-center overflow-hidden relative border border-neutral-200/50 shadow-inner">
                <!-- Lottie Embed Frame -->
                <iframe 
                    src="https://lottie.host/embed/3e6045e1-9b8f-4c5e-ad43-e0dae0fef1b7/6DA0mznRLQ.lottie" 
                    class="w-full h-full border-0 absolute inset-0 pointer-events-auto"
                    title="Lottie Animation"
                    loading="lazy"
                    allowfullscreen>
                </iframe>
            </div>
        </div>

        <!-- Right Column: Registration Form Content -->
        <div class="lg:col-span-6 px-6 py-8 sm:px-10 sm:py-12 lg:px-12 flex flex-col justify-center">
            
            <!-- Brand Logo & Name -->
            <div class="flex items-center justify-center gap-2.5 mb-5">
                <x-logo class="w-7 h-7" fill="#FF5500" />
                <span class="text-xl font-extrabold tracking-tight text-black">Nalaria</span>
            </div>

            <!-- Title & Subtitle -->
            <div class="text-center space-y-1.5 mb-6">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">
                    Create your account
                </h1>
                <p class="text-xs sm:text-sm text-neutral-500 font-medium">
                    Start your contextual numeracy learning journey with Nalaria AI
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-2xl bg-black text-white text-xs font-semibold space-y-1">
                    @foreach ($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs sm:text-sm font-semibold text-neutral-700 mb-1">
                        Full Name
                    </label>
                    <input 
                        id="name" 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus 
                        autocomplete="name"
                        placeholder="Enter your full name" 
                        class="w-full px-5 py-3 rounded-full border border-neutral-200 text-sm focus:outline-none focus:border-[#FF5500] focus:ring-2 focus:ring-[#FF5500]/20 transition-all placeholder:text-neutral-400 font-medium bg-white"
                    >
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs sm:text-sm font-semibold text-neutral-700 mb-1">
                        Email Address
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="username"
                        placeholder="Enter your email" 
                        class="w-full px-5 py-3 rounded-full border border-neutral-200 text-sm focus:outline-none focus:border-[#FF5500] focus:ring-2 focus:ring-[#FF5500]/20 transition-all placeholder:text-neutral-400 font-medium bg-white"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs sm:text-sm font-semibold text-neutral-700 mb-1">
                        Password
                    </label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        placeholder="Create a strong password" 
                        class="w-full px-5 py-3 rounded-full border border-neutral-200 text-sm focus:outline-none focus:border-[#FF5500] focus:ring-2 focus:ring-[#FF5500]/20 transition-all placeholder:text-neutral-400 font-medium bg-white"
                    >
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-xs sm:text-sm font-semibold text-neutral-700 mb-1">
                        Confirm Password
                    </label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="Repeat your password" 
                        class="w-full px-5 py-3 rounded-full border border-neutral-200 text-sm focus:outline-none focus:border-[#FF5500] focus:ring-2 focus:ring-[#FF5500]/20 transition-all placeholder:text-neutral-400 font-medium bg-white"
                    >
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full py-3.5 px-6 rounded-full bg-[#FF5500] hover:bg-black text-white font-extrabold text-sm tracking-wide transition-all shadow-lg shadow-orange-600/25 active:scale-[0.99] cursor-pointer"
                    >
                        Create Account
                    </button>
                </div>
            </form>

            <!-- Footer Login Link -->
            <p class="text-center text-xs sm:text-sm text-neutral-600 mt-5 font-medium">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-bold text-[#FF5500] hover:text-black transition-colors underline-offset-2 hover:underline">
                    Log in
                </a>
            </p>

            <div class="text-center mt-3">
                <a href="{{ route('diagnostic.landing') }}" class="text-xs font-semibold text-neutral-400 hover:text-neutral-700 transition-colors">
                    ← Kembali ke Beranda
                </a>
            </div>

        </div>

    </div>

</body>
</html>
