<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login to your account — Nalaria</title>
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
        
        <!-- Left Column: Lottie Animation Container (Exact Reference Layout) -->
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

        <!-- Right Column: Login Form Content -->
        <div class="lg:col-span-6 px-6 py-8 sm:px-10 sm:py-12 lg:px-12 flex flex-col justify-center">
            
            <!-- Brand Logo & Name -->
            <div class="flex items-center justify-center gap-2.5 mb-6">
                <x-logo class="w-7 h-7" fill="#FF5500" />
                <span class="text-xl font-extrabold tracking-tight text-black">Nalaria</span>
            </div>

            <!-- Title & Subtitle -->
            <div class="text-center space-y-2 mb-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">
                    Login to your account
                </h1>
                <p class="text-xs sm:text-sm text-neutral-500 font-medium">
                    Welcome back! Enter your details to log in to your account
                </p>
            </div>

            <!-- Session Status / Errors -->
            @if (session('status'))
                <div class="mb-4 p-3 rounded-2xl bg-[#FF5500]/10 border border-[#FF5500]/20 text-xs font-semibold text-[#FF5500] text-center">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-2xl bg-black text-white text-xs font-semibold space-y-1">
                    @foreach ($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs sm:text-sm font-semibold text-neutral-700 mb-1.5">
                        Email
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder="Enter your email" 
                        class="w-full px-5 py-3.5 rounded-full border border-neutral-200 text-sm focus:outline-none focus:border-[#FF5500] focus:ring-2 focus:ring-[#FF5500]/20 transition-all placeholder:text-neutral-400 font-medium bg-white"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs sm:text-sm font-semibold text-neutral-700 mb-1.5">
                        Password
                    </label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        placeholder="Enter your Password" 
                        class="w-full px-5 py-3.5 rounded-full border border-neutral-200 text-sm focus:outline-none focus:border-[#FF5500] focus:ring-2 focus:ring-[#FF5500]/20 transition-all placeholder:text-neutral-400 font-medium bg-white"
                    >
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between pt-1 text-xs">
                    <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input 
                            id="remember_me" 
                            type="checkbox" 
                            name="remember"
                            class="w-4 h-4 rounded border-neutral-300 text-[#FF5500] focus:ring-[#FF5500] cursor-pointer"
                        >
                        <span class="text-neutral-600 font-medium">Remember login</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="font-bold text-[#FF5500] hover:text-black transition-colors">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full py-3.5 px-6 rounded-full bg-[#FF5500] hover:bg-black text-white font-extrabold text-sm tracking-wide transition-all shadow-lg shadow-orange-600/25 active:scale-[0.99] cursor-pointer"
                    >
                        Login
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="relative my-6 text-center">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-neutral-200"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-white px-3 text-neutral-400 font-semibold tracking-wider text-[11px]">
                        Or continue with
                    </span>
                </div>
            </div>

            <!-- Social Logins (Reference Mockup Style) -->
            <div class="space-y-2.5">
                <button 
                    type="button" 
                    onclick="alert('Fitur Sign in with Apple sedang disiapkan untuk integrasi OAuth.')"
                    class="w-full py-3 px-4 rounded-full bg-[#F3F4F6] hover:bg-neutral-200 text-neutral-800 font-bold text-xs sm:text-sm flex items-center justify-center gap-3 transition-colors cursor-pointer"
                >
                    <svg class="w-4 h-4 fill-current text-black" viewBox="0 0 170 170">
                        <path d="M150.37 130.25c-2.45 5.66-5.35 10.87-8.71 15.66-4.58 6.53-8.33 11.05-11.22 13.56-4.48 4.12-9.28 6.23-14.42 6.35-3.69 0-8.14-1.05-13.32-3.18-5.19-2.12-9.97-3.17-14.34-3.17-4.58 0-9.49 1.05-14.75 3.17-5.26 2.13-9.5 3.24-12.74 3.35-4.35.13-9.16-1.9-14.42-6.08-3.7-3.07-7.66-7.85-11.87-14.34-6.42-9.84-11.39-20.73-14.92-32.67-3.53-11.94-5.3-23.2-5.3-33.78 0-14.7 3.73-26.68 11.19-35.94 7.46-9.26 16.89-13.98 28.29-14.16 5.2 0 10.99 1.41 17.37 4.23 6.38 2.82 10.37 4.3 11.97 4.45 2.11-.26 6.38-1.78 12.82-4.56 6.44-2.78 12.01-4.07 16.71-3.87 13.9.7 24.8 5.68 32.7 14.93-11.59 7.02-17.27 16.65-17.04 28.89.23 9.4 3.72 17.29 10.47 23.68 6.75 6.39 14.91 10.21 24.48 11.46-2.03 6.07-4.58 12.19-7.66 18.36zM119.22 32.18c0-7.1 2.58-13.79 7.74-20.07 5.16-6.28 11.64-10.38 19.44-12.31.25 1.05.37 2.06.37 3.03 0 7.03-2.67 13.84-8.01 20.44-5.34 6.6-11.85 10.63-19.54 12.09z"/>
                    </svg>
                    <span>Sign in with Apple</span>
                </button>

                <button 
                    type="button" 
                    onclick="alert('Fitur Sign in with Google sedang disiapkan untuk integrasi OAuth.')"
                    class="w-full py-3 px-4 rounded-full bg-[#F3F4F6] hover:bg-neutral-200 text-neutral-800 font-bold text-xs sm:text-sm flex items-center justify-center gap-3 transition-colors cursor-pointer"
                >
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span>Sign in with Google</span>
                </button>
            </div>

            <!-- Footer Create Account Link -->
            <p class="text-center text-xs sm:text-sm text-neutral-600 mt-6 font-medium">
                New here? 
                <a href="{{ route('register') }}" class="font-bold text-[#FF5500] hover:text-black transition-colors underline-offset-2 hover:underline">
                    Create account
                </a>
            </p>

            <div class="text-center mt-4">
                <a href="{{ route('diagnostic.landing') }}" class="text-xs font-semibold text-neutral-400 hover:text-neutral-700 transition-colors">
                    ← Kembali ke Beranda
                </a>
            </div>

        </div>

    </div>

</body>
</html>
