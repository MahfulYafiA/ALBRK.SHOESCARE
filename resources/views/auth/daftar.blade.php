<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - ALBRK.SHOESCARE</title>

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
        <div class="mt-8 w-full max-w-xs space-y-3">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20"><i class="fa-solid fa-check text-xs text-white"></i></div>
                <span class="text-sm text-gray-300">Reservasi Online 24/7</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20"><i class="fa-solid fa-check text-xs text-white"></i></div>
                <span class="text-sm text-gray-300">Pantau Status Real-time</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20"><i class="fa-solid fa-check text-xs text-white"></i></div>
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
            <h1 class="font-display text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Buat Akun</h1>
            <p class="text-gray-500">Daftar untuk mulai reservasi treatment sepatu.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6 text-sm">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span class="font-semibold">Terdapat kesalahan:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-5" id="registerForm">
            @csrf

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                    placeholder="Masukkan nama lengkap"
                    class="input-modern w-full px-5 py-4 rounded-xl text-sm font-medium text-gray-700 placeholder:text-gray-400">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">No. WhatsApp</label>
                <input type="text" name="no_telp" value="{{ old('no_telp') }}" required
                    placeholder="08xxxxxxxxxx"
                    class="input-modern w-full px-5 py-4 rounded-xl text-sm font-medium text-gray-700 placeholder:text-gray-400">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    placeholder="nama@email.com"
                    class="input-modern w-full px-5 py-4 rounded-xl text-sm font-medium text-gray-700 placeholder:text-gray-400">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Password</label>
                    <input type="password" name="password" required minlength="8"
                        placeholder="Min. 8 karakter"
                        class="input-modern w-full px-5 py-4 rounded-xl text-sm font-medium text-gray-700 placeholder:text-gray-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Konfirmasi</label>
                    <input type="password" name="password_confirmation" required
                        placeholder="Ulangi password"
                        class="input-modern w-full px-5 py-4 rounded-xl text-sm font-medium text-gray-700 placeholder:text-gray-400">
                </div>
            </div>

            <button type="submit" id="submitBtn"
                class="btn-gradient w-full text-white py-4 rounded-xl font-semibold text-sm tracking-wide mt-2">
                <span id="btnText">Daftar Sekarang</span>
                <span id="btnLoading" class="hidden">
                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>Memproses...
                </span>
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-8">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-gray-900 hover:text-black transition-colors">Masuk di sini</a>
        </p>

    </div>

    <script>
        const form = document.getElementById('registerForm');
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
