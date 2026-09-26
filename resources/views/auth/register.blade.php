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

    <!-- Lottie Web Player Engine -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js"></script>

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
                padding: 44px 40px;
            }
        }

        @media (min-width: 1024px) {
            .auth-form-col {
                width: 50%;
                flex: 0 0 50%;
                max-width: 50%;
                padding: 44px 48px;
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
            margin-bottom: 18px;
            text-decoration: none;
        }

        .brand-header svg {
            width: 28px;
            height: 28px;
            max-width: 28px;
            max-height: 28px;
            flex-shrink: 0;
            display: inline-block;
        }

        .brand-title {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: #0A0A0A;
        }

        .auth-heading {
            font-size: 26px;
            font-weight: 800;
            color: #0A0A0A;
            letter-spacing: -0.03em;
            text-align: center;
            margin-bottom: 6px;
        }

        .auth-subtitle {
            font-size: 13px;
            color: #6B7280;
            text-align: center;
            margin-bottom: 22px;
            font-weight: 500;
            line-height: 1.45;
        }

        .form-group {
            margin-bottom: 14px;
            width: 100%;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 5px;
        }

        .pill-input {
            width: 100% !important;
            display: block !important;
            box-sizing: border-box !important;
            padding: 13px 20px !important;
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
            padding: 14px 24px !important;
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
            margin-top: 6px;
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
            padding: 12px 20px !important;
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
            margin: 18px 0;
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

        <!-- Right Column: Registration Form Content -->
        <div class="auth-form-col">
            <div class="auth-form-inner">
                
                <!-- Brand Logo & Name -->
                <a href="{{ route('diagnostic.landing') }}" class="brand-header">
                    <x-logo style="width: 28px; height: 28px;" fill="#FF5500" />
                    <span class="brand-title">Nalaria</span>
                </a>

                <!-- Title & Subtitle -->
                <h1 class="auth-heading">Create your account</h1>
                <p class="auth-subtitle">Start your contextual numeracy learning journey with Nalaria AI</p>

                @if ($errors->any())
                    <div class="alert-error">
                        @foreach ($errors->all() as $error)
                            <div>• {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input 
                            id="name" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            autocomplete="name"
                            placeholder="Enter your full name" 
                            class="pill-input"
                        >
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="username"
                            placeholder="Enter your email" 
                            class="pill-input"
                        >
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            placeholder="Create a password" 
                            class="pill-input"
                        >
                    </div>

                    <!-- Password Confirmation -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            placeholder="Confirm your password" 
                            class="pill-input"
                        >
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary">
                        Create Account
                    </button>
                </form>

                <!-- Divider -->
                <div class="divider">
                    <span>Or register with</span>
                </div>

                <!-- Social Sign In Buttons -->
                <div>
                    <button type="button" class="btn-social" onclick="alert('Fitur Register with Google sedang disiapkan untuk integrasi OAuth.')">
                        <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span>Sign up with Google</span>
                    </button>
                </div>

                <!-- Footer Login Link -->
                <p style="text-align: center; font-size: 13px; color: #4B5563; margin-top: 18px; font-weight: 500;">
                    Already have an account? 
                    <a href="{{ route('login') }}" style="color: #FF5500; font-weight: 800; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                        Log in
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
