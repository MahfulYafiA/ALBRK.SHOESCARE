<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALBRK.SHOESCARE - Perawatan Sepatu Premium</title>
    <meta name="description" content="ALBRK.SHOESCARE - Solusi perawatan sepatu premium di Kabupaten Madiun. Fast Clean, Deep Clean, Unyellowing, dan treatment lainnya.">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#111111',
                        secondary: '#525252',
                        accent: '#737373',
                        dark: '#111111',
                        surface: '#f5f5f5',
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
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        *::-webkit-scrollbar {
            width: 0;
            height: 0;
            display: none;
        }
        html, body {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            scroll-padding-top: 5rem;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            width: 0;
            height: 0;
            display: none;
        }
        body { margin: 0; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }

        .gradient-text {
            color: #111111;
            background: none;
            -webkit-text-fill-color: currentColor;
        }

        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(23, 23, 23, 0.08);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 30px rgba(82, 82, 82, 0.16); }
            50% { box-shadow: 0 0 56px rgba(82, 82, 82, 0.26); }
        }
        .pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }

        .card-hover {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 28px 52px -18px rgba(15, 23, 42, 0.22);
        }

        .noise {
            position: fixed; inset: 0;
            pointer-events: none; z-index: 9999; opacity: 0.02;
            background: url('data:image/svg+xml;utf8,%3Csvg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"%3E%3Cfilter id="noiseFilter"%3E%3CfeTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch"/%3E%3C/filter%3E%3Crect width="100%25" height="100%25" filter="url(%23noiseFilter)"/%3E%3C/svg%3E');
        }

        [data-aos] { max-width: 100%; }

        .hero-bg {
            background:
                linear-gradient(180deg, rgba(229, 229, 229, 0.96) 0%, rgba(212, 212, 212, 0.98) 100%);
        }

        @keyframes marquee-scroll {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }
        .marquee-track {
            animation: marquee-scroll 20s linear infinite;
            width: max-content;
        }
        .marquee-track > span {
            flex: 0 0 auto;
        }

        .watermark-text {
            background: linear-gradient(135deg, #111111 0%, #525252 50%, #111111 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            -webkit-text-stroke: 2px rgba(17, 17, 17, 0.18);
            filter: drop-shadow(0 4px 24px rgba(17, 17, 17, 0.08));
        }

        .watermark-mask {
            mask-image: linear-gradient(90deg, transparent 0%, black 12%, black 88%, transparent 100%);
            -webkit-mask-image: linear-gradient(90deg, transparent 0%, black 12%, black 88%, transparent 100%);
        }

        /* Teks dekoratif bergerak diagonal dari kiri-bawah ke kanan-atas. */
        .diagonal-watermark {
            transform: translateY(-48px) rotate(-18deg) scale(1.35);
            transform-origin: center;
        }

        @media (max-width: 640px) {
            .diagonal-watermark { transform: translateY(-28px) rotate(-16deg) scale(1.7); }
        }

        .hero-photo-bg {
            background-image:
                linear-gradient(180deg, rgba(0, 0, 0, 0.18) 0%, rgba(0, 0, 0, 0.22) 58%, rgba(0, 0, 0, 0.12) 100%),
                url('{{ $heroPath }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .hero-frame {
            background: transparent;
            border: 0;
            box-shadow: none;
        }

        .hero-kicker {
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 16px 40px -24px rgba(15, 23, 42, 0.52);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .hero-title { text-wrap: balance; }

        .hero-title-gradient {
            color: #050505;
            background: linear-gradient(135deg, #030303 0%, #1a1a1a 42%, #4a4a4a 68%, #0b0b0b 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            -webkit-text-stroke: 1px rgba(255, 255, 255, 0.38);
            filter: drop-shadow(0 3px 0 rgba(255, 255, 255, 0.52)) drop-shadow(0 12px 28px rgba(255, 255, 255, 0.34));
        }

        .hero-copy-panel { background: rgba(0, 0, 0, 0.18); border: 1px solid rgba(255, 255, 255, 0.18); box-shadow: none; }

        .hero-stat { background: rgba(0, 0, 0, 0.14); border: 1px solid rgba(255, 255, 255, 0.16); backdrop-filter: none; -webkit-backdrop-filter: none; }

        .hero-line-pattern {
            background-image: linear-gradient(90deg, rgba(23, 23, 23, 0.08) 1px, transparent 1px);
            background-size: 84px 100%;
            mask-image: linear-gradient(90deg, transparent 0%, black 18%, black 82%, transparent 100%);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #111111 0%, #525252 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #000000 0%, #262626 100%);
            transform: translateY(-2px);
            box-shadow: 0 20px 40px -14px rgba(23, 23, 23, 0.42);
        }

        .landing-visual {
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.88) 0%, rgba(245, 245, 245, 0.92) 48%, rgba(229, 229, 229, 0.86) 100%);
        }

        .mono-grid {
            background-image:
                linear-gradient(rgba(23, 23, 23, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(23, 23, 23, 0.06) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        ::selection { background: #111111; color: white; }
    </style>
</head>
<body class="antialiased bg-surface text-dark overflow-x-hidden">

    <div class="noise"></div>

    <nav class="fixed w-full z-50 glass border-b border-gray-200 transition-all duration-300" data-aos="fade-down" data-aos-duration="800">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 lg:h-20">

                <a href="/" class="flex items-center gap-3 group">
                    <span class="font-display font-bold text-xl lg:text-2xl tracking-tight text-neutral-950">
                        ALBRK<span class="text-neutral-500">.SHOESCARE</span>
                    </span>
                </a>

                <div class="hidden lg:flex items-center gap-10">
                    <a href="#beranda" class="nav-link text-sm font-medium text-gray-500 hover:text-neutral-700 transition-colors">Beranda</a>
                    <a href="#layanan" class="nav-link text-sm font-medium text-gray-500 hover:text-neutral-700 transition-colors">Layanan</a>
                    <a href="#tentang" class="nav-link text-sm font-medium text-gray-500 hover:text-neutral-700 transition-colors">Tentang</a>
                    <a href="#kontak" class="nav-link text-sm font-medium text-gray-500 hover:text-neutral-700 transition-colors">Kontak</a>
                    <a href="{{ url('/dashboard') }}" class="nav-link text-sm font-medium text-gray-500 hover:text-neutral-700 transition-colors">Dashboard</a>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <div class="relative hidden sm:block">
                            <button type="button" onclick="document.getElementById('landingUserMenu').classList.toggle('hidden')" aria-label="Menu akun" aria-haspopup="true" class="flex items-center gap-2.5 rounded-full border border-gray-200 bg-gray-100 px-2 py-1.5 pr-3 shadow-sm transition-all hover:border-gray-300 hover:shadow-md">
                                <div class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-full border border-white bg-neutral-900">
                                    @if(auth()->user()->foto_profil)
                                        <img src="{{ route('profil.foto') }}" class="h-full w-full rounded-full object-cover" alt="Foto profil" onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden')">
                                        <span class="hidden text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->nama ?? 'PL', 0, 2)) }}</span>
                                    @else
                                        <span class="text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->nama ?? 'PL', 0, 2)) }}</span>
                                    @endif
                                </div>
                                <span class="flex flex-col items-start leading-none">
                                    <span class="text-[10px] font-extrabold uppercase tracking-[0.08em] text-gray-800">{{ explode(' ', auth()->user()->nama ?? 'Pelanggan')[0] }}</span>
                                    <span class="mt-1 text-[7px] font-bold uppercase tracking-[0.08em] text-gray-400">Pelanggan</span>
                                </span>
                                <i class="fa-solid fa-chevron-down ml-1 text-[10px] text-gray-400"></i>
                            </button>
                            <div id="landingUserMenu" class="hidden absolute right-0 top-full z-50 mt-3 w-56 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl">
                                <a href="{{ route('profil.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-50"><i class="fa-solid fa-user-gear w-4 text-center"></i>Kelola Profil</a>
                                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="flex w-full items-center gap-3 border-t border-gray-100 px-4 py-3 text-left text-sm font-semibold text-red-600 transition-colors hover:bg-red-50"><i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>Keluar</button></form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-500 hover:text-neutral-700 transition-colors hidden sm:block">Masuk</a>
                        <a href="{{ route('register') }}" class="btn-gradient text-white px-6 py-2.5 rounded-full text-sm font-semibold">
                            Daftar
                        </a>
                    @endauth

                    <button onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-neutral-100 hover:bg-neutral-200 transition-colors">
                        <i class="fa-solid fa-bars text-gray-500"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobileMenu" class="hidden lg:hidden bg-white border-t border-gray-100 absolute top-full left-0 right-0 shadow-xl">
            <div class="px-4 py-4 space-y-1">
                <a href="#beranda" class="block px-4 py-3 rounded-xl text-sm font-medium text-gray-500 hover:bg-neutral-50 hover:text-neutral-700 transition-colors">Beranda</a>
                <a href="#layanan" class="block px-4 py-3 rounded-xl text-sm font-medium text-gray-500 hover:bg-neutral-50 hover:text-neutral-700 transition-colors">Layanan</a>
                <a href="#tentang" class="block px-4 py-3 rounded-xl text-sm font-medium text-gray-500 hover:bg-neutral-50 hover:text-neutral-700 transition-colors">Tentang</a>
                <a href="#kontak" class="block px-4 py-3 rounded-xl text-sm font-medium text-gray-500 hover:bg-neutral-50 hover:text-neutral-700 transition-colors">Kontak</a>
                <a href="{{ url('/dashboard') }}" class="block px-4 py-3 rounded-xl text-sm font-medium text-gray-500 hover:bg-neutral-50 hover:text-neutral-700 transition-colors">Dashboard</a>
                @auth
                    <a href="{{ route('profil.index') }}" class="block px-4 py-3 rounded-xl text-sm font-medium text-gray-500 hover:bg-neutral-50 hover:text-neutral-700 transition-colors">Kelola Profil</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="block w-full rounded-xl px-4 py-3 text-left text-sm font-medium text-red-600 transition-colors hover:bg-red-50">Keluar</button></form>
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section id="beranda" class="relative min-h-[100svh] flex items-center hero-bg hero-photo-bg overflow-hidden pt-20">
        <div class="absolute inset-x-0 top-20 h-px bg-neutral-950/10"></div>
        <div class="hero-line-pattern absolute inset-x-0 bottom-0 h-28 opacity-0"></div>
        <div class="w-full px-4 sm:px-6 lg:px-8 py-10 lg:py-12 relative z-10">
            <div class="max-w-5xl mx-auto text-center hero-frame px-5 sm:px-10 lg:px-16 py-10 sm:py-14 lg:py-16">
                {{-- Text Content --}}
                <div data-aos="fade-up" data-aos-duration="1000">
                    <div class="hero-kicker inline-flex items-center gap-2 border border-neutral-200 px-4 py-2 rounded-full text-sm font-semibold text-neutral-700 mb-8">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-neutral-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-neutral-900"></span>
                        </span>
                        Solusi Perawatan Sepatu Premium
                    </div>

                    <h1 class="hero-title font-display text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-black tracking-tight leading-[0.88] mb-8">
                        <span class="block hero-title-gradient" data-aos="fade-up" data-aos-delay="100">Rawat</span>
                        <span class="block hero-title-gradient italic" data-aos="fade-up" data-aos-delay="200">Tanpa Harus</span>
                        <span class="block hero-title-gradient italic" data-aos="fade-up" data-aos-delay="300">Antri.</span>
                    </h1>

                    <div class="hero-copy-panel rounded-3xl max-w-2xl mx-auto px-5 sm:px-8 py-6 mb-8" data-aos="fade-up" data-aos-delay="400">
                        <p class="text-white text-base lg:text-lg leading-relaxed drop-shadow-[0_2px_10px_rgba(0,0,0,0.45)]">
                            Tingkatkan performa dan estetika sepatu Anda dengan treatment premium. Reservasi online, pantau real-time, hasil memuaskan.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="500">
                        <a href="{{ route('reservasi.create') }}" class="btn-gradient text-white px-8 py-4 rounded-full text-sm font-semibold inline-flex items-center justify-center gap-3">
                            Buat Reservasi
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a href="#layanan" class="bg-white border border-neutral-200 text-gray-700 px-8 py-4 rounded-full text-sm font-semibold hover:border-neutral-400 hover:text-gray-900 transition-all inline-flex items-center justify-center gap-3">
                            <i class="fa-solid fa-play text-xs"></i>
                            Lihat Layanan
                        </a>
                    </div>

                    <div class="mt-10 grid grid-cols-3 gap-3 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="650">
                        <div class="hero-stat rounded-2xl px-3 py-4">
                            <p class="text-xl sm:text-2xl font-black text-white">24/7</p>
                            <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-white/75">Reservasi</p>
                        </div>
                        <div class="hero-stat rounded-2xl px-3 py-4">
                            <p class="text-xl sm:text-2xl font-black text-white">Real-time</p>
                            <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-white/75">Status</p>
                        </div>
                        <div class="hero-stat rounded-2xl px-3 py-4">
                            <p class="text-xl sm:text-2xl font-black text-white">Premium</p>
                            <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-white/75">Treatment</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- LAYANAN SECTION --}}
    <section id="layanan" class="flex items-center py-5 hero-bg relative overflow-hidden lg:min-h-[calc(100svh-5rem)]">
        <div class="absolute inset-0 flex flex-col items-center justify-center gap-5 pointer-events-none select-none z-0 opacity-20 watermark-mask diagonal-watermark">
            <div class="flex whitespace-nowrap marquee-track">
                <span class="font-display font-black text-[8rem] sm:text-[11rem] lg:text-[15rem] tracking-tighter leading-none watermark-text px-12">ALBRK SHOESCARE</span>
                <span class="font-display font-black text-[8rem] sm:text-[11rem] lg:text-[15rem] tracking-tighter leading-none watermark-text px-12">ALBRK SHOESCARE</span>
                <span class="font-display font-black text-[8rem] sm:text-[11rem] lg:text-[15rem] tracking-tighter leading-none watermark-text px-12">ALBRK SHOESCARE</span>
                <span class="font-display font-black text-[8rem] sm:text-[11rem] lg:text-[15rem] tracking-tighter leading-none watermark-text px-12">ALBRK SHOESCARE</span>
            </div>
            <div class="flex whitespace-nowrap marquee-track" style="animation-direction: reverse; animation-duration: 20s;">
                <span class="font-display font-black text-[8rem] sm:text-[11rem] lg:text-[15rem] tracking-tighter leading-none watermark-text px-12">ALBRK SHOESCARE</span>
                <span class="font-display font-black text-[8rem] sm:text-[11rem] lg:text-[15rem] tracking-tighter leading-none watermark-text px-12">ALBRK SHOESCARE</span>
                <span class="font-display font-black text-[8rem] sm:text-[11rem] lg:text-[15rem] tracking-tighter leading-none watermark-text px-12">ALBRK SHOESCARE</span>
                <span class="font-display font-black text-[8rem] sm:text-[11rem] lg:text-[15rem] tracking-tighter leading-none watermark-text px-12">ALBRK SHOESCARE</span>
            </div>
            <div class="flex whitespace-nowrap marquee-track" style="animation-duration: 20s;">
                <span class="font-display font-black text-[8rem] sm:text-[11rem] lg:text-[15rem] tracking-tighter leading-none watermark-text px-12">ALBRK SHOESCARE</span>
                <span class="font-display font-black text-[8rem] sm:text-[11rem] lg:text-[15rem] tracking-tighter leading-none watermark-text px-12">ALBRK SHOESCARE</span>
                <span class="font-display font-black text-[8rem] sm:text-[11rem] lg:text-[15rem] tracking-tighter leading-none watermark-text px-12">ALBRK SHOESCARE</span>
                <span class="font-display font-black text-[8rem] sm:text-[11rem] lg:text-[15rem] tracking-tighter leading-none watermark-text px-12">ALBRK SHOESCARE</span>
            </div>
        </div>
        <div class="w-full px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="inline-block bg-neutral-100 text-neutral-600 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider mb-4">Layanan Kami</span>
                <h2 class="font-display text-4xl lg:text-5xl xl:text-6xl font-bold tracking-tight text-gray-900 mb-4">
                    Treatment <span class="gradient-text">Terbaik</span>
                </h2>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto">
                    Pilihan layanan lengkap untuk segala jenis dan kondisi sepatu Anda
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($layanans as $index => $layanan)
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg shadow-gray-200/50 card-hover border border-gray-100" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="aspect-video overflow-hidden landing-visual mono-grid flex items-center justify-center border-b border-neutral-100 relative bg-neutral-100">
                            {{-- Prioritaskan foto yang diunggah dari admin. Gambar bawaan hanya sebagai fallback. --}}
                            @if($layanan->landing_image)
                                <img src="{{ $layanan->landing_image }}" alt="{{ $layanan->nama_layanan }}" class="w-full h-full object-contain">
                            @else
                                <div class="w-24 h-24 rounded-2xl bg-neutral-950 text-white flex items-center justify-center shadow-2xl shadow-neutral-300/70">
                                    <i class="fa-solid {{ $layanan->landing_icon }} text-4xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-8">
                            <h3 class="font-display text-xl font-bold text-gray-900 mb-3">{{ $layanan->nama_layanan }}</h3>
                            <p class="text-gray-500 text-sm mb-6 line-clamp-2">{{ $layanan->deskripsi }}</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xs text-gray-400 uppercase tracking-wider">Harga</span>
                                    <p class="text-2xl font-bold text-gray-900">Rp {{ $layanan->landing_price }}</p>
                                </div>
                                <a href="{{ route('reservasi.create') }}" class="bg-neutral-100 hover:bg-neutral-950 text-neutral-900 hover:text-white px-5 py-2.5 rounded-full text-sm font-semibold transition-all">
                                    Pesan
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12" data-aos="fade-up">
                <a href="{{ route('reservasi.create') }}" class="btn-gradient text-white px-8 py-4 rounded-full text-sm font-semibold inline-flex items-center gap-3">
                    <i class="fa-solid fa-calendar-plus"></i>
                    Reservasi Sekarang
                </a>
            </div>
        </div>
    </section>

    {{-- TENTANG SECTION --}}
    <section id="tentang" class="min-h-[calc(100svh-5rem)] bg-neutral-200 flex items-center py-5">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="relative" data-aos="fade-right">
                    <div class="relative rounded-2xl overflow-hidden border border-neutral-200 shadow-2xl bg-white h-[52svh] min-h-[420px] max-h-[560px]">
                        <img src="{{ $tentangPath }}" alt="Tentang ALBRK.SHOESCARE" class="absolute inset-0 w-full h-full object-cover grayscale">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-white/10"></div>
                    </div>

                    {{-- Floating Badge --}}
                    <div class="absolute -bottom-8 right-4 sm:-right-8 bg-white p-5 sm:p-6 rounded-xl shadow-xl border border-neutral-200" data-aos="fade-up" data-aos-delay="300">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-neutral-950 flex items-center justify-center">
                                <i class="fa-solid fa-trophy text-white text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-gray-900">5+</p>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Pengalaman</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:pl-8" data-aos="fade-left">
                    <span class="inline-block bg-neutral-100 text-neutral-600 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider mb-6">Tentang Kami</span>
                    <h2 class="font-display text-4xl lg:text-5xl font-bold tracking-tight text-gray-900 mb-6">
                        Perawatan Premium.<br><span class="gradient-text">Jaminan Kualitas.</span>
                    </h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-8">
                        ALBRK.SHOESCARE hadir dari dedikasi tinggi terhadap standar perawatan sepatu premium. Kami menggunakan formulasi pembersih berkualitas yang terbukti aman untuk segala jenis material sepatu.
                    </p>

                    <div class="space-y-6 mb-10">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-neutral-100 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-check text-neutral-900 text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Bahan Premium</h4>
                                <p class="text-gray-500 text-sm">Menggunakan cairan pembersih berkualitas tinggi</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-neutral-100 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-shield-halved text-neutral-900 text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Garansi Hasil</h4>
                                <p class="text-gray-500 text-sm">100% garansi kepuasan pelanggan</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-neutral-100 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-clock text-neutral-900 text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Proses Cepat</h4>
                                <p class="text-gray-500 text-sm">Estimasi selesai 24-48 jam</p>
                            </div>
                        </div>
                    </div>

                    <a href="#kontak" class="btn-gradient text-white px-8 py-4 rounded-full text-sm font-semibold inline-flex items-center gap-3">
                        <i class="fa-brands fa-whatsapp"></i>
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- KONTAK SECTION --}}
    <section id="kontak" class="py-24 bg-neutral-200">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-stretch">
                <div data-aos="fade-right" class="flex flex-col justify-center">
                    <span class="inline-block bg-neutral-100 text-neutral-700 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider mb-6 self-start">Hubungi Kami</span>
                    <h2 class="font-display text-4xl lg:text-5xl font-bold tracking-tight text-gray-900 mb-6">
                        Kunjungi <span class="gradient-text">Workshop</span> Kami
                    </h2>
                    <p class="text-gray-500 text-lg mb-10">
                        Datang langsung ke workshop kami atau hubungi melalui WhatsApp untuk konsultasi gratis
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl bg-neutral-950 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-location-dot text-white text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Alamat</h4>
                                <p class="text-gray-500">Dsn. Tempuran Rt. 30 Rw. 06<br>Kec. dolopo, Kab. Madiun</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl bg-neutral-950 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-phone text-white text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Telepon</h4>
                                <p class="text-gray-500">+62 857-3608-4686</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl bg-neutral-950 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-clock text-white text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Jam Operasional</h4>
                                <p class="text-gray-500">Setiap Hari: 09:00 - 22:00 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-neutral-100 rounded-2xl overflow-hidden shadow-xl border border-neutral-200 h-[42vh] min-h-[360px] max-h-[440px]" data-aos="fade-left">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.264700874315!2d111.516142675883!3d-7.654637775736637!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e79bf3544520935%3A0x673c6a46a6f685c!2sALBRK.SHOESCARE!5e0!3m2!1sid!2sid!4v1709663784534!5m2!1sid!2sid"
                        class="w-full h-full border-0 grayscale"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>


    {{-- FOOTER --}}
    <footer class="bg-dark text-white relative overflow-hidden">
        <div class="relative z-10 w-full px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex flex-col items-center text-center">
                <a href="/" class="flex items-center gap-3 mb-6">
                    <span class="font-display font-bold text-xl">
                        ALBRK<span class="text-neutral-400">.SHOECARE</span>
                    </span>
                </a>
                <p class="text-gray-400 text-sm leading-relaxed mb-6 max-w-md">
                    Solusi perawatan sepatu premium dengan standar kualitas tinggi.
                </p>
                <h4 class="font-display font-semibold text-xs uppercase tracking-wider text-gray-300 mb-4">Sosial Media Kami</h4>
                <div class="flex gap-3">
                    <a href="https://www.instagram.com/albrk.shoescare.mdn?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-neutral-800/50 border border-neutral-700 flex items-center justify-center text-gray-400 hover:bg-neutral-700 hover:border-neutral-500 hover:text-white transition-all">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="https://wa.me/6285736084686" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-neutral-800/50 border border-neutral-700 flex items-center justify-center text-gray-400 hover:bg-neutral-700 hover:border-neutral-500 hover:text-white transition-all">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                    <a href="https://www.tiktok.com/@albrk_shoescare_mdn?is_from_webapp=1&sender_device=pc" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-neutral-800/50 border border-neutral-700 flex items-center justify-center text-gray-400 hover:bg-neutral-700 hover:border-neutral-500 hover:text-white transition-all">
                        <i class="fa-brands fa-tiktok"></i>
                    </a>
                </div>
            </div>

            <p class="mt-8 text-gray-500 text-sm">
                &copy; 2026 ALBRK.SHOESCARE. Hak cipta dilindungi.
            </p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            offset: 100,
            easing: 'ease-out-cubic',
        });

        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-lg');
            } else {
                nav.classList.remove('shadow-lg');
            }
        });

        document.querySelectorAll('a[href^="#"]').forEach((link) => {
            link.addEventListener('click', (event) => {
                const target = document.querySelector(link.getAttribute('href'));
                const nav = document.querySelector('nav');
                const mobileMenu = document.getElementById('mobileMenu');

                if (!target) return;

                event.preventDefault();
                const offset = target.id === 'beranda' ? 0 : nav.offsetHeight - 1;

                window.scrollTo({
                    top: Math.max(target.offsetTop - offset, 0),
                    behavior: 'smooth',
                });

                history.replaceState(null, '', link.getAttribute('href'));
                mobileMenu?.classList.add('hidden');
            });
        });
    </script>
</body>
</html>
