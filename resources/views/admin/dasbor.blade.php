<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - ALBRK.SHOESCARE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .dashboard-scroll { scrollbar-width: thin; scrollbar-color: #cbd8de transparent; }
        .dashboard-scroll::-webkit-scrollbar { width: 6px; }
        .dashboard-scroll::-webkit-scrollbar-thumb { background: #cbd8de; border-radius: 8px; }
        html, body { width: 100%; max-width: 100%; overflow-x: hidden; }
        body:has(.admin-sidebar) > main { flex: 1 1 0%; margin-left: 218px; width: calc(100% - 218px); max-width: calc(100% - 218px); }
        @media (max-width: 760px) { body:has(.admin-sidebar) > main { margin-left: 62px; width: calc(100% - 62px); max-width: calc(100% - 62px); } }
    </style>
    @include('shared.albrk-redesign')
</head>
<body class="flex h-screen overflow-hidden bg-[#f3f7fa] text-slate-700 antialiased selection:bg-sky-100 selection:text-[#17303d]">
    @include('shared.admin-sidebar')

    <main class="flex h-screen min-w-0 flex-1 flex-col bg-[#f3f7fa]">
        <header class="z-40 flex shrink-0 items-center justify-between border-b border-[#29424c] bg-[#17303d] px-5 py-3.5 text-white md:px-8">
            <div></div>
            @include('shared.admin-header-actions')
        </header>

        <div class="dashboard-scroll flex-1 overflow-y-auto p-3 lg:p-4">
            <div class="w-full space-y-6">
                <section class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="pointer-events-none absolute -right-12 -top-20 h-64 w-64 rounded-full bg-sky-100/80 blur-3xl"></div>
                    <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-[#17303d] sm:text-3xl">Selamat datang, {{ auth()->user()->nama ?? 'Admin' }}</h1>
                            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-500">Pantau antrean, tinjau pembayaran, dan kelola reservasi pelanggan ALBRK.SHOESCARE.</p>
                        </div>
                        <a href="{{ route('admin.antrean') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-[#17303d] px-4 py-3 text-xs font-bold text-white transition hover:bg-[#244653]"><i class="fa-solid fa-list-check"></i>Buka antrean</a>
                    </div>
                </section>

                @php
                    $cards = [
                        ['label' => 'Antrean aktif', 'value' => $stats['total_antrean'] ?? 0, 'icon' => 'fa-list-check', 'iconClass' => 'bg-indigo-50 text-indigo-700'],
                        ['label' => 'Menunggu konfirmasi', 'value' => $stats['total_menunggu'] ?? 0, 'icon' => 'fa-hourglass-half', 'iconClass' => 'bg-amber-50 text-amber-700'],
                        ['label' => 'Sedang diproses', 'value' => $stats['total_diproses'] ?? 0, 'icon' => 'fa-gears', 'iconClass' => 'bg-sky-50 text-sky-700'],
                        ['label' => 'Reservasi selesai', 'value' => $stats['total_selesai'] ?? 0, 'icon' => 'fa-circle-check', 'iconClass' => 'bg-emerald-50 text-emerald-700'],
                        ['label' => 'Total pelanggan', 'value' => $stats['total_pelanggan'] ?? 0, 'icon' => 'fa-users', 'iconClass' => 'bg-violet-50 text-violet-700'],
                        ['label' => 'Total omzet', 'value' => 'Rp '.number_format($stats['total_omzet'] ?? 0, 0, ',', '.'), 'icon' => 'fa-wallet', 'iconClass' => 'bg-blue-50 text-blue-700'],
                    ];
                @endphp
                <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6" aria-label="Statistik operasional">
                    @foreach($cards as $card)
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <span class="grid h-11 w-11 place-items-center rounded-xl {{ $card['iconClass'] }}"><i class="fa-solid {{ $card['icon'] }}"></i></span>
                            <p class="mt-5 break-words text-2xl font-extrabold tracking-tight text-[#17303d]">{{ $card['value'] }}</p>
                            <p class="mt-1 text-xs font-medium text-slate-500">{{ $card['label'] }}</p>
                        </article>
                    @endforeach
                </section>

                <section>
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        @php
                            $menus = [
                                ['url' => route('admin.antrean'), 'icon' => 'fa-list-check', 'title' => 'Antrean', 'desc' => 'Konfirmasi dan proses reservasi', 'iconClass' => 'bg-sky-50 text-sky-700'],
                                ['url' => route('admin.pembayaran.index'), 'icon' => 'fa-credit-card', 'title' => 'Pembayaran', 'desc' => 'Periksa transaksi pelanggan', 'iconClass' => 'bg-emerald-50 text-emerald-700'],
                                ['url' => route('admin.reservasi.index'), 'icon' => 'fa-calendar-check', 'title' => 'Reservasi', 'desc' => 'Cari dan tinjau data reservasi', 'iconClass' => 'bg-violet-50 text-violet-700'],
                                ['url' => route('admin.laporan'), 'icon' => 'fa-chart-column', 'title' => 'Laporan', 'desc' => 'Tinjau ringkasan operasional', 'iconClass' => 'bg-amber-50 text-amber-700'],
                            ];
                        @endphp
                        @foreach($menus as $menu)
                            <a href="{{ $menu['url'] }}" class="group flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md">
                                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl {{ $menu['iconClass'] }}"><i class="fa-solid {{ $menu['icon'] }}"></i></span>
                                <span class="min-w-0 flex-1"><span class="block text-sm font-bold text-[#17303d]">{{ $menu['title'] }}</span><span class="mt-1 block text-xs text-slate-500">{{ $menu['desc'] }}</span></span>
                                <i class="fa-solid fa-arrow-right text-xs text-slate-300 transition group-hover:translate-x-1 group-hover:text-slate-600"></i>
                            </a>
                        @endforeach
                    </div>
                </section>

                @php($maxActivity = max(1, (int) collect($weeklyActivity ?? [])->max('count')))
                <section class="grid gap-5 xl:grid-cols-[.9fr_1.1fr]">
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="mb-5"><h2 class="text-sm font-bold text-[#17303d]">Aktivitas reservasi</h2><p class="mt-1 text-xs text-slate-500">Jumlah reservasi dalam 7 hari terakhir</p></div>
                        <div class="flex h-44 items-end justify-between gap-3 border-b border-l border-slate-200 px-3 pb-2 pt-4">
                            @forelse($weeklyActivity ?? [] as $day)
                                <div class="flex h-full min-w-0 flex-1 flex-col items-center justify-end gap-2"><span class="text-[10px] font-semibold text-slate-500">{{ $day['count'] ?: '' }}</span><div class="w-full max-w-10 rounded-t-lg bg-sky-600/80 transition hover:bg-sky-700" style="height: {{ max(5, (int) round(($day['count'] / $maxActivity) * 76)) }}%" title="{{ $day['count'] }} reservasi"></div><span class="text-[10px] text-slate-500">{{ $day['label'] }}</span></div>
                            @empty
                                <p class="w-full pb-12 text-center text-xs text-slate-400">Belum ada data aktivitas.</p>
                            @endforelse
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex items-center justify-between gap-3 p-5 sm:px-6"><div><h2 class="text-sm font-bold text-[#17303d]">Reservasi terbaru</h2><p class="mt-1 text-xs text-slate-500">Lima reservasi terakhir</p></div><a href="{{ route('admin.antrean') }}" class="shrink-0 text-xs font-bold text-sky-700 hover:text-sky-900">Lihat antrean <i class="fa-solid fa-arrow-right ml-1"></i></a></div>
                        <div class="overflow-x-auto"><table class="w-full min-w-[520px] text-left text-xs"><thead class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3 font-semibold">Reservasi</th><th class="px-5 py-3 font-semibold">Pelanggan</th><th class="px-5 py-3 font-semibold">Layanan</th><th class="px-5 py-3 font-semibold">Status</th></tr></thead><tbody>
                            @forelse($latestReservations ?? [] as $reservation)
                                @php($status = $reservation['status'] ?? 'menunggu')
                                <tr class="border-t border-slate-100"><td class="px-5 py-3 font-bold text-[#17303d]">#{{ $reservation['id_reservasi'] ?? '—' }}</td><td class="px-5 py-3 text-slate-600">{{ $reservation['nama_pelanggan'] ?? 'Pelanggan' }}</td><td class="px-5 py-3 text-slate-600">{{ $reservation['metode_layanan'] ?? '—' }}</td><td class="px-5 py-3"><span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-semibold text-slate-600">{{ ['di_terima' => 'Diterima', 'sedang_diproses' => 'Diproses', 'menunggu' => 'Menunggu', 'selesai' => 'Selesai', 'diambil' => 'Diambil', 'dibatalkan' => 'Dibatalkan'][$status] ?? ucfirst($status) }}</span></td></tr>
                            @empty
                                <tr><td colspan="4" class="px-5 py-10 text-center text-slate-500">Belum ada reservasi masuk.</td></tr>
                            @endforelse
                        </tbody></table></div>
                    </article>
                </section>
            </div>
        </div>
        <footer class="shrink-0 border-t border-[#dbe5ea] bg-white px-6 py-4 text-center text-xs text-slate-500">&copy; {{ now()->year }} <span class="font-semibold text-slate-600">ALBRK.SHOESCARE</span></footer>
    </main>
    <script>
        document.addEventListener('click', (event) => {
            const menu = document.getElementById('userMenu');
            const button = document.getElementById('userMenuButton');
            if (!menu || !button || button.contains(event.target) || menu.contains(event.target)) return;
            menu.classList.add('hidden');
        });
    </script>
</body>
</html>
