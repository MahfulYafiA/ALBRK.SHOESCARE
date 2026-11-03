<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran - ALBRK.SHOESCARE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --primary: #17303d;
            --surface: #f3f7fa;
            --text: #17303d;
        }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--surface); 
            color: var(--text); 
            overflow-x: hidden; 
        }
        
        .noise { 
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; 
            pointer-events: none; z-index: 9999; opacity: 0.035; 
            background: url('data:image/svg+xml;utf8,%3Csvg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"%3E%3Cfilter id="noiseFilter"%3E%3CfeTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch"/%3E%3C/filter%3E%3Crect width="100%25" height="100%25" filter="url(%23noiseFilter)"/%3E%3C/svg%3E'); 
        }
        .bg-mesh {
            position: fixed; inset: 0; z-index: -3;
            background-color: #f3f7fa;
            background-image: radial-gradient(#dbe5ea 1px, transparent 1px);
            background-size: 32px 32px; 
            opacity: 0.5;
        }
        .orb-1, .orb-2 {
            position: fixed; border-radius: 50%; filter: blur(140px); z-index: -2;
            animation: floatOrb 15s infinite alternate ease-in-out;
        }
        .orb-1 { width: 600px; height: 600px; background: rgba(84, 119, 134, 0.12); top: -100px; left: -100px; }
        .orb-2 { width: 700px; height: 700px; background: rgba(166, 216, 229, 0.3); bottom: -200px; right: -100px; animation-delay: -5s; }
        
        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(100px, 80px) scale(1.2); }
        }

        .glass-nav { 
            background: rgba(255, 255, 255, 0.65); 
            backdrop-filter: blur(28px); 
            border-bottom: 1px solid rgba(148, 163, 184, 0.5); 
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid #dbe5ea;
            box-shadow: 0 18px 42px -28px rgba(23, 48, 61, 0.3);
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; }
        }
    </style>
    @include('shared.pelanggan-shell-styles')
</head>
<body class="antialiased flex min-h-screen overflow-x-hidden relative">

    <div class="noise"></div>
    <div class="bg-mesh"></div>
    <div class="orb-1"></div>
    <div class="orb-2"></div>

    @include('shared.pelanggan-sidebar')
    <div class="customer-main ml-[218px] flex min-h-screen min-w-0 flex-1 flex-col">
    @include('shared.pelanggan-header', ['pageTitle' => ''])

    <main class="relative z-10 flex flex-1 items-center justify-center overflow-y-auto px-4 py-8 sm:px-6 sm:py-10 lg:px-10 lg:py-14">
        <div class="w-full max-w-5xl">

            <div class="mx-auto mb-8 max-w-2xl text-center sm:mb-10">
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-[#dbe5ea] bg-white/80 px-4 py-2 shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-[#547786]"></span>
                    <p class="text-[9px] font-bold uppercase tracking-[0.24em] text-[#547786]">Konfirmasi Pembayaran</p>
                </div>
                <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-[#17303d] sm:text-4xl lg:text-5xl">Selesaikan reservasi</h2>
                <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-slate-500">Periksa ringkasan pesanan, lalu lanjutkan pembayaran dengan QRIS atau Virtual Account.</p>
            </div>

            <div class="grid items-start gap-5 lg:grid-cols-12 lg:gap-6">
                <section class="glass-card relative overflow-hidden rounded-3xl p-5 sm:p-7 lg:col-span-5" aria-labelledby="order-summary-title">
                    <div class="absolute -right-12 -top-14 h-36 w-36 rounded-full bg-[#eef4f7]"></div>
                    <div class="relative">
                        <div class="mb-6 flex items-center gap-3">
                            <span class="grid h-10 w-10 place-items-center rounded-xl bg-[#eef4f7] text-[#17303d]"><i class="fa-solid fa-receipt"></i></span>
                            <div><h3 id="order-summary-title" class="text-sm font-bold text-[#17303d]">Ringkasan reservasi</h3><p class="mt-0.5 text-xs text-slate-500">Pastikan detail pesanan sudah benar.</p></div>
                        </div>
                        <p class="text-xs font-semibold text-slate-500">Total pembayaran</p>
                        <p class="mt-1 text-3xl font-extrabold tracking-tight text-[#17303d] sm:text-4xl">Rp {{ number_format((int) data_get($reservasi, 'total_harga', 0), 0, ',', '.') }}</p>
                        <dl class="mt-6 divide-y divide-[#e8eef1] rounded-2xl border border-[#e8eef1] bg-white/70 px-4">
                            <div class="flex items-center justify-between gap-4 py-3 text-sm"><dt class="text-slate-500">Nomor reservasi</dt><dd class="font-bold text-[#17303d]">#ORD-{{ data_get($reservasi, 'id_reservasi', '-') }}</dd></div>
                            <div class="flex items-center justify-between gap-4 py-3 text-sm"><dt class="text-slate-500">Jumlah sepatu</dt><dd class="font-bold text-[#17303d]">{{ data_get($reservasi, 'jumlah_sepatu', 1) }} pasang</dd></div>
                            @if(data_get($reservasi, 'detail_layanan'))
                                <div class="py-3 text-sm"><dt class="text-slate-500">Rincian layanan</dt><dd class="mt-1 text-right font-semibold text-[#17303d]">{{ collect(data_get($reservasi, 'detail_layanan'))->map(fn ($detail) => $detail['nama_layanan'].' × '.$detail['jumlah'].' pasang (Rp '.number_format($detail['sub_total'], 0, ',', '.').')')->join(', ') }}</dd></div>
                            @endif
                        </dl>
                    </div>
                </section>

                <section class="glass-card rounded-3xl p-5 sm:p-7 lg:col-span-7" aria-labelledby="payment-method-title">
                    <div class="flex items-start gap-4">
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-[#17303d] text-white"><i class="fa-solid fa-shield-halved"></i></span>
                        <div><p class="text-xs font-semibold text-[#547786]">Pembayaran aman</p><h3 id="payment-method-title" class="mt-1 text-lg font-bold text-[#17303d] sm:text-xl">Lanjutkan dengan Midtrans</h3></div>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-slate-500">Pilih QRIS, Virtual Account, atau metode lain yang tersedia di halaman checkout Midtrans. Status pembayaran akan diperbarui setelah transaksi dikonfirmasi.</p>
                    <div class="mt-5 flex flex-wrap gap-2" aria-label="Metode pembayaran tersedia">
                        <span class="rounded-full border border-[#dbe5ea] bg-[#f7fafb] px-3 py-1.5 text-xs font-semibold text-slate-600"><i class="fa-solid fa-qrcode mr-1.5 text-[#547786]"></i>QRIS</span>
                        <span class="rounded-full border border-[#dbe5ea] bg-[#f7fafb] px-3 py-1.5 text-xs font-semibold text-slate-600"><i class="fa-solid fa-building-columns mr-1.5 text-[#547786]"></i>Virtual Account</span>
                    </div>
                    <button id="pay-button" type="button" class="mt-7 flex w-full items-center justify-center gap-2.5 rounded-xl bg-[#17303d] px-5 py-4 text-sm font-bold text-white shadow-lg shadow-slate-900/10 transition hover:bg-[#244653] focus:outline-none focus:ring-4 focus:ring-[#547786]/20 active:scale-[.99] disabled:cursor-wait disabled:opacity-60">
                        <span>Bayar sekarang</span><i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <p class="mt-3 flex items-center justify-center gap-1.5 text-center text-[11px] text-slate-400"><i class="fa-solid fa-lock"></i>Anda akan diarahkan ke checkout aman Midtrans.</p>
                </section>
            </div>

        </div>
    </main>
    <footer class="shrink-0 border-t border-[#dbe5ea] bg-white px-6 py-4 text-center text-xs text-slate-500">&copy; {{ now()->year }} <span class="font-semibold text-slate-600">ALBRK.SHOESCARE</span></footer>
    </div>

    {{-- SCRIPT MIDTRANS --}}
    <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            // Loading State
            const btn = document.getElementById('pay-button');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'MEMPROSES... <i class="fa-solid fa-circle-notch fa-spin ml-2"></i>';
            btn.disabled = true;
            btn.classList.add('cursor-wait');

            snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) { 
                    window.location.href = "{{ route('pelanggan.riwayat-reservasi') }}";
                },
                onPending: function (result) { 
                    alert("Pembayaran tertunda, silakan selesaikan instruksi pembayaran."); 
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    btn.classList.remove('cursor-wait');
                },
                onError: function (result) { 
                    alert("Pembayaran gagal atau dibatalkan."); 
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    btn.classList.remove('cursor-wait');
                },
                onClose: function () {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    btn.classList.remove('cursor-wait');
                }
            });
        };
    </script>
</body>
</html>

