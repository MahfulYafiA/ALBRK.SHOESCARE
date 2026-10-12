<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - ALBRK.SHOESCARE</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#111111',
                        secondary: '#525252',
                        accent: '#737373',
                        dark: '#111111',
                    },
                    fontFamily: {
                        display: ['Plus Jakarta Sans', 'sans-serif'],
                        body: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }

        .btn-gradient {
            background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            transform: translateY(-2px);
            box-shadow: 0 20px 40px -14px rgba(0, 0, 0, 0.5);
        }

        .input-modern {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            transition: all 0.3s ease;
        }
        .input-modern:focus {
            background: #ffffff;
            border-color: #1a1a1a;
            box-shadow: 0 0 0 4px rgba(26, 26, 26, 0.1);
            outline: none;
        }
    </style>
</head>
<body class="min-h-screen bg-white flex">

    {{-- Left Side: Logo Full --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gray-900 flex flex-col items-center justify-center px-12 py-16 text-white min-h-screen">

        {{-- Logo --}}
        <img src="/images/Logo.jpeg" alt="ALBRK Logo" class="w-64 h-auto mb-8 rounded-2xl shadow-2xl">

        {{-- Tagline --}}
        <h2 class="font-display text-3xl font-bold text-center leading-tight mb-4">
            Rawat Sepatu,<br>
            <span class="italic font-semibold text-gray-400">Tingkatkan Gaya.</span>
        </h2>

        <p class="text-gray-400 text-center text-sm max-w-sm mt-4">
            Akses dashboard untuk reservasi online dan pantau proses treatment sepatu Anda secara real-time.
        </p>

        {{-- Features --}}
        <div class="space-y-3 mt-8 w-full max-w-xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-check text-xs text-white"></i>
                </div>
                <span class="text-sm text-gray-300">Reservasi Online 24/7</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-check text-xs text-white"></i>
                </div>
                <span class="text-sm text-gray-300">Pantau Status Real-time</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-check text-xs text-white"></i>
                </div>
                <span class="text-sm text-gray-300">Treatment Premium Quality</span>
            </div>
        </div>
    </div>

    {{-- Right Side: Form --}}
    <div class="w-full lg:w-1/2 bg-white flex flex-col justify-center p-8 sm:p-12 lg:p-16 min-h-screen">

        {{-- Mobile Logo --}}
        <div class="lg:hidden text-center mb-8">
            <img src="/images/Logo.jpeg" alt="ALBRK Logo" class="w-32 h-auto mx-auto mb-4 rounded-xl">
            <h1 class="font-display text-2xl font-bold text-gray-900">ALBRK SHOECARE</h1>
        </div>

        <div class="mb-8">
            <h1 class="font-display text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Selamat Datang!</h1>
            <p class="text-gray-500">Masuk untuk mengelola reservasi sepatu Anda.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6 text-sm font-medium">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5" id="loginForm">
            @csrf

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    placeholder="nama@email.com"
                    class="input-modern w-full px-5 py-4 rounded-xl text-sm font-medium text-gray-700 placeholder:text-gray-400">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Password</label>
                <div class="relative">
                    <input type="password" name="password" required id="password"
                        placeholder="Masukkan password"
                        class="input-modern w-full px-5 py-4 rounded-xl text-sm font-medium text-gray-700 placeholder:text-gray-400 pr-12">
                    <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">
                    Lupa password?
                </a>
            </div>

            <button type="submit" id="submitBtn"
                class="btn-gradient w-full text-white py-4 rounded-xl font-semibold text-sm tracking-wide mt-2">
                <span id="btnText">Masuk Sekarang</span>
                <span id="btnLoading" class="hidden">
                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>Memproses...
                </span>
            </button>
        </form>

        <div class="relative flex items-center my-6">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="flex-shrink mx-4 text-xs font-semibold uppercase tracking-wider text-gray-400">atau</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <a href="{{ route('login.google') }}"
            class="w-full bg-white border-2 border-gray-200 text-gray-700 py-4 rounded-xl font-semibold text-sm hover:border-gray-400 hover:text-gray-900 transition-all flex items-center justify-center gap-3 shadow-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Masuk dengan Google
        </a>

        <p class="text-center text-sm text-gray-500 mt-8">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-gray-900 hover:text-black transition-colors">Daftar sekarang</a>
        </p>

    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');

            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        const form = document.getElementById('loginForm');
        const btn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');

        form.addEventListener('submit', function() {
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            btn.disabled = true;
            btn.classList.add('opacity-75');
        });
    </script>
</body>
</html>
