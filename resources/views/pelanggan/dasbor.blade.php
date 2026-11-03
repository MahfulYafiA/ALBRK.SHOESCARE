@php
    $customer = auth()->user();
    $firstName = explode(' ', $customer->nama ?? 'Pelanggan')[0];
    $activeCount = ($stats['menunggu'] ?? 0) + ($stats['diproses'] ?? 0);
    $avatar = $customer->foto_profil ? route('profil.foto') : null;
    $statusLabels = ['menunggu' => 'Menunggu', 'di_terima' => 'Diterima', 'sedang_diproses' => 'Diproses', 'selesai' => 'Selesai', 'diambil' => 'Diambil', 'dibatalkan' => 'Dibatalkan'];
    $maxStatusCount = max(1, $activeCount, $stats['selesai'] ?? 0, $stats['dibatalkan'] ?? 0);
    $statusSummary = [
        ['label' => 'Menunggu', 'count' => $stats['menunggu'] ?? 0, 'color' => '#f59e0b'],
        ['label' => 'Diproses', 'count' => $stats['diproses'] ?? 0, 'color' => '#3b82f6'],
        ['label' => 'Selesai', 'count' => $stats['selesai'] ?? 0, 'color' => '#10b981'],
        ['label' => 'Dibatalkan', 'count' => $stats['dibatalkan'] ?? 0, 'color' => '#94a3b8'],
    ];
    $statCards = [
        ['label' => 'Total Reservasi', 'value' => $stats['total_reservasi'] ?? 0, 'icon' => 'fa-calendar-check'],
        ['label' => 'Reservasi Diproses', 'value' => $stats['diproses'] ?? 0, 'icon' => 'fa-clock'],
        ['label' => 'Reservasi Selesai', 'value' => $stats['selesai'] ?? 0, 'icon' => 'fa-check'],
        ['label' => 'Total Pengeluaran', 'value' => 'Rp '.number_format($stats['total_transaksi'] ?? 0,0,',','.'), 'icon' => 'fa-wallet'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan - ALBRK.SHOESCARE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f3f7fa; color: #17303d; }
        .customer-sidebar { background: #142a34; }
        .customer-nav-link { display:flex; align-items:center; gap:11px; border-radius:8px; padding:9px 10px; color:#d5dfe3; transition:background .18s,color .18s; }
        .customer-nav-link i { width:16px; text-align:center; font-size:12px; }
        .customer-nav-link:hover,.customer-nav-link.is-active { background:#ffffff29; color:#fff; }
        .customer-panel { background:#fff; border:1px solid #dbe5ea; box-shadow:0 5px 18px rgba(23,48,61,.045); }
        .customer-scroll { scrollbar-width:thin; scrollbar-color:#cbd8de transparent; }
        .customer-scroll::-webkit-scrollbar { width:6px; height:6px; }
        .customer-scroll::-webkit-scrollbar-thumb { background:#cbd8de; border-radius:8px; }
        @media(max-width:760px) {
            .customer-sidebar { width:62px; padding-left:6px; padding-right:6px; }
            .customer-sidebar .brand-label,.customer-sidebar .nav-label,.customer-sidebar .help-card { display:none; }
            .customer-sidebar .brand-link { justify-content:center; padding-left:0; padding-right:0; }
            .customer-sidebar .customer-nav-link { justify-content:center; padding:11px 0; font-size:0; }
            .customer-sidebar .customer-nav-link i { font-size:13px; }
            .customer-main { margin-left:62px!important; width:calc(100% - 62px)!important; }
        }
        @media print { .customer-sidebar { display:none!important; } .customer-main { margin:0!important; width:100%!important; } }
    </style>
</head>
<body class="flex h-screen overflow-hidden antialiased">
    @include('shared.pelanggan-sidebar')

    <main class="customer-main relative z-10 ml-[218px] flex h-screen min-w-0 flex-1 flex-col bg-[#f3f7fa]">
        <header class="z-40 flex shrink-0 items-center justify-between border-b border-[#29424c] bg-[#17303d] px-5 py-3.5 text-white md:px-8">
            <div class="ml-auto flex items-center gap-3 sm:gap-5">
                <span class="hidden rounded-full border border-white/10 bg-white/10 px-4 py-2 text-[9px] font-bold uppercase tracking-widest text-slate-300 sm:block">Hari ini: <strong class="text-white">{{ now()->format('d M Y') }}</strong></span>
                <a href="{{ route('pelanggan.notifikasi') }}" aria-label="Notifikasi, {{ $headerUnreadCount }} belum dibaca" title="Notifikasi" class="relative grid h-9 w-9 place-items-center rounded-full border border-white/10 bg-white/10 text-slate-200 transition hover:bg-white/15"><i class="fa-regular fa-bell"></i>@if($headerUnreadCount)<span class="absolute -right-1 -top-1 grid h-4 min-w-4 place-items-center rounded-full bg-rose-500 px-1 text-[9px] font-bold text-white">{{ $headerUnreadCount > 99 ? '99+' : $headerUnreadCount }}</span>@endif</a>
                @include('shared.user-guide-trigger')
                <div class="relative">
                    <button type="button" id="customerProfileButton" aria-expanded="false" aria-controls="customerProfileMenu" onclick="const menu=document.getElementById('customerProfileMenu');const open=menu.classList.toggle('hidden');this.setAttribute('aria-expanded',String(!open))" class="flex items-center rounded-full border border-white/10 bg-white/10 p-1 pr-3 transition hover:border-sky-400/50">
                        @if($avatar)<img src="{{ $avatar }}" alt="Foto {{ $customer->nama }}" class="h-8 w-8 rounded-full border border-white/20 object-cover">@else<span class="grid h-8 w-8 place-items-center rounded-full bg-sky-700 text-[10px] font-bold">{{ strtoupper(substr($firstName,0,2)) }}</span>@endif
                        <span class="ml-2 hidden text-left sm:block"><strong class="block text-[9px] font-bold uppercase tracking-wider">{{ $firstName }}</strong><small class="mt-0.5 block text-[8px] font-semibold uppercase text-sky-300">Pelanggan</small></span>
                        <i class="fa-solid fa-chevron-down ml-2 text-[9px] text-slate-400"></i>
                    </button>
                    <div id="customerProfileMenu" class="absolute right-0 z-50 mt-2 hidden w-48 overflow-hidden rounded-xl border border-slate-200 bg-white text-xs text-slate-700 shadow-xl">
                        <a href="{{ route('profil.index') }}" class="flex items-center gap-3 px-4 py-3 font-semibold hover:bg-slate-50"><i class="fa-solid fa-user-gear w-4 text-center"></i>Kelola Profil</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-left font-semibold text-rose-600 hover:bg-rose-50"><i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>Keluar</button></form>
                    </div>
                </div>
            </div>
        </header>

        <div class="customer-scroll flex-1 overflow-y-auto p-5 lg:p-8">
            <div class="relative mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white px-6 py-7 shadow-sm md:px-8">
                <div class="pointer-events-none absolute -right-12 -top-20 h-64 w-64 rounded-full bg-sky-100/80 blur-3xl"></div>
                <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                    <div><h1 class="mb-2 text-2xl font-extrabold tracking-tight text-[#17303d] md:text-3xl">Halo, <span class="text-sky-700">{{ $firstName }}!</span></h1><p class="max-w-2xl text-sm leading-relaxed text-slate-500">Pantau status perawatan sepatu dan riwayat reservasi Anda di sini.</p></div>
                    <a href="{{ route('reservasi.create') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-[#17303d] px-4 py-3 text-xs font-bold text-white transition hover:bg-[#244653]"><i class="fa-solid fa-plus"></i>Buat reservasi</a>
                </div>
            </div>

            <section class="mb-6 grid grid-cols-2 gap-4 xl:grid-cols-4" aria-label="Ringkasan akun">
                <?php foreach ($statCards as $card): ?>
                    <article class="customer-panel rounded-2xl p-5 transition hover:-translate-y-0.5"><div class="mb-4 flex items-center justify-between"><span class="flex h-11 w-11 items-center justify-center rounded-xl border border-sky-200 bg-sky-50 text-sky-700"><i class="fa-solid {{ $card['icon'] }} text-base"></i></span></div><p class="mb-1 text-2xl font-black text-[#17303d]">{{ $card['value'] }}</p><p class="text-[10px] uppercase tracking-wider text-slate-500">{{ $card['label'] }}</p></article>
                <?php endforeach; ?>
            </section>

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
                <section class="customer-panel rounded-2xl p-5 md:p-6">
                    <div class="mb-5 flex items-start justify-between"><div><h2 class="text-sm font-bold text-[#17303d]">Ringkasan Status Reservasi</h2><p class="mt-1 text-[10px] text-slate-500">Status seluruh reservasi Anda</p></div><span class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-[9px] font-semibold text-slate-600">{{ $stats['total_reservasi'] ?? 0 }} reservasi</span></div>
                    <div class="space-y-5">
                        <?php foreach ($statusSummary as $item): ?>
                            <div><div class="mb-2 flex items-center justify-between text-[10px]"><span class="font-semibold text-slate-600">{{ $item['label'] }}</span><strong class="text-[#17303d]">{{ $item['count'] }}</strong></div><div class="h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full" style="width:{{ min(100, (int) round(($item['count'] / $maxStatusCount) * 100)) }}%;background:{{ $item['color'] }}"></div></div></div>
                        <?php endforeach; ?>
                    </div>
                    <a href="{{ route('reservasi.create') }}" class="mt-6 inline-flex items-center gap-2 text-[10px] font-semibold text-sky-700 hover:text-sky-900">Buat Reservasi Baru <i class="fa-solid fa-arrow-right"></i></a>
                </section>

                <section id="reservasi-terbaru" class="customer-panel overflow-hidden rounded-2xl">
                    <div class="flex items-center justify-between p-5 md:px-6"><div><h2 class="text-sm font-bold text-[#17303d]">Reservasi Terbaru</h2><p class="mt-1 text-[10px] text-slate-500">Pembaruan reservasi Anda</p></div><a href="{{ route('pelanggan.riwayat-reservasi') }}" class="text-[10px] font-semibold text-sky-700">Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i></a></div>
                    <div class="overflow-x-auto"><table class="w-full min-w-[500px] text-left text-[10px]"><thead class="bg-[#eef4f7] text-slate-500"><tr><th class="px-4 py-3 font-semibold">No. Reservasi</th><th class="px-4 py-3 font-semibold">Tanggal</th><th class="px-4 py-3 font-semibold">Layanan</th><th class="px-4 py-3 font-semibold">Status</th><th class="px-4 py-3 font-semibold">Total</th></tr></thead><tbody>
                        <?php if (count($latestReservations) > 0): foreach ($latestReservations as $reservation): $status = $reservation['status'] ?? 'menunggu'; ?>
                            <tr class="border-t border-slate-100 transition hover:bg-slate-50"><td class="whitespace-nowrap px-4 py-3 font-semibold text-[#17303d]">#{{ $reservation['id_reservasi'] }}</td><td class="whitespace-nowrap px-4 py-3 text-slate-500">{{ \Carbon\Carbon::parse($reservation['tanggal_reservasi'])->format('d M Y') }}</td><td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $reservation['metode_layanan'] ?? 'Perawatan Sepatu' }}</td><td class="px-4 py-3"><span class="whitespace-nowrap rounded-full px-2 py-1 text-[9px] font-semibold {{ in_array($status, ['selesai','diambil']) ? 'bg-emerald-50 text-emerald-700' : ($status === 'dibatalkan' ? 'bg-rose-50 text-rose-700' : 'bg-sky-50 text-sky-700') }}">{{ $statusLabels[$status] ?? ucfirst($status) }}</span></td><td class="whitespace-nowrap px-4 py-3 font-semibold text-[#17303d]">Rp {{ number_format($reservation['total_harga'] ?? 0,0,',','.') }}</td></tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada reservasi masuk.</td></tr>
                        <?php endif; ?>
                    </tbody></table></div>
                </section>
            </div>

            @if(!empty($popularServices))
                <section class="mt-6">
                    <div class="mb-3 flex items-end justify-between gap-3"><div><h2 class="text-base font-bold text-[#17303d]">Layanan tersedia</h2></div><a href="{{ route('reservasi.create') }}" class="text-xs font-semibold text-sky-700 hover:text-sky-900">Lihat semua <i class="fa-solid fa-arrow-right ml-1"></i></a></div>
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($popularServices as $service)
                            <article class="customer-panel flex items-center gap-4 rounded-2xl p-4 transition hover:-translate-y-0.5">
                                @if(!empty($service['gambar']))<img src="{{ route('media.public', ['path' => $service['gambar']]) }}" alt="{{ $service['nama_layanan'] }}" class="h-16 w-16 shrink-0 rounded-xl object-cover">@else<span class="grid h-16 w-16 shrink-0 place-items-center rounded-xl bg-slate-100 text-slate-400"><i class="fa-solid fa-shoe-prints"></i></span>@endif
                                <div class="min-w-0"><h3 class="truncate text-sm font-bold text-[#17303d]">{{ $service['nama_layanan'] }}</h3><p class="mt-1 line-clamp-2 text-xs text-slate-500">{{ $service['deskripsi'] ?: 'Perawatan sepatu dari ALBRK.SHOESCARE.' }}</p><p class="mt-2 text-xs font-extrabold text-sky-800">Rp {{ number_format($service['harga'], 0, ',', '.') }} <span class="font-medium text-slate-400">/ pasang</span></p></div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

        </div>
        <footer class="shrink-0 border-t border-[#dbe5ea] bg-white px-6 py-4 text-center text-xs text-slate-500">&copy; {{ now()->year }} <span class="font-semibold text-slate-600">ALBRK.SHOESCARE</span></footer>
    </main>
    <script>
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
