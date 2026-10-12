<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin - ALBRK.SHOESCARE</title>
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
    @include('shared.superadmin-sidebar')

    <main class="flex h-screen min-w-0 flex-1 flex-col bg-[#f3f7fa]">
        <header class="z-40 flex shrink-0 items-center justify-between border-b border-[#29424c] bg-[#17303d] px-5 py-3.5 text-white md:px-8">
            <div></div>
            @include('shared.admin-header-actions')
        </header>

        <div class="dashboard-scroll flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="mx-auto max-w-[1500px] space-y-6">
                <section class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="pointer-events-none absolute -right-12 -top-20 h-64 w-64 rounded-full bg-sky-100/80 blur-3xl"></div>
                    <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-[#17303d] sm:text-3xl">Selamat datang, {{ auth()->user()->nama ?? 'Superadmin' }}</h1>
                            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-500">Pantau reservasi, pelanggan, dan omzet ALBRK.SHOESCARE dari satu halaman.</p>
                        </div>
                        <a href="{{ route('superadmin.laporan') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-[#17303d] px-4 py-3 text-xs font-bold text-white transition hover:bg-[#244653]"><i class="fa-solid fa-chart-column"></i>Buka laporan</a>
                    </div>
                </section>

                <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Statistik bisnis">
                    @php
                        $cards = [
                            ['label' => 'Antrean aktif', 'value' => $stats['total_antrean'] ?? 0, 'icon' => 'fa-clock', 'tone' => 'sky'],
                            ['label' => 'Reservasi selesai', 'value' => $stats['total_selesai'] ?? 0, 'icon' => 'fa-circle-check', 'tone' => 'emerald'],
                            ['label' => 'Total pelanggan', 'value' => $stats['total_pelanggan'] ?? 0, 'icon' => 'fa-users', 'tone' => 'violet'],
                            ['label' => 'Total omzet', 'value' => 'Rp '.number_format($stats['total_omzet'] ?? 0, 0, ',', '.'), 'icon' => 'fa-wallet', 'tone' => 'amber'],
                        ];
                    @endphp
                    @foreach($cards as $card)
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <div class="flex items-center justify-between">
                                <span class="grid h-11 w-11 place-items-center rounded-xl bg-{{ $card['tone'] }}-50 text-{{ $card['tone'] }}-700"><i class="fa-solid {{ $card['icon'] }}"></i></span>
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Ringkasan</span>
                            </div>
                            <p class="mt-5 break-words text-2xl font-extrabold tracking-tight text-[#17303d]">{{ $card['value'] }}</p>
                            <p class="mt-1 text-xs font-medium text-slate-500">{{ $card['label'] }}</p>
                        </article>
                    @endforeach
                </section>

                <section>
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @php
                            $menus = [
                                ['url' => route('superadmin.layanan.index'), 'icon' => 'fa-shoe-prints', 'title' => 'Master Layanan', 'desc' => 'Atur katalog, harga, dan foto layanan', 'tone' => 'sky'],
                                ['url' => route('superadmin.laporan'), 'icon' => 'fa-chart-line', 'title' => 'Laporan', 'desc' => 'Tinjau reservasi dan performa omzet', 'tone' => 'emerald'],
                                ['url' => route('superadmin.users'), 'icon' => 'fa-users-gear', 'title' => 'Manajemen Pengguna', 'desc' => 'Kelola akun pelanggan dan admin', 'tone' => 'violet'],
                            ];
                        @endphp
                        @foreach($menus as $menu)
                            <a href="{{ $menu['url'] }}" class="group flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md">
                                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-{{ $menu['tone'] }}-50 text-{{ $menu['tone'] }}-700"><i class="fa-solid {{ $menu['icon'] }}"></i></span>
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
                        <div class="flex items-center justify-between gap-3 p-5 sm:px-6"><div><h2 class="text-sm font-bold text-[#17303d]">Reservasi terbaru</h2><p class="mt-1 text-xs text-slate-500">Pembaruan reservasi pelanggan</p></div><a href="{{ route('superadmin.laporan') }}" class="shrink-0 text-xs font-bold text-sky-700 hover:text-sky-900">Lihat laporan <i class="fa-solid fa-arrow-right ml-1"></i></a></div>
                        <div class="overflow-x-auto"><table class="w-full min-w-[520px] text-left text-xs"><thead class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3 font-semibold">Reservasi</th><th class="px-5 py-3 font-semibold">Pelanggan</th><th class="px-5 py-3 font-semibold">Tanggal</th><th class="px-5 py-3 font-semibold">Status</th></tr></thead><tbody>
                            @forelse($latestReservations ?? [] as $reservation)
                                @php($status = $reservation['status'] ?? 'menunggu')
                                <tr class="border-t border-slate-100"><td class="px-5 py-3 font-bold text-[#17303d]">#{{ $reservation['id_reservasi'] ?? '—' }}</td><td class="px-5 py-3 text-slate-600">{{ $reservation['nama_pelanggan'] ?: 'Pelanggan' }}</td><td class="px-5 py-3 whitespace-nowrap text-slate-500">{{ !empty($reservation['tanggal_reservasi']) ? \Carbon\Carbon::parse($reservation['tanggal_reservasi'])->format('d M Y') : '—' }}</td><td class="px-5 py-3"><span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-semibold text-slate-600">{{ ['di_terima' => 'Diterima', 'sedang_diproses' => 'Diproses', 'menunggu' => 'Menunggu', 'selesai' => 'Selesai', 'diambil' => 'Diambil', 'dibatalkan' => 'Dibatalkan'][$status] ?? ucfirst($status) }}</span></td></tr>
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
