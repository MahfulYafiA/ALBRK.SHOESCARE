<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Sandi - ALBRK.SHOESCARE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }

        .btn-gradient {
            background: linear-gradient(135deg, #17303d 0%, #244f61 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #102630 0%, #17303d 100%);
            transform: translateY(-2px);
            box-shadow: 0 20px 40px -14px rgba(23, 23, 23, 0.42);
        }

        .input-modern {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            transition: all 0.3s ease;
        }
        .input-modern:focus {
            background: #ffffff;
            border-color: #3e7895;
            box-shadow: 0 0 0 4px rgba(62, 120, 149, 0.12);
            outline: none;
        }

        .noise {
            position: fixed; inset: 0;
            pointer-events: none; z-index: 9999; opacity: 0.015;
            background: url('data:image/svg+xml;utf8,%3Csvg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"%3E%3Cfilter id="noiseFilter"%3E%3CfeTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch"/%3E%3C/filter%3E%3Crect width="100%25" height="100%25" filter="url(%23noiseFilter)"/%3E%3C/svg%3E');
        }
    </style>
</head>
<body class="relative flex min-h-screen items-center justify-center overflow-hidden bg-[#f3f7fa] p-4 text-[#17303d]">
    <div class="pointer-events-none absolute -left-28 -top-32 h-96 w-96 rounded-full bg-sky-100 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-40 -right-28 h-[30rem] w-[30rem] rounded-full bg-cyan-100/70 blur-3xl"></div>
    <div class="noise"></div>

    <main class="relative w-full max-w-md overflow-hidden rounded-3xl border border-white/80 bg-white p-7 shadow-[0_24px_70px_rgba(23,48,61,.12)] sm:p-10">
        <a href="{{ route('landing') }}" class="mb-8 flex items-center justify-center gap-2.5" aria-label="ALBRK ShoesCare, beranda">
            <span class="grid h-10 w-10 place-items-center overflow-hidden rounded-xl bg-[#17303d] shadow-md"><img src="{{ asset('images/Logo.jpeg') }}" alt="" class="h-full w-full object-contain"></span>
            <span class="font-display text-lg font-extrabold tracking-tight text-[#17303d]">ALBRK<span class="text-slate-400">.SHOESCARE</span></span>
        </a>

        <div class="mb-7 text-center">
            <h1 class="text-2xl font-extrabold tracking-tight text-[#17303d]">Lupa kata sandi?</h1>
            <p class="mx-auto mt-2 max-w-sm text-sm leading-relaxed text-slate-500">Masukkan email terdaftar. Kami akan mengirim tautan untuk mengatur ulang kata sandi Anda.</p>
        </div>

        @if(session('status'))
            <div role="status" class="mb-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-left text-xs font-semibold leading-relaxed text-emerald-800"><i class="fa-solid fa-circle-check mt-0.5"></i><span>{{ session('status') }}</span></div>
        @endif
        @if($errors->any())
            <div role="alert" class="mb-5 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-left text-xs font-semibold leading-relaxed text-rose-800"><i class="fa-solid fa-circle-exclamation mt-0.5"></i><span>{{ $errors->first() }}</span></div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="mb-2 block text-xs font-bold text-slate-700">Alamat email terdaftar</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nama@email.com" class="input-modern h-12 w-full rounded-xl px-4 text-sm font-medium text-slate-700 placeholder:text-slate-400">
            </div>
            <button type="submit" class="btn-gradient flex h-12 w-full items-center justify-center gap-2 rounded-xl text-sm font-bold text-white shadow-lg shadow-[#17303d]/15"><span>Kirim tautan reset</span><i class="fa-solid fa-arrow-right text-xs"></i></button>
        </form>

    </main>
</body>
</html>
