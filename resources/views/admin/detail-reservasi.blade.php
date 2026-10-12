<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail reservasi - ALBRK SHOESCARE</title>
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
@if(auth()->user()->isSuperAdmin())
    @include('shared.superadmin-sidebar')
@else
    @include('shared.admin-sidebar')
@endif
@endif
<main class="flex h-screen min-w-0 flex-1 flex-col bg-[#f3f7fa]">
    <header class="flex shrink-0 items-center justify-between border-b border-[#29424c] bg-[#17303d] px-5 py-3.5 text-white md:px-8 z-40">
    <div class="flex items-center gap-3 md:gap-4"></div>
    @include('shared.admin-header-actions')
</header>
    <div class="admin-scroll flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8"><div class="mx-auto max-w-6xl space-y-5">
        @if(session('success'))<div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $errors->first() }}</div>@endif
        @php
            $number=($reservasi->tanggal_reservasi?->format('Ymd') ?? now()->format('Ymd')).'-'.str_pad((string)$reservasi->id_reservasi,3,'0',STR_PAD_LEFT);
            $labels=['di_terima'=>'Dikonfirmasi','sedang_diproses'=>'Diproses','selesai'=>'Selesai · Siap Diambil/Dikirim','diambil'=>'Diambil / Dikirim','dibatalkan'=>'Dibatalkan'];
            $badge=['di_terima'=>'bg-amber-50 text-amber-700','sedang_diproses'=>'bg-blue-50 text-blue-700','selesai'=>'bg-emerald-50 text-emerald-700','diambil'=>'bg-teal-50 text-teal-700','dibatalkan'=>'bg-rose-50 text-rose-700'];
            $current=$reservasi->status;
            $next=['di_terima'=>'sedang_diproses','sedang_diproses'=>'selesai'][$current] ?? null;
            $flow=['menunggu'=>'Reservasi','di_terima'=>'Dikonfirmasi','sedang_diproses'=>'Diproses','selesai'=>'Selesai / Siap Diambil atau Dikirim','diambil'=>'Diambil / Dikirim'];
            $stepMap=['di_terima'=>1,'sedang_diproses'=>2,'selesai'=>3,'diambil'=>4];
            $step=$stepMap[$current] ?? -1;
            $latestPayment=$reservasi->pembayaranRecords->first();
            $paymentStatus=strtolower((string)($reservasi->status_bayar ?? $latestPayment?->status));
            $paymentLabel=in_array($paymentStatus,['lunas','settlement','capture','berhasil'],true)?'Lunas':(in_array($paymentStatus,['gagal','expire','expired','cancel','deny'],true)?'Gagal':'Belum Lunas');
            $remaining=max(0,(int)$reservasi->total_harga-(int)$paid);
            $serviceMethod=$reservasi->metode_layanan==='Pick-up'?'Antar-Jemput':'Self Drop-off';
        @endphp
        <a href="{{ route($reservasiRoutePrefix . '.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-900"><i class="fa-solid fa-arrow-left"></i>Kembali ke reservasi</a>
        <section class="flex flex-wrap items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7"><div><p class="text-xs font-semibold text-slate-500">Nomor reservasi · Nomor Reservasi</p><h2 class="mt-1 text-xl font-extrabold text-slate-900">#RSV-{{ $number }}</h2><p class="mt-2 text-xs text-slate-500">Dibuat {{ $reservasi->created_at?->format('d M Y, H:i') ?? '—' }} · Tanggal reservasi {{ $reservasi->tanggal_reservasi?->format('d M Y') ?? '—' }}</p></div><span class="rounded-full px-3 py-2 text-xs font-bold {{ $badge[$current] ?? 'bg-slate-100 text-slate-600' }}">{{ $labels[$current] ?? $current }}</span></section>
        <div class="grid gap-5 lg:grid-cols-2">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><h3 class="mb-4 text-sm font-extrabold text-slate-900">Informasi Reservasi</h3><dl class="grid grid-cols-2 gap-x-4 gap-y-4 text-xs"><div><dt class="text-slate-400">Nomor reservasi</dt><dd class="mt-1 font-semibold">#RSV-{{ $number }}</dd></div><div><dt class="text-slate-400">Nomor reservasi</dt><dd class="mt-1 font-semibold">#RSV-{{ $number }}</dd></div><div><dt class="text-slate-400">Status</dt><dd class="mt-1 font-semibold">{{ $labels[$current] ?? $current }}</dd></div><div><dt class="text-slate-400">Estimasi selesai</dt><dd class="mt-1 font-semibold text-slate-500">Tidak dicatat</dd></div><div><dt class="text-slate-400">Terakhir diperbarui</dt><dd class="mt-1 font-semibold">{{ $reservasi->updated_at?->format('d M Y, H:i') ?? '—' }}</dd></div><div><dt class="text-slate-400">Admin perubahan terakhir</dt><dd class="mt-1 font-semibold">{{ $reservasi->statusDiubahOleh?->nama ?? 'Belum dicatat' }}</dd></div></dl></section>
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><h3 class="mb-4 text-sm font-extrabold text-slate-900">Customer</h3><dl class="space-y-3 text-xs"><div><dt class="text-slate-400">Nama</dt><dd class="mt-1 font-semibold">{{ $reservasi->user?->nama ?? $reservasi->nama_pelanggan ?? '—' }}</dd></div><div><dt class="text-slate-400">Email</dt><dd class="mt-1 font-semibold">{{ $reservasi->user?->email ?? '—' }}</dd></div><div><dt class="text-slate-400">Nomor telepon</dt><dd class="mt-1 font-semibold">{{ $reservasi->user?->no_telp ?? $reservasi->no_hp ?? '—' }}</dd></div></dl></section>
        </div>
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"><div class="border-b border-slate-100 p-5"><h3 class="text-sm font-extrabold text-slate-900">Detail Layanan</h3></div><div class="overflow-x-auto"><table class="w-full min-w-[500px] text-left text-xs"><thead class="bg-slate-50 text-[10px] uppercase text-slate-500"><tr><th class="px-5 py-3">Nama layanan</th><th class="px-5 py-3">Jumlah</th><th class="px-5 py-3">Harga</th><th class="px-5 py-3">Subtotal</th><th class="px-5 py-3">Foto sepatu</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($reservasi->detailReservasis as $detail)<tr><td class="px-5 py-4 font-semibold">{{ $detail->layanan?->nama_layanan ?? 'Layanan dihapus' }}</td><td class="px-5 py-4">{{ $detail->jumlah ?? $reservasi->jumlah_sepatu }} pasang</td><td class="px-5 py-4">Rp{{ number_format($detail->harga,0,',','.') }}</td><td class="px-5 py-4 font-bold">Rp{{ number_format($detail->sub_total ?? ($detail->harga*$detail->jumlah),0,',','.') }}</td><td class="px-5 py-4 text-slate-400">Tidak tersedia</td></tr>@empty<tr><td colspan="5" class="px-5 py-6 text-center text-slate-400">Detail layanan tidak tersedia.</td></tr>@endforelse</tbody></table></div><div class="flex justify-end border-t border-slate-100 px-5 py-4 text-sm font-extrabold">Subtotal: <span class="ml-2 text-slate-900">Rp{{ number_format($reservasi->total_harga,0,',','.') }}</span></div></section>
        <div class="grid gap-5 lg:grid-cols-2">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><h3 class="mb-4 text-sm font-extrabold text-slate-900">Metode Layanan</h3><p class="text-xs font-semibold">{{ $serviceMethod }}</p>@if($reservasi->metode_layanan==='Pick-up')<dl class="mt-4 space-y-3 text-xs"><div><dt class="text-slate-400">Alamat pickup</dt><dd class="mt-1 font-medium">{{ $reservasi->alamat_jemput ?? 'Tidak dicantumkan' }}</dd></div><div><dt class="text-slate-400">Alamat pengantaran</dt><dd class="mt-1 font-medium">{{ $reservasi->alamat_pengantaran ?? 'Tidak dicantumkan' }}</dd></div><div><dt class="text-slate-400">Catatan</dt><dd class="mt-1 font-medium">{{ $reservasi->catatan ?? 'Tidak ada catatan' }}</dd></div></dl>@elseif($reservasi->catatan)<p class="mt-3 text-xs"><span class="text-slate-400">Catatan:</span> {{ $reservasi->catatan }}</p>@endif</section>
            <section id="pembayaran" class="scroll-mt-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="mb-4 flex items-center justify-between gap-3"><h3 class="text-sm font-extrabold text-slate-900">Pembayaran</h3><a href="#pembayaran" class="text-[10px] font-bold text-sky-700 hover:underline">Lihat Pembayaran</a></div><dl class="space-y-3 text-xs"><div class="flex justify-between gap-3"><dt class="text-slate-500">Total tagihan</dt><dd class="font-bold">Rp{{ number_format($reservasi->total_harga,0,',','.') }}</dd></div><div class="flex justify-between gap-3"><dt class="text-slate-500">Sudah dibayar</dt><dd class="font-bold">Rp{{ number_format($paid,0,',','.') }}</dd></div><div class="flex justify-between gap-3"><dt class="text-slate-500">Sisa pembayaran</dt><dd class="font-bold">Rp{{ number_format($remaining,0,',','.') }}</dd></div><div class="flex justify-between gap-3"><dt class="text-slate-500">Status pembayaran</dt><dd class="font-bold">{{ $paymentLabel }}</dd></div><div class="flex justify-between gap-3"><dt class="text-slate-500">Metode pembayaran</dt><dd class="font-bold">{{ $reservasi->metode_bayar ?? $latestPayment?->metode_bayar ?? $latestPayment?->metode ?? 'Belum dipilih' }}</dd></div></dl>@if($latestPayment?->bukti_pembayaran)<a target="_blank" rel="noopener" href="{{ route('media.public',['path'=>$latestPayment->bukti_pembayaran]) }}" class="mt-4 inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-sky-700"><i class="fa-regular fa-image"></i>Lihat Bukti Pembayaran</a>@else<p class="mt-3 text-[10px] text-slate-400">Bukti pembayaran tidak tersedia.</p>@endif</section>
        </div>
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><h3 class="mb-5 text-sm font-extrabold text-slate-900">Timeline reservasi</h3><div class="grid grid-cols-2 gap-y-4 sm:grid-cols-5">@foreach($flow as $status=>$label)@php($idx=$stepMap[$status] ?? 0)<div class="flex items-center gap-2 sm:flex-col sm:text-center"><span class="grid h-8 w-8 shrink-0 place-items-center rounded-full text-xs font-bold {{ $step >= $idx ? 'bg-sky-700 text-white' : 'bg-slate-100 text-slate-400' }}">@if($step > $idx)<i class="fa-solid fa-check"></i>@else{{ $idx + 1 }}@endif</span><span class="text-[10px] {{ $step === $idx ? 'font-bold text-sky-700' : 'text-slate-500' }}">{{ $label }}</span></div>@endforeach</div><p class="mt-4 text-[10px] text-slate-400">Tahap Sepatu Diterima dan Quality Check belum tersedia sebagai status terpisah. Riwayat perubahan status juga belum memiliki tabel/model khusus.</p></section>
        <section class="flex flex-wrap items-center gap-2 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">@if($next)<form method="POST" action="{{ route($reservasiRoutePrefix . '.update',$reservasi->id_reservasi) }}" onsubmit="return confirm('Apakah Anda yakin mengubah status reservasi?')">@csrf<input type="hidden" name="status" value="{{ $next }}"><button class="rounded-xl bg-[#17303d] px-4 py-3 text-xs font-bold text-white hover:bg-sky-800">{{ $next==='selesai'?'Tandai Selesai':'Mulai Diproses' }}</button></form>@endif<span class="text-[10px] text-slate-500">Estimasi selesai, biaya tambahan, diskon, dan foto sepatu tidak memiliki data tersimpan pada struktur saat ini.</span></section>
    </div></div>
    <footer class="shrink-0 border-t border-[#dbe5ea] bg-white px-6 py-4 text-center text-xs text-slate-500">&copy; {{ now()->year }} <span class="font-semibold text-slate-600">ALBRK.SHOESCARE</span></footer>
</main>
</body></html>
