<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - ALBRK SHOESCARE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>body{font-family:'Plus Jakarta Sans',sans-serif;background:#f3f7fa;color:#17303d}.payment-scroll{scrollbar-width:none;-ms-overflow-style:none}.payment-scroll::-webkit-scrollbar{display:none}</style>
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
    <header class="flex shrink-0 items-center justify-between border-b border-[#29424c] bg-[#17303d] px-5 py-3.5 text-white md:px-8 z-40">
    <div class="flex items-center gap-3 md:gap-4"></div>
    @include('shared.admin-header-actions')
</header>
    <div class="payment-scroll min-w-0 flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
        <div class="mx-auto max-w-[1600px] space-y-5">
            @if(session('success'))<div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>@endif
            @if($errors->any())<div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ $errors->first() }}</div>@endif

            <section class="grid grid-cols-2 gap-3 xl:grid-cols-6" aria-label="Ringkasan pembayaran">
                @foreach([
                    ['Total Pembayaran', number_format($summary['total']), 'fa-receipt', 'slate'],
                    ['Menunggu Verifikasi', number_format($summary['menunggu']), 'fa-hourglass-half', 'amber'],
                    ['Pembayaran Berhasil', number_format($summary['berhasil']), 'fa-circle-check', 'emerald'],
                    ['Pembayaran Ditolak', number_format($summary['ditolak']), 'fa-circle-xmark', 'rose'],
                    ['Total Pendapatan', 'Rp '.number_format((int)$summary['pendapatan'],0,',','.'), 'fa-wallet', 'sky'],
                    ['Pendapatan Bulan Ini', 'Rp '.number_format((int)$summary['bulan_ini'],0,',','.'), 'fa-calendar', 'indigo'],
                ] as [$label,$value,$icon,$tone])
                    <article class="flex min-w-0 items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-{{ $tone }}-50 text-{{ $tone }}-700"><i class="fa-solid {{ $icon }}"></i></span>
                        <div class="min-w-0"><p class="truncate text-lg font-extrabold text-slate-900">{{ $value }}</p><p class="mt-0.5 text-[10px] leading-tight text-slate-500">{{ $label }}</p></div>
                    </article>
                @endforeach
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4 sm:px-6"><h2 class="text-base font-bold text-slate-900">Transaksi Pembayaran</h2><p class="mt-1 text-xs text-slate-500">Cari dan tinjau pembayaran yang tercatat pada reservasi pelanggan.</p></div>
                <form method="GET" action="{{ route($paymentRoutePrefix . '.index') }}" class="grid gap-3 border-b border-slate-100 bg-slate-50/70 p-4 sm:grid-cols-2 lg:grid-cols-6 lg:p-5">
                    <label class="relative sm:col-span-2"><span class="invisible mb-1 block h-[15px] text-[10px]" aria-hidden="true">Cari</span><span class="sr-only">Cari pembayaran</span><i class="fa-solid fa-magnifying-glass absolute left-3 top-[calc(50%+10px)] -translate-y-1/2 text-xs text-slate-400"></i><input name="q" value="{{ $filters['q'] ?? '' }}" maxlength="100" placeholder="ID pembayaran, reservasi, nama, atau email" class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 text-xs outline-none focus:border-slate-400"></label>
                    <label><span class="invisible mb-1 block h-[15px] text-[10px]" aria-hidden="true">Status</span><select name="status" aria-label="Status pembayaran" class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs"><option value="">Semua status</option>@foreach(['menunggu'=>'Menunggu Verifikasi','berhasil'=>'Berhasil','ditolak'=>'Ditolak'] as $key=>$label)<option value="{{ $key }}" @selected(($filters['status'] ?? '')===$key)>{{ $label }}</option>@endforeach</select></label>
                    <label><span class="invisible mb-1 block h-[15px] text-[10px]" aria-hidden="true">Metode</span><select name="metode" aria-label="Metode pembayaran" class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs"><option value="">Semua metode</option>@foreach($methods as $method)<option value="{{ $method }}" @selected(($filters['metode'] ?? '')===$method)>{{ ucfirst($method) }}</option>@endforeach</select></label>
                    <label><span class="mb-1 block text-[10px] font-semibold text-slate-500">Dari tanggal</span><input type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] ?? '' }}" class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs"></label>
                    <label><span class="mb-1 block text-[10px] font-semibold text-slate-500">Sampai tanggal</span><input type="date" name="tanggal_akhir" value="{{ $filters['tanggal_akhir'] ?? '' }}" min="{{ $filters['tanggal_mulai'] ?? '' }}" class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs"></label>
                    <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-6"><button class="inline-flex h-10 items-center gap-2 rounded-xl bg-[#17303d] px-4 text-xs font-semibold text-white hover:bg-[#244653]"><i class="fa-solid fa-filter"></i>Terapkan Filter</button><a href="{{ route($paymentRoutePrefix . '.index') }}" class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-600"><i class="fa-solid fa-rotate-left"></i>Reset Filter</a></div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[850px] text-left text-xs">
                        <thead class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500"><tr><th class="px-4 py-3">No</th><th class="px-4 py-3">ID Pembayaran</th><th class="px-4 py-3">No. Reservasi</th><th class="px-4 py-3">Customer</th><th class="px-4 py-3">Tanggal Pembayaran</th><th class="px-4 py-3">Metode</th><th class="px-4 py-3">Jumlah</th><th class="px-4 py-3">Status</th><th class="px-4 py-3 text-right">Aksi</th></tr></thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($payments as $payment)
                            @php
                                $reservation=$payment->reservasi;
                                $status=strtolower((string)$payment->status);
                                [$label,$tone]=match(true){$status==='lunas'=>['Berhasil','emerald'],$status==='menunggu'=>['Menunggu Verifikasi','amber'],in_array($status,['gagal','ditolak'],true)=>['Ditolak','rose'],$status==='dikembalikan'=>['Dikembalikan','slate'],default=>[ucfirst($status),'slate']};
                                $orderNumber='RSV-'.($reservation?->tanggal_reservasi?->format('Ymd') ?? '--------').'-'.str_pad((string)$payment->id_reservasi,3,'0',STR_PAD_LEFT);
                            @endphp
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-4 py-4 text-slate-500">{{ $payments->firstItem()+$loop->index }}</td>
                                <td class="whitespace-nowrap px-4 py-4 font-bold text-slate-800">#PAY-{{ str_pad((string)$payment->id_pembayaran,5,'0',STR_PAD_LEFT) }}</td>
                                <td class="whitespace-nowrap px-4 py-4 font-semibold text-slate-700">#{{ $orderNumber }}</td>
                                <td class="px-4 py-4"><p class="font-semibold text-slate-800">{{ $reservation?->user?->nama ?? $reservation?->nama_pelanggan ?? 'Customer' }}</p><p class="mt-1 text-[10px] text-slate-500">{{ $reservation?->user?->email ?? '—' }}</p></td>
                                <td class="whitespace-nowrap px-4 py-4 text-slate-600">{{ ($payment->tanggal_bayar ?? $payment->created_at)?->translatedFormat('d M Y H:i') ?? '—' }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ ucfirst($payment->metode_bayar ?: $payment->metode ?: '—') }}</td>
                                <td class="whitespace-nowrap px-4 py-4 font-bold text-slate-800">Rp {{ number_format((int)$payment->jumlah,0,',','.') }}</td>
                                <td class="px-4 py-4"><span class="inline-flex rounded-full bg-{{ $tone }}-50 px-2.5 py-1 text-[10px] font-semibold text-{{ $tone }}-700 ring-1 ring-inset ring-{{ $tone }}-200">{{ $label }}</span></td>
                                <td class="px-4 py-4 text-right"><a href="{{ route($paymentRoutePrefix . '.show',$payment->id_pembayaran) }}" class="whitespace-nowrap rounded-lg border border-slate-200 px-3 py-2 text-[10px] font-semibold text-slate-700 hover:bg-[#17303d] hover:text-white">Lihat Detail</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="px-4 py-14 text-center"><i class="fa-regular fa-credit-card text-3xl text-slate-300"></i><p class="mt-3 text-sm font-semibold text-slate-700">{{ !empty($filters) ? 'Tidak ada pembayaran yang sesuai dengan pencarian.' : 'Belum ada data pembayaran.' }}</p><p class="mt-1 text-xs text-slate-500">Pembayaran customer akan muncul setelah transaksi tercatat.</p></td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($payments->hasPages())<div class="border-t border-slate-100 px-4 py-4 sm:px-6">{{ $payments->links() }}</div>@endif
            </section>
        </div>
    </div>
    <footer class="shrink-0 border-t border-[#dbe5ea] bg-white px-6 py-4 text-center text-xs text-slate-500">&copy; {{ now()->year }} <span class="font-semibold text-slate-600">ALBRK.SHOESCARE</span></footer>
</main>
</body>
</html>
