<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Riwayat Reservasi - ALBRK.SHOESCARE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @include('shared.pelanggan-shell-styles')
    <style>body{font-family:'Plus Jakarta Sans',sans-serif;background:#f3f7fa;color:#17303d}.detail-scroll{scrollbar-width:thin;scrollbar-color:#cbd8de transparent}</style>
</head>
<body class="flex h-screen overflow-hidden antialiased">
@include('shared.pelanggan-sidebar', ['showHistoryMenuItems' => true])
<div class="customer-main ml-[218px] flex h-screen min-w-0 flex-1 flex-col">
    @include('shared.pelanggan-header', ['pageTitle' => '', 'showNotificationIcon' => true])
    <main class="detail-scroll flex-1 min-h-0 overflow-y-auto p-3 lg:p-4">
        <div class="flex min-h-full w-full flex-col gap-3">
            @php
                $layananNames = $reservasi->detailReservasis->map(fn ($detail) => ($detail->layanan?->nama_layanan ?? 'Layanan').' × '.($detail->jumlah ?? 1).' pasang')->join(', ');
                $flow = ['menunggu', 'di_terima', 'sedang_diproses', 'selesai', 'diambil'];
                $labels = ['menunggu' => 'Menunggu Konfirmasi', 'di_terima' => 'Dikonfirmasi', 'sedang_diproses' => 'Diproses', 'selesai' => 'Selesai', 'diambil' => 'Diambil'];
                $currentIndex = array_search($reservasi->status, $flow, true);
            @endphp
            <section class="flex flex-wrap items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm sm:p-4">
                <div><p class="text-xs font-semibold text-slate-500">Nomor Reservasi</p><h1 class="mt-1 text-xl font-extrabold text-slate-900">#RSV-{{ str_pad((string) $reservasi->id_reservasi, 8, '0', STR_PAD_LEFT) }}</h1><p class="mt-2 text-xs text-slate-500">Dibuat {{ $reservasi->created_at?->format('d M Y, H:i') ?? $reservasi->tanggal_reservasi?->format('d M Y') ?? '-' }}</p></div>
                <span class="rounded-full px-3 py-2 text-xs font-bold {{ $reservasi->status === 'selesai' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $reservasi->status === 'selesai' ? 'Selesai' : 'Dibatalkan' }}</span>
            </section>
            <div class="grid gap-3 lg:grid-cols-2">
                <section class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                    <h2 class="mb-3 text-sm font-extrabold text-slate-900">Informasi Reservasi</h2>
                    <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                        <div><dt class="text-slate-400">Tanggal reservasi</dt><dd class="mt-1 font-semibold">{{ $reservasi->tanggal_reservasi?->format('d M Y') ?? '-' }}</dd></div>
                        <div><dt class="text-slate-400">Metode layanan</dt><dd class="mt-1 font-semibold">{{ $reservasi->metode_layanan === 'Pick-up' ? 'Antar-Jemput (Pick-up)' : 'Self Drop-off' }}</dd></div>
                        <div><dt class="text-slate-400">Terakhir diperbarui</dt><dd class="mt-1 font-semibold">{{ $reservasi->updated_at?->format('d M Y, H:i') ?? '-' }}</dd></div>
                        <div><dt class="text-slate-400">Jumlah sepatu</dt><dd class="mt-1 font-semibold">{{ $reservasi->jumlah_sepatu ?? $reservasi->detailReservasis->sum('jumlah') }} pasang</dd></div>
                    </dl>
                </section>
                <section class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                    <h2 class="mb-3 text-sm font-extrabold text-slate-900">Informasi Customer</h2>
                    <dl class="space-y-2 text-xs">
                        <div><dt class="text-slate-400">Nama</dt><dd class="mt-1 font-semibold">{{ $reservasi->user?->nama ?? '-' }}</dd></div>
                        <div><dt class="text-slate-400">Email</dt><dd class="mt-1 font-semibold">{{ $reservasi->user?->email ?? '-' }}</dd></div>
                        <div><dt class="text-slate-400">Nomor telepon</dt><dd class="mt-1 font-semibold">{{ $reservasi->user?->no_telp ?? $reservasi->no_hp ?? '-' }}</dd></div>
                    </dl>
                </section>
            </div>
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-3"><h2 class="text-sm font-extrabold text-slate-900">Detail Layanan</h2></div>
                <div class="overflow-x-auto"><table class="w-full min-w-[500px] text-left text-xs">
                    <thead class="bg-slate-50 text-[10px] uppercase text-slate-500"><tr><th class="px-3 py-2">Nama layanan</th><th class="px-3 py-2">Jumlah sepatu</th><th class="px-3 py-2">Harga satuan</th><th class="px-3 py-2">Subtotal</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($reservasi->detailReservasis as $detail)
                            <tr><td class="px-3 py-2 font-semibold">{{ $detail->layanan?->nama_layanan ?? 'Layanan dihapus' }}</td><td class="px-3 py-2">{{ $detail->jumlah ?? $reservasi->jumlah_sepatu }} pasang</td><td class="px-3 py-2">Rp{{ number_format($detail->harga, 0, ',', '.') }}</td><td class="px-3 py-2 font-bold">Rp{{ number_format($detail->sub_total ?? ($detail->harga * $detail->jumlah), 0, ',', '.') }}</td></tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-6 text-center text-slate-400">Detail layanan tidak tersedia.</td></tr>
                        @endforelse
                    </tbody>
                </table></div>
                <div class="flex justify-end border-t border-slate-100 px-3 py-2 text-sm font-extrabold">Total: <span class="ml-2 text-slate-900">Rp{{ number_format($reservasi->total_harga, 0, ',', '.') }}</span></div>
            </section>
            <div class="grid gap-3 lg:grid-cols-2">
                <section class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                    <h2 class="mb-3 text-sm font-extrabold text-slate-900">Metode Layanan</h2>
                    <p class="text-xs font-semibold">{{ $reservasi->metode_layanan === 'Pick-up' ? 'Antar-Jemput (Pick-up)' : 'Self Drop-off' }}</p>
                    @if($reservasi->metode_layanan === 'Pick-up')
                        <dl class="mt-3 space-y-2 text-xs"><div><dt class="text-slate-400">Alamat pickup</dt><dd class="mt-1 font-medium">{{ $reservasi->alamat_jemput ?? 'Alamat pickup tidak tersedia' }}</dd></div><div><dt class="text-slate-400">Alamat pengantaran</dt><dd class="mt-1 font-medium">{{ $reservasi->alamat_pengantaran ?? 'Tidak dicantumkan' }}</dd></div></dl>
                    @else
                        <p class="mt-3 text-xs leading-relaxed text-slate-500">Sepatu diantar langsung ke toko (drop-off).</p>
                    @endif
                    @if($reservasi->catatan)<p class="mt-3 text-xs"><span class="text-slate-400">Catatan customer:</span> {{ $reservasi->catatan }}</p>@endif
                </section>
                <section class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                    <h2 class="mb-3 text-sm font-extrabold text-slate-900">Pembayaran</h2>
                    <dl class="space-y-2 text-xs"><div class="flex justify-between gap-3"><dt class="text-slate-500">Total pembayaran</dt><dd class="font-bold">Rp{{ number_format($reservasi->total_harga, 0, ',', '.') }}</dd></div><div class="flex justify-between gap-3"><dt class="text-slate-500">Status pembayaran</dt><dd class="font-bold">{{ $reservasi->status_bayar ?: '-' }}</dd></div><div class="flex justify-between gap-3"><dt class="text-slate-500">Metode pembayaran</dt><dd class="font-bold">{{ $reservasi->metode_bayar ?: '-' }}</dd></div><div class="flex justify-between gap-3"><dt class="text-slate-500">Tanggal bayar</dt><dd class="font-bold">{{ $reservasi->tanggal_bayar?->format('d M Y H:i') ?? '-' }}</dd></div></dl>
                </section>
            </div>
            <section class="flex-1 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                <h2 class="mb-3 text-sm font-extrabold text-slate-900">Timeline Reservasi</h2>
                @if($reservasi->status === 'dibatalkan')
                    <div class="mb-4 rounded-xl bg-rose-50 p-3 text-xs text-rose-800"><b>Reservasi dibatalkan.</b> {{ $reservasi->alasan_pembatalan }}</div>
                @endif
                <div class="relative mt-2"><div class="absolute left-[10%] right-[10%] top-[14px] h-0.5 rounded bg-slate-200"></div>@if($currentIndex !== false)<div class="absolute left-[10%] top-[14px] h-0.5 rounded bg-emerald-500" style="width: {{ max(0, min(80, $currentIndex * 20)) }}%"></div>@endif
                    <div class="relative grid grid-cols-5 gap-0">@foreach($flow as $index => $stage)<div class="relative text-center"><span class="mx-auto grid h-7 w-7 place-items-center rounded-full text-[10px] font-bold {{ $currentIndex !== false && $currentIndex >= $index ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400' }}">@if($currentIndex !== false && $currentIndex > $index)<i class="fa-solid fa-check"></i>@else{{ $index + 1 }}@endif</span><p class="mt-1 text-[9px] leading-tight {{ $currentIndex === $index ? 'font-bold text-emerald-700' : ($currentIndex !== false && $currentIndex > $index ? 'text-emerald-600' : 'text-slate-500') }}">{{ $labels[$stage] }}</p></div>@endforeach</div>
                </div>
            </section>
        </div>
    </main>
    <footer class="shrink-0 border-t border-[#dbe5ea] bg-white px-6 py-4 text-center text-xs text-slate-500">&copy; {{ now()->year }} <span class="font-semibold text-slate-600">ALBRK.SHOESCARE</span></footer>
</div>
</body>
</html>
