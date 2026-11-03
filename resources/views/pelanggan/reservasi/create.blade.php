@php
    $fallbackImages = ['Cuci Sepatu' => 'fastclean.png', 'Deep Cleaning' => 'deepclean.png', 'Unyellowing' => 'unyellowing.png', 'Repaint' => 'adidasspezial.png', 'Treatment' => 'fastclean.png'];
    $selectedLayanans = array_map('strval', (array) old('id_layanans', request('id_layanans', [])));
    $selectedQuantities = (array) old('jumlah_layanan', []);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Layanan - ALBRK.SHOESCARE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background:#f3f7fa; color:#17303d; }
        .booking-panel { background:#fff; border:1px solid #dbe5ea; box-shadow:0 5px 18px rgba(23,48,61,.045); }
        .booking-service { transition:border-color .18s, box-shadow .18s, transform .18s; }
        .booking-service:hover { transform:translateY(-2px); border-color:#9eb5c0; box-shadow:0 8px 18px rgba(23,48,61,.08); }
        .service-description { display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:3; }
        .booking-checkbox:checked + .booking-service { border-color:#17303d; box-shadow:0 0 0 1px #17303d; }
        .booking-checkbox:checked + .booking-service .booking-check { opacity:1; transform:scale(1); }
        .booking-choice:checked + .booking-choice-card { border-color:#17303d; background:#f3f7fa; box-shadow:0 0 0 1px #17303d; }
        .booking-field { border:1px solid #dbe5ea; background:#fff; color:#17303d; }
        .booking-field:focus { outline:none; border-color:#6f91a0; box-shadow:0 0 0 3px rgba(23,48,61,.08); }
        [data-quantity-minus]:focus-visible,[data-quantity-plus]:focus-visible { outline:2px solid #547786; outline-offset:-2px; }
        [data-quantity-minus]:disabled,[data-quantity-plus]:disabled { cursor:not-allowed; opacity:.45; }
        .booking-scroll { scrollbar-width:thin; scrollbar-color:#cbd8de transparent; }
        .customer-sidebar { background:#142a34; }
        .customer-nav-link { display:flex; align-items:center; gap:11px; border-radius:8px; padding:9px 10px; color:#d5dfe3; transition:background .18s,color .18s; }
        .customer-nav-link i { width:16px; text-align:center; font-size:12px; }
        .customer-nav-link:hover,.customer-nav-link.is-active { background:#ffffff29; color:#fff; }
        @media(max-width:760px) {
            .customer-sidebar { width:62px; padding-left:6px; padding-right:6px; }
            .customer-sidebar .brand-label,.customer-sidebar .nav-label,.customer-sidebar .help-card { display:none; }
            .customer-sidebar .brand-link { justify-content:center; padding-left:0; padding-right:0; }
            .customer-sidebar .customer-nav-link { justify-content:center; padding:11px 0; font-size:0; }
            .customer-sidebar .customer-nav-link i { font-size:13px; }
            .customer-main { margin-left:62px!important; width:calc(100% - 62px)!important; }
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden antialiased">
    @include('shared.pelanggan-sidebar')

    <main class="customer-main ml-[218px] flex h-screen min-w-0 flex-1 flex-col bg-[#f3f7fa]">
        <header class="z-40 flex shrink-0 items-center justify-between border-b border-[#29424c] bg-[#17303d] px-5 py-3.5 text-white md:px-8">
            <div class="ml-auto flex items-center gap-3 sm:gap-5">
                <span class="hidden rounded-full border border-white/10 bg-white/10 px-4 py-2 text-[9px] font-bold uppercase tracking-widest text-slate-300 sm:block">Hari ini: <strong class="text-white">{{ now()->format('d M Y') }}</strong></span>
                <a href="{{ route('pelanggan.notifikasi') }}" aria-label="Notifikasi, {{ $headerUnreadCount }} belum dibaca" title="Notifikasi" class="relative grid h-9 w-9 place-items-center rounded-full border border-white/10 bg-white/10 text-slate-200 transition hover:bg-white/15"><i class="fa-regular fa-bell"></i>@if($headerUnreadCount)<span class="absolute -right-1 -top-1 grid h-4 min-w-4 place-items-center rounded-full bg-rose-500 px-1 text-[9px] font-bold text-white">{{ $headerUnreadCount > 99 ? '99+' : $headerUnreadCount }}</span>@endif</a>
                @include('shared.user-guide-trigger')
                <div class="relative">
                    <button type="button" id="customerProfileButton" aria-expanded="false" aria-controls="customerProfileMenu" onclick="const menu=document.getElementById('customerProfileMenu');const open=menu.classList.toggle('hidden');this.setAttribute('aria-expanded',String(!open))" class="flex items-center rounded-full border border-white/10 bg-white/10 p-1 pr-3 transition hover:border-sky-400/50">
                        @if(auth()->user()->foto_profil)<img src="{{ route('profil.foto') }}" alt="Foto profil" class="h-8 w-8 rounded-full border border-white/20 object-cover">@else<span class="grid h-8 w-8 place-items-center rounded-full bg-sky-700 text-[10px] font-bold">{{ strtoupper(substr(auth()->user()->nama ?? 'PL',0,2)) }}</span>@endif
                        <span class="ml-2 hidden text-left sm:block"><strong class="block text-[9px] font-bold uppercase tracking-wider">{{ explode(' ',auth()->user()->nama ?? 'Pelanggan')[0] }}</strong><small class="mt-0.5 block text-[8px] font-semibold uppercase text-sky-300">Pelanggan</small></span><i class="fa-solid fa-chevron-down ml-2 text-[9px] text-slate-400"></i>
                    </button>
                    <div id="customerProfileMenu" class="absolute right-0 z-50 mt-2 hidden w-48 overflow-hidden rounded-xl border border-slate-200 bg-white text-xs text-slate-700 shadow-xl"><a href="{{ route('profil.index') }}" class="flex items-center gap-3 px-4 py-3 font-semibold hover:bg-slate-50"><i class="fa-solid fa-user-gear w-4 text-center"></i>Kelola Profil</a><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-left font-semibold text-rose-600 hover:bg-rose-50"><i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>Keluar</button></form></div>
                </div>
            </div>
        </header>
        <div class="booking-scroll flex-1 overflow-y-auto px-3 py-4 lg:px-4">
        <div class="w-full">
        @if(session('error'))
            <div class="mb-4 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs font-semibold text-red-700"><i class="fa-solid fa-circle-exclamation"></i>{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700"><div class="mb-2 flex items-center gap-2 text-xs font-bold"><i class="fa-solid fa-circle-exclamation"></i>Periksa kembali data reservasi:</div><ul class="list-inside list-disc space-y-1 text-[10px]">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <form id="formReservasi" action="{{ route('reservasi.store') }}" method="POST" class="grid grid-cols-1 items-start gap-5 xl:grid-cols-[minmax(0,1.75fr)_minmax(360px,.9fr)]">
            @csrf
            <section class="booking-panel rounded-xl p-5 sm:p-6">
                <div class="mb-4 flex flex-col items-start gap-3">
                    <div><h2 class="text-base font-bold text-[#17303d]">Layanan Kami</h2><p class="mt-1 text-xs text-slate-500">Pilih layanan dan atur jumlah pasang di setiap kartu.</p></div>
                    <label class="flex h-11 w-full items-center gap-2 rounded-lg border border-[#dbe5ea] px-3 text-slate-400 sm:max-w-[320px]"><i class="fa-solid fa-magnifying-glass text-[10px]"></i><input id="layananSearch" type="search" placeholder="Cari layanan..." class="w-full text-xs text-slate-700 outline-none" aria-label="Cari layanan"></label>
                </div>

                <div id="serviceFilters" class="mb-4 flex flex-wrap gap-2.5 text-[11px]">
                    <button type="button" data-service-filter="semua" class="rounded-full bg-[#17303d] px-4 py-2 font-semibold text-white">Semua</button>
                    <button type="button" data-service-filter="cuci" class="rounded-full bg-slate-100 px-4 py-2 text-slate-500">Cuci & Bersih</button>
                    <button type="button" data-service-filter="perawatan" class="rounded-full bg-slate-100 px-4 py-2 text-slate-500">Perawatan</button>
                    <button type="button" data-service-filter="custom" class="rounded-full bg-slate-100 px-4 py-2 text-slate-500">Custom</button>
                </div>

                <div id="serviceGrid" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-3">
                    @forelse($layanans as $l)
                        @php
                            $idLayanan = data_get($l, 'id', data_get($l, 'id_layanan'));
                            $namaLayanan = data_get($l, 'nama_layanan', 'Layanan');
                            $hargaLayanan = (int) data_get($l, 'harga', 0);
                            $deskripsiLayanan = data_get($l, 'deskripsi', 'Perawatan sepatu profesional.');
                            $gambarLayanan = data_get($l, 'gambar');
                            $namaLayananLower = strtolower($namaLayanan);
                            $kategoriLayanan = str_contains($namaLayananLower, 'custom') || str_contains($namaLayananLower, 'special') ? 'custom' : (str_contains($namaLayananLower, 'repaint') || str_contains($namaLayananLower, 'treatment') || str_contains($namaLayananLower, 'unyellowing') ? 'perawatan' : 'cuci');
                            $isSelected = in_array((string) $idLayanan, $selectedLayanans, true) || ($selectedLayanans === [] && $loop->first);
                            $gambarFallback = $fallbackImages[$namaLayanan] ?? 'fastclean.png';
                        @endphp
                        <div class="service-option relative" data-service-option data-service-name="{{ $namaLayananLower }}" data-service-category="{{ $kategoriLayanan }}">
                            <label class="block cursor-pointer">
                                <input type="checkbox" name="id_layanans[]" value="{{ $idLayanan }}" data-harga="{{ $hargaLayanan }}" data-nama="{{ $namaLayanan }}" class="booking-checkbox sr-only layanan-checkbox" {{ $isSelected ? 'checked' : '' }}>
                                <span class="booking-service flex h-[296px] flex-col overflow-hidden rounded-lg border border-slate-200 bg-white">
                                    <span class="relative block h-[132px] shrink-0 overflow-hidden bg-slate-100"><img src="{{ $gambarLayanan ? route('media.public', ['path' => $gambarLayanan]) : asset('images/'.$gambarFallback) }}" alt="{{ $namaLayanan }}" class="h-full w-full object-cover {{ $loop->iteration === 2 ? 'scale-110' : '' }}"><span class="booking-check absolute right-2 top-2 grid h-5 w-5 scale-75 place-items-center rounded-full bg-[#17303d] text-[9px] text-white opacity-0 transition"><i class="fa-solid fa-check"></i></span></span>
                                    <span class="flex flex-1 flex-col p-3"><strong class="block truncate text-xs font-bold text-[#17303d]">{{ $namaLayanan }}</strong><span class="service-description mt-1.5 h-[42px] min-h-[42px] overflow-hidden text-[10px] leading-[14px] text-slate-500">{{ $deskripsiLayanan }}</span><strong class="mt-1.5 block text-xs text-[#17303d]">Rp {{ number_format($hargaLayanan,0,',','.') }} <span class="font-normal text-slate-500">/ pasang</span></strong><span class="mt-auto block rounded-md border border-slate-200 py-2 text-center text-[10px] font-semibold text-slate-600">Pilih Layanan</span></span>
                                </span>
                            </label>
                            <div class="service-quantity mt-2 flex items-center justify-between rounded-lg border border-slate-200 bg-white px-3 py-2 {{ $isSelected ? '' : 'hidden' }}" data-quantity-control>
                                <label for="jumlah_layanan_{{ $idLayanan }}" class="text-[10px] font-semibold text-slate-600">Jumlah pasang</label>
                                <span class="flex h-8 items-center overflow-hidden rounded-md border border-slate-200 bg-slate-50">
                                    <button type="button" data-quantity-minus class="grid h-full w-8 place-items-center text-slate-600 transition hover:bg-slate-200" aria-label="Kurangi jumlah {{ $namaLayanan }}">&minus;</button>
                                    <input type="number" id="jumlah_layanan_{{ $idLayanan }}" name="jumlah_layanan[{{ $idLayanan }}]" value="{{ $selectedQuantities[$idLayanan] ?? 1 }}" min="1" max="10" inputmode="numeric" required class="h-full w-10 border-x border-slate-200 bg-white text-center text-xs font-semibold text-[#17303d] outline-none" {{ $isSelected ? '' : 'disabled' }}>
                                    <button type="button" data-quantity-plus class="grid h-full w-8 place-items-center text-slate-600 transition hover:bg-slate-200" aria-label="Tambah jumlah {{ $namaLayanan }}">+</button>
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full rounded-lg bg-slate-50 py-10 text-center text-xs text-slate-500">Belum ada layanan yang tersedia.</p>
                    @endforelse
                </div>
                <p id="emptySearch" class="hidden py-8 text-center text-xs text-slate-500">Layanan tidak ditemukan.</p>

                <div class="mt-5">
                    <label class="block"><span class="mb-1.5 block text-[11px] font-semibold text-slate-600">Metode Penyerahan</span><select name="metode_layanan" id="metode_layanan" class="booking-field h-12 w-full rounded-lg px-3 text-xs" required><option value="Drop-off" {{ old('metode_layanan','Drop-off') === 'Drop-off' ? 'selected' : '' }}>Antar ke toko (Drop-off)</option><option value="Pick-up" {{ old('metode_layanan') === 'Pick-up' ? 'selected' : '' }}>Jemput kurir (Pick-up)</option></select></label>
                </div>
                <div id="areaAlamat" class="mt-3 hidden"><label for="alamat_jemput" class="mb-1.5 block text-[11px] font-semibold text-slate-600">Alamat Penjemputan</label><textarea name="alamat_jemput" id="alamat_jemput" rows="2" maxlength="255" placeholder="Masukkan alamat lengkap penjemputan..." class="booking-field w-full resize-y rounded-lg px-3 py-2.5 text-[10px]">{{ old('alamat_jemput') }}</textarea></div>
            </section>

            <aside class="booking-panel rounded-xl p-5 sm:p-6 xl:sticky xl:top-4">
                <div class="mb-4 flex items-center gap-2.5"><span class="grid h-8 w-8 place-items-center rounded-lg bg-[#eef4f7] text-[#17303d]"><i class="fa-solid fa-calendar-check text-xs"></i></span><div><h2 class="text-base font-bold text-[#17303d]">Detail Reservasi</h2><p class="text-[11px] text-slate-500">Lengkapi informasi reservasi Anda.</p></div></div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <label class="block"><span class="mb-1.5 block text-[11px] font-semibold text-slate-600">Tanggal Reservasi</span><span class="flex h-12 items-center gap-2 rounded-lg border border-[#dbe5ea] px-2.5"><i class="fa-regular fa-calendar text-slate-500"></i><input type="date" name="tanggal_reservasi" value="{{ old('tanggal_reservasi', now()->toDateString()) }}" min="{{ now()->toDateString() }}" required class="w-full min-w-0 bg-transparent text-[11px] text-[#17303d] outline-none"></span></label>
                        <div class="rounded-lg border border-slate-100 bg-slate-50 p-2.5"><p class="text-[10px] text-slate-500">Metode Penyerahan</p><p id="summaryDelivery" class="mt-1 truncate text-xs font-semibold text-[#17303d]">{{ old('metode_layanan','Drop-off') === 'Pick-up' ? 'Jemput kurir' : 'Antar ke toko' }}</p></div>
                    </div>

                    <div id="areaPengantaran" class="hidden space-y-3 rounded-lg border border-slate-100 bg-slate-50 p-3">
                        <label class="block"><span class="mb-1.5 block text-[11px] font-semibold text-slate-600">No. WhatsApp Penerima</span><input type="tel" id="wa_pengantaran" name="wa_pengantaran" value="{{ old('wa_pengantaran',auth()->user()->no_telp) }}" maxlength="15" placeholder="08xxxxxxxxxx" class="booking-field h-9 w-full rounded-lg px-3 text-[10px]"></label>
                        <label class="block"><span class="mb-1.5 block text-[11px] font-semibold text-slate-600">Alamat Pengantaran</span><textarea id="alamat_pengantaran" name="alamat_pengantaran" rows="2" maxlength="255" placeholder="Masukkan alamat pengantaran..." class="booking-field w-full resize-y rounded-lg px-3 py-2 text-[10px]">{{ old('alamat_pengantaran',auth()->user()->alamat) }}</textarea></label>
                    </div>

                    <fieldset><legend class="mb-2 text-[11px] font-semibold text-slate-600">Metode Pengembalian</legend><div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer"><input type="radio" name="metode_pengembalian" value="diambil" class="booking-choice sr-only" {{ old('metode_pengembalian','diambil') === 'diambil' ? 'checked' : '' }} required><span class="booking-choice-card flex min-h-12 items-center gap-2 rounded-lg border border-slate-200 px-2.5 text-[11px] text-slate-600"><i class="fa-solid fa-store"></i>Ambil di toko</span></label>
                        <label class="cursor-pointer"><input type="radio" name="metode_pengembalian" value="diantar" class="booking-choice sr-only" {{ old('metode_pengembalian') === 'diantar' ? 'checked' : '' }}><span class="booking-choice-card flex min-h-12 items-center gap-2 rounded-lg border border-slate-200 px-2.5 text-[9px] text-slate-600"><i class="fa-solid fa-truck"></i>Diantar</span></label>
                    </div></fieldset>

                    <label class="block"><span class="mb-1.5 block text-[11px] font-semibold text-slate-600">Catatan Tambahan (Opsional)</span><textarea name="catatan" rows="2" maxlength="500" placeholder="Contoh: sepatu warna putih, ada noda lumpur..." class="booking-field w-full resize-y rounded-lg px-3 py-2.5 text-[10px]">{{ old('catatan') }}</textarea></label>

                    <fieldset><legend class="mb-2 text-[11px] font-semibold text-slate-600">Metode Pembayaran</legend><div class="space-y-2">
                        <label class="cursor-pointer"><input type="radio" name="metode_pembayaran" value="cash" class="booking-choice sr-only" {{ in_array(old('metode_pembayaran','cash'),['cash','Bayar di Toko'],true) ? 'checked' : '' }} required><span class="booking-choice-card flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2.5 text-[11px] text-slate-700"><span><i class="fa-solid fa-store mr-2"></i>Bayar di toko</span><i class="fa-solid fa-circle-check text-slate-400"></i></span></label>
                        <label class="cursor-pointer"><input type="radio" name="metode_pembayaran" value="Payment Gateway" class="booking-choice sr-only" {{ old('metode_pembayaran') === 'Payment Gateway' ? 'checked' : '' }}><span class="booking-choice-card flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2.5 text-[9px] text-slate-700"><span><i class="fa-solid fa-credit-card mr-2"></i>QRIS / Bank (Otomatis)</span><i class="fa-solid fa-circle-check text-slate-400"></i></span></label>
                    </div></fieldset>
                    <div id="areaTransfer" class="hidden rounded-lg bg-slate-50 p-3 text-[9px] leading-relaxed text-slate-500"><strong class="mb-1 block text-[#17303d]">Pembayaran otomatis via Midtrans</strong>Setelah reservasi dikirim, Anda akan diarahkan ke halaman pembayaran untuk memilih QRIS, e-wallet, atau transfer bank.</div>
                    <div id="areaBayarToko" class="hidden rounded-lg bg-slate-50 p-3 text-[9px] leading-relaxed text-slate-500"><strong class="mb-1 block text-[#17303d]">Pembayaran di kasir</strong>Bayar tunai atau QRIS saat menyerahkan sepatu di toko.</div>

                    <div class="border-t border-slate-200 pt-3"><h3 class="mb-2 text-[10px] font-bold text-[#17303d]"><i class="fa-solid fa-receipt mr-1.5"></i>Ringkasan Biaya</h3><div id="summaryLayanan" class="space-y-1.5 text-[9px]"><p class="flex justify-between text-slate-500"><span>Belum ada layanan dipilih</span><span>Rp 0</span></p></div><div class="mt-2 flex justify-between border-t border-slate-100 pt-2 text-[9px] text-slate-500"><span>Jumlah Sepatu</span><strong id="summaryQuantity" class="text-[#17303d]">1 pasang</strong></div><div class="mt-2 flex justify-between text-xs font-extrabold text-[#17303d]"><span>Total</span><span id="totalTagihan">Rp 0</span></div></div>
                    <button type="submit" id="btnSubmit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#10191f] px-4 py-4 text-xs font-bold text-white transition hover:bg-[#244653] disabled:cursor-wait disabled:opacity-60">Konfirmasi Reservasi <i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </aside>
        </form>
        </div>
        </div>
        <footer class="shrink-0 border-t border-[#dbe5ea] bg-white px-6 py-4 text-center text-xs text-slate-500">&copy; {{ now()->year }} <span class="font-semibold text-slate-600">ALBRK.SHOESCARE</span></footer>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('formReservasi');
            const total = document.getElementById('totalTagihan');
            const summary = document.getElementById('summaryLayanan');
            const delivery = document.getElementById('metode_layanan');
            const pickupArea = document.getElementById('areaAlamat');
            const returnArea = document.getElementById('areaPengantaran');
            const returnRadios = document.querySelectorAll('input[name="metode_pengembalian"]');
            const paymentRadios = document.querySelectorAll('input[name="metode_pembayaran"]');
            const transferArea = document.getElementById('areaTransfer');
            const cashArea = document.getElementById('areaBayarToko');
            const formatRupiah = amount => 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);

            function updateSummary() {
                const selected = [...document.querySelectorAll('.layanan-checkbox:checked')];
                let sum = 0;
                let totalPairs = 0;
                summary.innerHTML = '';
                if (!selected.length) {
                    summary.innerHTML = '<p class="flex justify-between text-slate-500"><span>Belum ada layanan dipilih</span><span>Rp 0</span></p>';
                } else {
                    selected.forEach(input => {
                        const price = parseInt(input.dataset.harga, 10) || 0;
                        const quantityInput = input.closest('[data-service-option]').querySelector('.service-quantity input');
                        const count = Math.min(10, Math.max(1, parseInt(quantityInput.value, 10) || 1));
                        const subtotal = price * count;
                        sum += subtotal;
                        totalPairs += count;
                        const line = document.createElement('p');
                        line.className = 'flex justify-between gap-3 text-slate-500';
                        const name = document.createElement('span');
                        name.className = 'truncate pr-2';
                        name.textContent = `${input.dataset.nama} × ${count}`;
                        const priceText = document.createElement('span');
                        priceText.className = 'shrink-0';
                        priceText.textContent = formatRupiah(subtotal);
                        line.append(name, priceText);
                        summary.append(line);
                    });
                }
                total.textContent = formatRupiah(sum);
                document.getElementById('summaryQuantity').textContent = totalPairs + (totalPairs === 1 ? ' pasang' : ' pasang');
                document.getElementById('summaryDelivery').textContent = delivery.value === 'Pick-up' ? 'Jemput kurir' : 'Antar ke toko';
            }

            document.querySelectorAll('.layanan-checkbox').forEach(input => {
                const quantityControl = input.closest('[data-service-option]').querySelector('[data-quantity-control]');
                const quantityInput = quantityControl.querySelector('input');
                const quantityMinus = quantityControl.querySelector('[data-quantity-minus]');
                const quantityPlus = quantityControl.querySelector('[data-quantity-plus]');
                const updateServiceQuantity = () => {
                    quantityControl.classList.toggle('hidden', !input.checked);
                    quantityInput.disabled = !input.checked;
                    quantityMinus.disabled = !input.checked;
                    quantityPlus.disabled = !input.checked;
                    updateSummary();
                };
                input.addEventListener('change', updateServiceQuantity);
                quantityInput.addEventListener('input', updateSummary);
                quantityMinus.addEventListener('click', () => {
                    quantityInput.value = Math.max(1, (parseInt(quantityInput.value, 10) || 1) - 1);
                    updateSummary();
                });
                quantityPlus.addEventListener('click', () => {
                    quantityInput.value = Math.min(10, (parseInt(quantityInput.value, 10) || 1) + 1);
                    updateSummary();
                });
                updateServiceQuantity();
            });

            function updatePickup() {
                const needsPickup = delivery.value === 'Pick-up';
                pickupArea.classList.toggle('hidden', !needsPickup);
                document.getElementById('alamat_jemput').required = needsPickup;
            }
            function updateReturn() {
                const needsDelivery = document.querySelector('input[name="metode_pengembalian"]:checked')?.value === 'diantar';
                returnArea.classList.toggle('hidden', !needsDelivery);
                document.getElementById('wa_pengantaran').required = needsDelivery;
                document.getElementById('alamat_pengantaran').required = needsDelivery;
            }
            function updatePayment() {
                const method = document.querySelector('input[name="metode_pembayaran"]:checked')?.value;
                transferArea.classList.toggle('hidden', method !== 'Payment Gateway');
                cashArea.classList.toggle('hidden', method !== 'cash');
            }

            delivery.addEventListener('change', () => { updatePickup(); updateSummary(); });
            returnRadios.forEach(input => input.addEventListener('change', updateReturn));
            paymentRadios.forEach(input => input.addEventListener('change', updatePayment));
            let activeCategory = 'semua';
            function applyServiceFilters() {
                const query = document.getElementById('layananSearch').value.trim().toLocaleLowerCase('id');
                let visible = 0;
                document.querySelectorAll('[data-service-option]').forEach(card => {
                    const matches = card.dataset.serviceName.includes(query) && (activeCategory === 'semua' || card.dataset.serviceCategory === activeCategory);
                    card.classList.toggle('hidden', !matches);
                    visible += matches ? 1 : 0;
                });
                document.getElementById('emptySearch').classList.toggle('hidden', visible > 0);
            }
            document.getElementById('layananSearch').addEventListener('input', applyServiceFilters);
            document.querySelectorAll('[data-service-filter]').forEach(button => button.addEventListener('click', () => {
                activeCategory = button.dataset.serviceFilter;
                document.querySelectorAll('[data-service-filter]').forEach(filter => {
                    filter.classList.toggle('bg-[#17303d]', filter === button);
                    filter.classList.toggle('text-white', filter === button);
                    filter.classList.toggle('bg-slate-100', filter !== button);
                    filter.classList.toggle('text-slate-500', filter !== button);
                    filter.classList.toggle('font-semibold', filter === button);
                });
                applyServiceFilters();
            }));
            form.addEventListener('submit', () => {
                const button = document.getElementById('btnSubmit');
                button.disabled = true;
                button.innerHTML = 'Memproses... <i class="fa-solid fa-circle-notch fa-spin"></i>';
            });

            updateSummary(); updatePickup(); updateReturn(); updatePayment();
        });
        document.addEventListener('click', event => {
            const menu = document.getElementById('customerProfileMenu');
            const button = document.getElementById('customerProfileButton');
            if (menu && button && !menu.contains(event.target) && !button.contains(event.target)) {
                menu.classList.add('hidden');
                button.setAttribute('aria-expanded', 'false');
            }
        });
    </script>
    @include('shared.user-guide')
</body>
</html>
