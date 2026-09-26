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

    <!-- Lottie Web Player Engine -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js"></script>
    <!-- DotLottie Web Component -->
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #F8F8FA;
            color: #0A0A0A;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .auth-container {
            width: 100%;
            max-width: 1060px;
            background: #FFFFFF;
            border-radius: 36px;
            border: 1px solid #ECECEF;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 1024px) {
            .auth-container {
                flex-direction: row;
                min-height: 640px;
            }
        }

        .auth-visual-col {
            width: 100%;
            padding: 20px;
            display: flex;
            align-items: stretch;
        }

        @media (min-width: 1024px) {
            .auth-visual-col {
                width: 50%;
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        .auth-visual-box {
            width: 100%;
            height: 100%;
            min-height: 380px;
            border-radius: 28px;
            background-color: #EDE8E2;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        @media (min-width: 1024px) {
            .auth-visual-box {
                min-height: 580px;
            }
        }

        .auth-form-col {
            width: 100%;
            padding: 40px 24px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        @media (min-width: 640px) {
            .auth-form-col {
                padding: 48px 44px;
            }
        }

        @media (min-width: 1024px) {
            .auth-form-col {
                width: 50%;
                flex: 0 0 50%;
                max-width: 50%;
                padding: 48px 52px;
            }
        }

        .auth-form-inner {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }

        .brand-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            text-decoration: none;
        }

        .brand-title {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: #0A0A0A;
        }

        .auth-heading {
            font-size: 28px;
            font-weight: 800;
            color: #0A0A0A;
            letter-spacing: -0.03em;
            text-align: center;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            font-size: 13px;
            color: #6B7280;
            text-align: center;
            margin-bottom: 26px;
            font-weight: 500;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 16px;
            width: 100%;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 6px;
        }

        .pill-input {
            width: 100% !important;
            display: block !important;
            box-sizing: border-box !important;
            padding: 14px 22px !important;
            border-radius: 9999px !important;
            border: 1.5px solid #E5E7EB !important;
            background: #FFFFFF !important;
            font-size: 14px !important;
            font-family: inherit !important;
            color: #111827 !important;
            outline: none !important;
            transition: all 0.2s ease !important;
        }

        .pill-input:focus {
            border-color: #FF5500 !important;
            box-shadow: 0 0 0 4px rgba(255, 85, 0, 0.12) !important;
        }

        .pill-input::placeholder {
            color: #9CA3AF !important;
        }

        .btn-primary {
            width: 100% !important;
            display: block !important;
            box-sizing: border-box !important;
            padding: 15px 24px !important;
            border-radius: 9999px !important;
            background-color: #FF5500 !important;
            color: #FFFFFF !important;
            font-size: 14px !important;
            font-weight: 800 !important;
            text-align: center !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 10px 25px -5px rgba(255, 85, 0, 0.35) !important;
            font-family: inherit !important;
        }

        .btn-primary:hover {
            background-color: #0A0A0A !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3) !important;
            transform: translateY(-1px);
        }

        .btn-social {
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 12px !important;
            box-sizing: border-box !important;
            padding: 13px 20px !important;
            border-radius: 9999px !important;
            background-color: #F3F4F6 !important;
            color: #1F2937 !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            font-family: inherit !important;
            margin-bottom: 10px !important;
        }

        .btn-social:hover {
            background-color: #E5E7EB !important;
        }

        .divider {
            position: relative;
            text-align: center;
            margin: 22px 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background-color: #E5E7EB;
        }

        .divider span {
            position: relative;
            background-color: #FFFFFF;
            padding: 0 14px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9CA3AF;
            font-weight: 600;
        }

        .alert-error {
            background-color: #0A0A0A;
            color: #FFFFFF;
            padding: 12px 18px;
            border-radius: 18px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 16px;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        
        <!-- Left Column: Lottie Animation Container -->
        <div class="auth-visual-col">
            <div class="auth-visual-box">
                <!-- High-performance SVG animation container -->
                <div id="lottie-container" style="width: 100%; height: 100%; max-height: 540px; display: flex; align-items: center; justify-content: center;"></div>
            </div>
        </div>

        <!-- Right Column: Login Form Content -->
        <div class="auth-form-col">
            <div class="auth-form-inner">
                
                <!-- Brand Logo & Name -->
                <a href="{{ route('diagnostic.landing') }}" class="brand-header">
                    <x-logo class="w-6 h-6" fill="#FF5500" />
                    <span class="brand-title">Nalaria</span>
                </a>        

                <!-- Title & Subtitle -->
                <h1 class="auth-heading">Login to your account</h1>
                <p class="auth-subtitle">Welcome back! Enter your details to log in to your account</p>

                <!-- Session Status / Errors -->
                @if (session('status'))
                    <div style="background-color: #FFF3EB; border: 1px solid #FFD0B8; color: #FF5500; padding: 12px 16px; border-radius: 16px; font-size: 12px; font-weight: 600; margin-bottom: 16px; text-align: center;">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert-error">
                        @foreach ($errors->all() as $error)
                            <div>• {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="Enter your email" 
                            class="pill-input"
                        >
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="Enter your Password" 
                            class="pill-input"
                        >
                    </div>

                    <!-- Remember Login & Forgot Password -->
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 12px; margin-bottom: 22px;">
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; color: #4B5563; font-weight: 500;">
                            <input 
                                id="remember_me" 
                                type="checkbox" 
                                name="remember" 
                                style="width: 16px; height: 16px; border-radius: 4px; border: 1px solid #D1D5DB; accent-color: #FF5500; cursor: pointer;"
                            >
                            <span>Remember login</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="color: #FF5500; font-weight: 700; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary">
                        Login
                    </button>
                </form>

                <!-- Divider -->
                <div class="divider">
                    <span>Or continue with</span>
                </div>

                <!-- Social Sign In Buttons -->
                <div>
                    <button type="button" class="btn-social" onclick="alert('Fitur Sign in with Apple sedang disiapkan untuk integrasi OAuth.')">
                        <svg style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 170 170">
                            <path d="M150.37 130.25c-2.45 5.66-5.35 10.87-8.71 15.66-4.58 6.53-8.33 11.05-11.22 13.56-4.48 4.12-9.28 6.23-14.42 6.35-3.69 0-8.14-1.05-13.32-3.18-5.19-2.12-9.97-3.17-14.34-3.17-4.58 0-9.49 1.05-14.75 3.17-5.26 2.13-9.5 3.24-12.74 3.35-4.35.13-9.16-1.9-14.42-6.08-3.7-3.07-7.66-7.85-11.87-14.34-6.42-9.84-11.39-20.73-14.92-32.67-3.53-11.94-5.3-23.2-5.3-33.78 0-14.7 3.73-26.68 11.19-35.94 7.46-9.26 16.89-13.98 28.29-14.16 5.2 0 10.99 1.41 17.37 4.23 6.38 2.82 10.37 4.3 11.97 4.45 2.11-.26 6.38-1.78 12.82-4.56 6.44-2.78 12.01-4.07 16.71-3.87 13.9.7 24.8 5.68 32.7 14.93-11.59 7.02-17.27 16.65-17.04 28.89.23 9.4 3.72 17.29 10.47 23.68 6.75 6.39 14.91 10.21 24.48 11.46-2.03 6.07-4.58 12.19-7.66 18.36zM119.22 32.18c0-7.1 2.58-13.79 7.74-20.07 5.16-6.28 11.64-10.38 19.44-12.31.25 1.05.37 2.06.37 3.03 0 7.03-2.67 13.84-8.01 20.44-5.34 6.6-11.85 10.63-19.54 12.09z"/>
                        </svg>
                        <span>Sign in with Apple</span>
                    </button>

                    <button type="button" class="btn-social" onclick="alert('Fitur Sign in with Google sedang disiapkan untuk integrasi OAuth.')">
                        <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span>Sign in with Google</span>
                    </button>
                </div>

                <!-- Footer Create Account Link -->
                <p style="text-align: center; font-size: 13px; color: #4B5563; margin-top: 22px; font-weight: 500;">
                    New here? 
                    <a href="{{ route('register') }}" style="color: #FF5500; font-weight: 800; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                        Create account
                    </a>
                </p>

                <!-- Return to landing -->
                <div style="text-align: center; margin-top: 14px;">
                    <a href="{{ route('diagnostic.landing') }}" style="font-size: 12px; font-weight: 600; color: #9CA3AF; text-decoration: none;" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#9CA3AF'">
                        ← Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>

    </div>

    <!-- Script to render Lottie animation directly and smoothly -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('lottie-container');
            if (typeof bodymovin !== 'undefined' && container) {
                bodymovin.loadAnimation({
                    container: container,
                    path: '{{ asset("images/login-animation.json") }}',
                    renderer: 'svg',
                    loop: true,
                    autoplay: true
                });
            }
        });
    </script>

</body>
</html>
