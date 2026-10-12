<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembayaran - ALBRK SHOESCARE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>body{font-family:'Plus Jakarta Sans',sans-serif;background:#f3f7fa;color:#17303d}.admin-scroll{scrollbar-width:none;-ms-overflow-style:none}.admin-scroll::-webkit-scrollbar{display:none}</style>
    @include('shared.albrk-redesign')
</head>
@php
    $paymentRoutePrefix = auth()->user()->isSuperAdmin() ? 'superadmin.pembayaran' : 'admin.pembayaran';
@endphp
<body class="flex h-screen overflow-hidden antialiased">
@if(auth()->user()->isSuperAdmin())
@include('shared.superadmin-sidebar')
@else
@include('shared.admin-sidebar')
@endif
<main class="flex h-screen min-w-0 flex-1 flex-col bg-[#f3f7fa]">
    <header class="relative flex shrink-0 items-center justify-between border-b border-[#29424c] bg-[#17303d] px-5 py-3.5 text-white md:px-8">
        <h1 class="text-sm font-bold sm:text-base">Detail Pembayaran</h1>
        <div class="ml-auto flex items-center gap-3 sm:gap-5">
            <a href="{{ route($paymentRoutePrefix . '.index') }}" class="rounded-lg border border-white/20 px-3 py-2 text-xs font-semibold text-white hover:bg-white/10"><i class="fa-solid fa-arrow-left mr-1"></i>Kembali</a>
            @include('shared.admin-header-actions')
        </div>
    </header>
    <div class="admin-scroll flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8"><div class="mx-auto max-w-5xl space-y-5">
        @if(session('success'))<div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>@endif
        @if($errors->any())<div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ $errors->first() }}</div>@endif
        @php
            $reservation=$payment->reservasi;
            $status=strtolower((string)$payment->status);
            [$label,$tone]=match(true){$status==='lunas'=>['Berhasil','emerald'],$status==='menunggu'=>['Menunggu Verifikasi','amber'],in_array($status,['gagal','ditolak'],true)=>['Ditolak','rose'],$status==='dikembalikan'=>['Dikembalikan','slate'],default=>[ucfirst($status),'slate']};
            $orderNumber='RSV-'.($reservation?->tanggal_reservasi?->format('Ymd') ?? '--------').'-'.str_pad((string)$payment->id_reservasi,3,'0',STR_PAD_LEFT);
            $proof=$payment->bukti_pembayaran;
            $proofUrl=$proof ? (str_starts_with($proof,'http://') || str_starts_with($proof,'https://') ? $proof : route('media.public',['path'=>$proof])) : null;
            $proofIsImage=$proof && in_array(strtolower(pathinfo($proof,PATHINFO_EXTENSION)),['jpg','jpeg','png','webp','gif'],true);
        @endphp
        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-7">
            <div><p class="text-xs font-semibold text-slate-500">ID Pembayaran</p><h2 class="mt-1 text-2xl font-extrabold text-slate-900">#PAY-{{ str_pad((string)$payment->id_pembayaran,5,'0',STR_PAD_LEFT) }}</h2><p class="mt-2 text-xs text-slate-500">Reservasi #{{ $orderNumber }}</p></div>
            <span class="inline-flex w-fit rounded-full bg-{{ $tone }}-50 px-3 py-1.5 text-xs font-semibold text-{{ $tone }}-700 ring-1 ring-inset ring-{{ $tone }}-200">{{ $label }}</span>
        </section>
        <div class="grid gap-5 lg:grid-cols-2">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6"><h3 class="mb-4 text-sm font-bold text-slate-900">Informasi Pembayaran</h3><dl class="space-y-3 text-xs">
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">Tanggal pembayaran</dt><dd class="text-right font-semibold text-slate-800">{{ ($payment->tanggal_bayar ?? $payment->created_at)?->translatedFormat('d M Y H:i') ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">Metode</dt><dd class="text-right font-semibold text-slate-800">{{ ucfirst($payment->metode_bayar ?: $payment->metode ?: '—') }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">Jumlah pembayaran</dt><dd class="text-right font-bold text-slate-900">Rp {{ number_format((int)$payment->jumlah,0,',','.') }}</dd></div>
                @if($payment->midtrans_order_id)<div class="flex justify-between gap-4"><dt class="text-slate-500">Referensi gateway</dt><dd class="max-w-[65%] break-all text-right font-medium text-slate-700">{{ $payment->midtrans_order_id }}</dd></div>@endif
            </dl></section>
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6"><h3 class="mb-4 text-sm font-bold text-slate-900">Informasi Reservasi</h3><dl class="space-y-3 text-xs">
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">No. Reservasi</dt><dd class="text-right font-semibold text-slate-800">#{{ $orderNumber }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">Tanggal reservasi</dt><dd class="text-right font-semibold text-slate-800">{{ $reservation?->tanggal_reservasi?->translatedFormat('d M Y') ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">Jumlah sepatu</dt><dd class="text-right font-semibold text-slate-800">{{ $reservation?->jumlah_sepatu ?? 0 }} pasang</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">Status reservasi</dt><dd class="text-right font-semibold capitalize text-slate-800">{{ str_replace('_',' ',$reservation?->status ?? '—') }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Total reservasi</dt><dd class="text-right font-extrabold text-slate-900">Rp {{ number_format((int)($reservation?->total_harga ?? 0),0,',','.') }}</dd></div>
                <div class="border-t border-slate-100 pt-3"><dt class="mb-2 text-slate-500">Layanan</dt><dd class="font-semibold leading-relaxed text-slate-800">@forelse($reservation?->detailReservasis ?? [] as $detail){{ $detail->layanan?->nama_layanan ?? 'Layanan' }} × {{ $detail->jumlah }}@if(!$loop->last), @endif @empty Layanan tidak tersedia @endforelse</dd></div>
            </dl></section>
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6"><h3 class="mb-4 text-sm font-bold text-slate-900">Informasi Customer</h3><dl class="space-y-3 text-xs">
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">Nama</dt><dd class="text-right font-semibold text-slate-800">{{ $reservation?->user?->nama ?? $reservation?->nama_pelanggan ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">Email</dt><dd class="break-all text-right font-semibold text-slate-800">{{ $reservation?->user?->email ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">Nomor telepon</dt><dd class="text-right font-semibold text-slate-800">{{ $reservation?->user?->no_telp ?? $reservation?->no_hp ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Alamat</dt><dd class="max-w-[65%] text-right font-semibold text-slate-800">{{ $reservation?->user?->alamat ?? $reservation?->alamat_jemput ?? '—' }}</dd></div>
            </dl></section>
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6"><h3 class="mb-4 text-sm font-bold text-slate-900">Bukti Pembayaran</h3>
                @if($proofUrl)
                    @if($proofIsImage)<a href="{{ $proofUrl }}" target="_blank" rel="noopener noreferrer"><img src="{{ $proofUrl }}" alt="Bukti pembayaran" class="max-h-72 w-full rounded-xl border border-slate-200 bg-slate-50 object-contain"></a>@endif
                    <a href="{{ $proofUrl }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700"><i class="fa-solid fa-arrow-up-right-from-square"></i>Lihat bukti pembayaran</a>
                @else
                    <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center"><i class="fa-regular fa-file-lines text-2xl text-slate-300"></i><p class="mt-2 text-xs font-semibold text-slate-700">Bukti pembayaran tidak tersedia</p><p class="mt-1 text-[11px] text-slate-500">Status gateway dapat diperiksa melalui referensi transaksi.</p></div>
                @endif
            </section>
        </div>
        @if($payment->diproses_pada)
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><h3 class="text-sm font-bold text-slate-900">Riwayat Peninjauan</h3><p class="mt-2 text-xs text-slate-600">{{ $payment->diproses_pada->translatedFormat('d M Y H:i') }} · {{ $label }} oleh {{ $payment->processedBy?->nama ?? 'Admin' }}</p>@if($payment->alasan_penolakan)<p class="mt-2 rounded-xl bg-rose-50 p-3 text-xs text-rose-800"><strong>Alasan penolakan:</strong> {{ $payment->alasan_penolakan }}</p>@endif</section>
        @endif
        @if($status==='menunggu')
            <section class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:grid-cols-2 sm:p-6">
                <form method="POST" action="{{ route($paymentRoutePrefix . '.verify',$payment->id_pembayaran) }}" onsubmit="return confirm('Apakah Anda yakin ingin memverifikasi pembayaran ini?')">@csrf<button class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 text-xs font-bold text-white hover:bg-emerald-700"><i class="fa-solid fa-circle-check"></i>Verifikasi Pembayaran</button></form>
                <form method="POST" action="{{ route($paymentRoutePrefix . '.reject',$payment->id_pembayaran) }}" onsubmit="return confirm('Tolak pembayaran ini dengan alasan yang diberikan?')" class="space-y-2">@csrf<label for="alasan_penolakan" class="block text-xs font-semibold text-slate-700">Alasan penolakan <span class="text-rose-600">(wajib)</span></label><textarea id="alasan_penolakan" name="alasan_penolakan" rows="2" minlength="5" maxlength="500" required placeholder="Jelaskan alasan pembayaran ditolak" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs outline-none focus:border-rose-400"></textarea><button class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 text-xs font-bold text-white hover:bg-rose-700"><i class="fa-solid fa-circle-xmark"></i>Tolak Pembayaran</button></form>
            </section>
        @endif
    </div></div>
    <footer class="shrink-0 border-t border-[#dbe5ea] bg-white px-6 py-4 text-center text-xs text-slate-500">&copy; {{ now()->year }} <span class="font-semibold text-slate-600">ALBRK.SHOESCARE</span></footer>
</main>
</body>
</html>
