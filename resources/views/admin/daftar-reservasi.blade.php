<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi - ALBRK SHOESCARE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>body{font-family:'Plus Jakarta Sans',sans-serif;background:#f3f7fa;color:#17303d}.admin-scroll{scrollbar-width:thin;scrollbar-color:#cbd8de transparent}.admin-scroll::-webkit-scrollbar{width:6px;height:6px}.admin-scroll::-webkit-scrollbar-thumb{background:#cbd8de;border-radius:8px}</style>
    @include('shared.albrk-redesign')
</head>
@php
    $reservasiRoutePrefix = auth()->user()->isSuperAdmin() ? 'superadmin.reservasi' : 'admin.reservasi';
@endphp
<body class="flex h-screen overflow-hidden antialiased">
@if(auth()->user()->isSuperAdmin())
@include('shared.superadmin-sidebar')
@else
@include('shared.admin-sidebar')
@endif
<main class="flex h-screen min-w-0 flex-1 flex-col bg-[#f3f7fa]">
    <header class="flex shrink-0 items-center justify-between border-b border-[#29424c] bg-[#17303d] px-5 py-3.5 text-white md:px-8 z-40">
    <div class="flex items-center gap-3 md:gap-4"></div>
    @include('shared.admin-header-actions')
</header>
    <div class="admin-scroll flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8"><div class="mx-auto max-w-[1600px] space-y-5">
        @if(session('success'))<div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $errors->first() }}</div>@endif
        <section class="grid grid-cols-2 gap-3 xl:grid-cols-4" aria-label="Ringkasan reservasi">
            @foreach([['Total reservasi',$stats['total'],'fa-box','sky'],['Menunggu Diproses',$stats['menunggu'],'fa-hourglass-half','amber'],['Sedang Diproses',$stats['diproses'],'fa-gear','violet'],['Selesai',$stats['selesai'],'fa-circle-check','emerald']] as [$label,$value,$icon,$tone])
                <article class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"><span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-{{ $tone }}-50 text-{{ $tone }}-700"><i class="fa-solid {{ $icon }}"></i></span><div><p class="text-xl font-extrabold text-slate-900 sm:text-2xl">{{ $value }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $label }}</p></div></article>
            @endforeach
        </section>
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6"><h2 class="text-base font-bold text-slate-900">Daftar reservasi</h2></div>
            <form method="GET" action="{{ route($reservasiRoutePrefix . '.index') }}" class="grid gap-3 border-b border-slate-100 bg-slate-50/70 p-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-7 lg:p-5">
                <label class="relative sm:col-span-2"><span class="invisible mb-1 block text-[10px]" aria-hidden="true">Cari</span><span class="sr-only">Cari nomor reservasi atau nama customer</span><i class="fa-solid fa-magnifying-glass absolute left-3 top-[calc(50%+10px)] -translate-y-1/2 text-xs text-slate-400"></i><input type="search" name="q" value="{{ $filters['q'] ?? '' }}" maxlength="100" placeholder="Cari nomor reservasi atau nama customer..." class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 text-xs outline-none focus:border-slate-400"></label>
                <label><span class="mb-1 block text-[10px] font-semibold text-slate-500">Status</span><select name="status" class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs"><option value="">Semua status</option>@foreach(['di_terima'=>'Dikonfirmasi','sedang_diproses'=>'Diproses','selesai'=>'Selesai','diambil'=>'Diambil / Dikirim','dibatalkan'=>'Dibatalkan'] as $value=>$label)<option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>@endforeach</select></label>
                <label><span class="mb-1 block text-[10px] font-semibold text-slate-500">Metode layanan</span><select name="metode_layanan" class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs"><option value="">Semua metode</option><option value="Drop-off" @selected(($filters['metode_layanan'] ?? '') === 'Drop-off')>Self Drop-off</option><option value="Pick-up" @selected(($filters['metode_layanan'] ?? '') === 'Pick-up')>Antar-Jemput</option></select></label>
                <label><span class="mb-1 block text-[10px] font-semibold text-slate-500">Tanggal mulai</span><input type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] ?? '' }}" class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs"></label>
                <label><span class="mb-1 block text-[10px] font-semibold text-slate-500">Tanggal akhir</span><input type="date" name="tanggal_akhir" value="{{ $filters['tanggal_akhir'] ?? '' }}" min="{{ $filters['tanggal_mulai'] ?? '' }}" class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs"></label>
                <label><span class="mb-1 block text-[10px] font-semibold text-slate-500">Layanan</span><select name="id_layanan" class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs"><option value="">Semua layanan</option>@foreach($layanans as $layanan)<option value="{{ $layanan->id_layanan }}" @selected(($filters['id_layanan'] ?? '') == $layanan->id_layanan)>{{ $layanan->nama_layanan }}</option>@endforeach</select></label>
                <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-3 xl:col-span-7"><button class="inline-flex h-10 items-center gap-2 rounded-xl bg-[#17303d] px-4 text-xs font-semibold text-white hover:bg-[#244653]"><i class="fa-solid fa-filter"></i>Terapkan Filter</button><a href="{{ route($reservasiRoutePrefix . '.index') }}" class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-600 hover:bg-slate-100"><i class="fa-solid fa-rotate-left"></i>Reset Filter</a></div>
            </form>
            @php
                $labels=['di_terima'=>'Dikonfirmasi','sedang_diproses'=>'Diproses','selesai'=>'Selesai','diambil'=>'Diambil / Dikirim','dibatalkan'=>'Dibatalkan'];
                $badges=['di_terima'=>'bg-amber-50 text-amber-700','sedang_diproses'=>'bg-blue-50 text-blue-700','selesai'=>'bg-emerald-50 text-emerald-700','diambil'=>'bg-teal-50 text-teal-700','dibatalkan'=>'bg-rose-50 text-rose-700'];
            @endphp
            <div class="hidden overflow-x-auto md:block"><table class="w-full min-w-[1050px] text-left text-xs"><thead class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500"><tr><th class="px-4 py-3">No.</th><th class="px-4 py-3">Nomor reservasi</th><th class="px-4 py-3">Customer</th><th class="px-4 py-3">Layanan</th><th class="px-4 py-3">Jumlah Sepatu</th><th class="px-4 py-3">Tanggal Masuk</th><th class="px-4 py-3">Estimasi Selesai</th><th class="px-4 py-3">Total</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100">
                @forelse($reservasis as $reservation)
                    @php($number=($reservation->tanggal_reservasi?->format('Ymd') ?? now()->format('Ymd')).'-'.str_pad((string)$reservation->id_reservasi,3,'0',STR_PAD_LEFT))
                    <tr class="hover:bg-slate-50/70"><td class="px-4 py-4 text-slate-500">{{ $reservasis->firstItem()+$loop->index }}</td><td class="whitespace-nowrap px-4 py-4 font-bold text-slate-900">#RSV-{{ $number }}</td><td class="px-4 py-4 font-semibold">{{ $reservation->user?->nama ?? $reservation->nama_pelanggan ?? 'Customer' }}</td><td class="max-w-[180px] px-4 py-4">{{ $reservation->detailReservasis->map(fn ($detail) => ($detail->layanan?->nama_layanan ?? 'Layanan').' × '.($detail->jumlah ?? 1))->join(', ') ?: '—' }}</td><td class="px-4 py-4">{{ $reservation->jumlah_sepatu ?? $reservation->detailReservasis->sum('jumlah') }} pasang</td><td class="whitespace-nowrap px-4 py-4">{{ $reservation->tanggal_reservasi?->format('d M Y') ?? '—' }}</td><td class="px-4 py-4 text-slate-500">Belum dicatat</td><td class="whitespace-nowrap px-4 py-4 font-bold">Rp{{ number_format($reservation->total_harga,0,',','.') }}</td><td class="px-4 py-4"><span class="whitespace-nowrap rounded-full px-2.5 py-1.5 text-[10px] font-bold {{ $badges[$reservation->status] ?? 'bg-slate-100 text-slate-600' }}">{{ $labels[$reservation->status] ?? $reservation->status }}</span></td><td class="px-4 py-4"><a href="{{ route($reservasiRoutePrefix . '.show',$reservation->id_reservasi) }}" class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-lg border border-slate-200 px-3 py-2 text-[10px] font-bold text-slate-700 hover:bg-[#17303d] hover:text-white">Lihat Detail <i class="fa-solid fa-arrow-right"></i></a></td></tr>
                @empty<tr><td colspan="10" class="px-4 py-16 text-center"><i class="fa-solid fa-box-open text-3xl text-slate-300"></i><p class="mt-3 font-semibold text-slate-700">Belum ada reservasi</p><p class="mt-1 text-xs text-slate-500">reservasi muncul setelah reservasi dikonfirmasi.</p></td></tr>@endforelse
            </tbody></table></div>
            <div class="divide-y divide-slate-100 md:hidden">@forelse($reservasis as $reservation)
                @php($number=($reservation->tanggal_reservasi?->format('Ymd') ?? now()->format('Ymd')).'-'.str_pad((string)$reservation->id_reservasi,3,'0',STR_PAD_LEFT))
                <article class="space-y-3 p-4"><div class="flex items-start justify-between gap-3"><div><p class="text-xs font-extrabold text-slate-900">#RSV-{{ $number }}</p><p class="mt-1 text-xs text-slate-500">{{ $reservation->user?->nama ?? $reservation->nama_pelanggan ?? 'Customer' }}</p></div><span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $badges[$reservation->status] ?? 'bg-slate-100 text-slate-600' }}">{{ $labels[$reservation->status] ?? $reservation->status }}</span></div><div class="grid grid-cols-2 gap-2 text-[11px]"><p><span class="text-slate-400">Tanggal masuk</span><br><b>{{ $reservation->tanggal_reservasi?->format('d M Y') ?? '—' }}</b></p><p><span class="text-slate-400">Jumlah</span><br><b>{{ $reservation->jumlah_sepatu ?? $reservation->detailReservasis->sum('jumlah') }} pasang</b></p><p class="col-span-2"><span class="text-slate-400">Layanan</span><br><b>{{ $reservation->detailReservasis->map(fn ($detail) => ($detail->layanan?->nama_layanan ?? 'Layanan').' × '.($detail->jumlah ?? 1))->join(', ') ?: '—' }}</b></p><p><span class="text-slate-400">Metode</span><br><b>{{ $reservation->metode_layanan==='Pick-up'?'Antar-Jemput':'Self Drop-off' }}</b></p><p><span class="text-slate-400">Total</span><br><b>Rp{{ number_format($reservation->total_harga,0,',','.') }}</b></p></div><a href="{{ route($reservasiRoutePrefix . '.show',$reservation->id_reservasi) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-[10px] font-bold">Lihat Detail <i class="fa-solid fa-arrow-right"></i></a></article>
            @empty<div class="p-12 text-center text-sm text-slate-500">Belum ada reservasi.</div>@endforelse</div>
            @if($reservasis->hasPages())<div class="border-t border-slate-100 px-4 py-4 sm:px-6">{{ $reservasis->links() }}</div>@endif
        </section>
    </div></div>
    <footer class="shrink-0 border-t border-[#dbe5ea] bg-white px-6 py-4 text-center text-xs text-slate-500">&copy; {{ now()->year }} <span class="font-semibold text-slate-600">ALBRK.SHOESCARE</span></footer>
</main>
</body></html>
