<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bisnis - ALBRK.SHOESCARE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *{font-family:'Plus Jakarta Sans',sans-serif} body{background:#f3f7fa}
        .admin-scroll{overflow-x:hidden;scrollbar-width:thin;scrollbar-color:#cbd8de transparent}
        .admin-scroll::-webkit-scrollbar{width:6px;height:6px}.admin-scroll::-webkit-scrollbar-thumb{background:#cbd8de;border-radius:8px}
        .report-card{background:#fff;border:1px solid #dce6ec;box-shadow:0 4px 16px rgba(23,48,61,.04)}
        @media print{.no-print,aside,header,footer{display:none!important}main{margin:0!important}.admin-scroll{overflow:visible!important;height:auto!important}.report-card{box-shadow:none;break-inside:avoid}}
    </style>
    @include('shared.albrk-redesign')
</head>
@php
    $reportAreaPrefix = auth()->user()->isSuperAdmin() ? 'superadmin' : 'admin';
@endphp
<body class="antialiased flex h-screen overflow-hidden text-[#17303d]">
    @if(auth()->user()->isSuperAdmin())
        @include('shared.superadmin-sidebar')
    @else
        @include('shared.admin-sidebar')
    @endif
    <main class="ml-0 flex h-screen min-w-0 flex-1 flex-col bg-[#f3f7fa] md:ml-[218px]">
        <header class="flex shrink-0 items-center justify-between border-b border-[#29424c] bg-[#17303d] px-5 py-3.5 text-white md:px-8 z-40">
    <div class="flex items-center gap-3 md:gap-4"></div>
    @include('shared.admin-header-actions')
</header>
        <div class="admin-scroll flex-1 space-y-6 overflow-y-auto p-5 md:p-8">
            <section class="flex justify-end">
                <button onclick="window.print()" class="no-print inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-xs font-bold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-print"></i> Cetak / Simpan PDF</button>
            </section>

            <section class="report-card rounded-2xl p-5 md:p-6 no-print">
                <form method="GET" action="{{ route($reportAreaPrefix . '.laporan') }}" class="grid grid-cols-1 items-end gap-4 md:grid-cols-2 xl:grid-cols-5">
                    <div><label class="mb-2 block text-xs font-semibold text-slate-600">Periode</label><select name="periode" id="periode" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm focus:border-sky-500 focus:outline-none">
                        @foreach(['hari_ini'=>'Hari ini','minggu_ini'=>'Minggu ini','bulan_ini'=>'Bulan ini','tahun_ini'=>'Tahun ini','custom'=>'Custom'] as $value=>$label)<option value="{{ $value }}" @selected($period===$value)>{{ $label }}</option>@endforeach
                    </select></div>
                    <div class="custom-date"><label class="mb-2 block text-xs font-semibold text-slate-600">Tanggal Mulai</label><input type="date" name="tanggal_mulai" value="{{ $period==='custom' ? $startDate : '' }}" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm"></div>
                    <div class="custom-date"><label class="mb-2 block text-xs font-semibold text-slate-600">Tanggal Akhir</label><input type="date" name="tanggal_akhir" value="{{ $period==='custom' ? $endDate : '' }}" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm"></div>
                    <div><label class="mb-2 block text-xs font-semibold text-slate-600">Cari data</label><input type="search" name="q" value="{{ $q }}" placeholder="Nama, email, ID transaksi..." class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm"></div>
                    <div class="flex gap-2"><button class="flex-1 rounded-xl bg-[#17303d] px-4 py-3 text-xs font-bold text-white hover:bg-[#244654]">Tampilkan Laporan</button><a href="{{ route($reportAreaPrefix . '.laporan') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-xs font-bold text-slate-600">Reset</a></div>
                </form>
                @error('tanggal_mulai')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror @error('tanggal_akhir')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                <p class="mt-4 text-xs text-slate-500">Periode data: <strong>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} – {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</strong></p>
            </section>

            @php($cards = [
                ['Reservasi', $reservationCounts['total'], 'fa-calendar-check', 'sky'],
                ['Reservasi Dikonfirmasi', $reservationCounts['dikonfirmasi'], 'fa-box', 'blue'],
                ['Reservasi Selesai', $reservationCounts['selesai'], 'fa-circle-check', 'emerald'],
                ['Pendapatan Berhasil', 'Rp '.number_format($totalIncome,0,',','.'), 'fa-wallet', 'cyan'],
                ['Pembayaran Berhasil', $paymentCounts['berhasil'], 'fa-credit-card', 'indigo'],
                ['Menunggu Verifikasi', $paymentCounts['menunggu'], 'fa-hourglass-half', 'amber'],
                ['Reservasi Dibatalkan', $reservationCounts['dibatalkan'], 'fa-ban', 'rose'],
                ['Pelanggan Baru', $customerCounts['baru'], 'fa-users', 'violet'],
            ])
            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach($cards as [$label,$value,$icon,$color])<article class="report-card flex items-center justify-between rounded-2xl p-5"><div><p class="text-xs font-semibold text-slate-500">{{ $label }}</p><p class="mt-2 text-2xl font-extrabold tracking-tight text-[#17303d]">{{ $value }}</p></div><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-{{ $color }}-50 text-{{ $color }}-600"><i class="fa-solid {{ $icon }}"></i></span></article>@endforeach
            </section>
            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach($incomePeriods as $item)<article class="report-card rounded-2xl p-4"><p class="text-xs font-semibold text-slate-500">{{ $item['label'] }}</p><p class="mt-2 text-lg font-extrabold text-[#17303d]">Rp {{ number_format($item['amount'],0,',','.') }}</p></article>@endforeach
            </section>

            <section class="grid grid-cols-1 gap-5 xl:grid-cols-2">
                <article class="report-card rounded-2xl p-5"><div class="mb-5"><h3 class="font-bold">Reservasi berdasarkan periode</h3><p class="mt-1 text-xs text-slate-500">Jumlah reservasi per hari atau bulan.</p></div>
                    @if(array_sum($chart['reservations'])>0)<div class="flex h-48 items-end gap-1 border-b border-slate-100 pb-1">@foreach($chart['reservations'] as $i=>$n)@php($max=max(1,max($chart['reservations'])))<div class="group flex h-full min-w-0 flex-1 flex-col justify-end" title="{{ $chart['labels'][$i] }}: {{ $n }}"><div class="mx-auto w-full max-w-8 rounded-t-md bg-sky-500/85 hover:bg-sky-600" style="height:{{ max($n>0?4:0,($n/$max)*100) }}%"></div></div>@endforeach</div><div class="mt-2 flex justify-between gap-2 text-[9px] text-slate-400"><span>{{ $chart['labels'][0] ?? '' }}</span><span>{{ $chart['labels'][count($chart['labels'])-1] ?? '' }}</span></div>@else<div class="flex h-48 items-center justify-center text-sm text-slate-400">Belum ada data laporan pada periode ini.</div>@endif
                </article>
                <article class="report-card rounded-2xl p-5"><div class="mb-5"><h3 class="font-bold">Pendapatan berdasarkan periode</h3><p class="mt-1 text-xs text-slate-500">Hanya pembayaran yang berstatus berhasil.</p></div>
                    @if(array_sum($chart['income'])>0)<div class="flex h-48 items-end gap-1 border-b border-slate-100 pb-1">@foreach($chart['income'] as $i=>$n)@php($max=max(1,max($chart['income'])))<div class="group flex h-full min-w-0 flex-1 flex-col justify-end" title="{{ $chart['labels'][$i] }}: Rp {{ number_format($n,0,',','.') }}"><div class="mx-auto w-full max-w-8 rounded-t-md bg-emerald-500/85 hover:bg-emerald-600" style="height:{{ max($n>0?4:0,($n/$max)*100) }}%"></div></div>@endforeach</div><div class="mt-2 flex justify-between gap-2 text-[9px] text-slate-400"><span>{{ $chart['labels'][0] ?? '' }}</span><span>{{ $chart['labels'][count($chart['labels'])-1] ?? '' }}</span></div>@else<div class="flex h-48 items-center justify-center text-sm text-slate-400">Belum ada data laporan pada periode ini.</div>@endif
                </article>
                <article class="report-card rounded-2xl p-5 xl:col-span-2"><div class="mb-5"><h3 class="font-bold">Layanan paling banyak digunakan</h3><p class="mt-1 text-xs text-slate-500">Jumlah reservasi per layanan pada periode terpilih.</p></div>
                    @if(count($chart['services']))@php($max=max(1,max($chart['serviceCounts'])))<div class="space-y-3">@foreach($chart['services'] as $i=>$name)<div class="grid grid-cols-[minmax(100px,180px)_1fr_40px] items-center gap-3 text-xs"><span class="truncate font-semibold text-slate-600">{{ $name }}</span><div class="h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-sky-500" style="width:{{ ($chart['serviceCounts'][$i]/$max)*100 }}%"></div></div><span class="text-right font-bold">{{ $chart['serviceCounts'][$i] }}</span></div>@endforeach</div>@else<div class="py-10 text-center text-sm text-slate-400">Belum ada data laporan pada periode ini.</div>@endif
                </article>
            </section>

            <section class="report-card rounded-2xl p-5 md:p-6"><div class="mb-4 flex flex-wrap items-end justify-between gap-3"><div><h3 class="text-lg font-extrabold">Laporan Reservasi</h3><p class="text-xs text-slate-500">Menunggu {{ $reservationCounts['menunggu'] }} · Dikonfirmasi {{ $reservationCounts['dikonfirmasi'] }} · Selesai {{ $reservationCounts['selesai'] }} · Dibatalkan {{ $reservationCounts['dibatalkan'] }}</p></div><span class="text-xs font-bold text-slate-500">{{ $reservationRows->total() }} data</span></div>
                <div class="overflow-x-auto"><table class="w-full min-w-[950px] text-left text-xs"><thead class="border-y border-slate-100 bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500"><tr>@foreach(['No','No. Reservasi','Customer','Tanggal / Jadwal','Layanan','Sepatu','Metode','Total','Status'] as $heading)<th class="px-3 py-3">{{ $heading }}</th>@endforeach</tr></thead><tbody class="divide-y divide-slate-100">@forelse($reservationRows as $i=>$row)<tr><td class="px-3 py-3">{{ $reservationRows->firstItem()+$i }}</td><td class="px-3 py-3 font-bold"><a class="text-sky-700 hover:underline" href="{{ route($reportAreaPrefix . '.reservasi.show',$row->id_reservasi) }}">#{{ $row->id_reservasi }}</a></td><td class="px-3 py-3">{{ $row->user->nama ?? $row->nama_pelanggan ?? 'Pelanggan' }}</td><td class="px-3 py-3">{{ $row->tanggal_reservasi?->format('d/m/Y') ?? '—' }}</td><td class="px-3 py-3">{{ $row->detailReservasis->map(fn($d)=>$d->layanan?->nama_layanan)->filter()->unique()->join(', ') ?: '—' }}</td><td class="px-3 py-3">{{ $row->jumlah_sepatu }}</td><td class="px-3 py-3">{{ $row->metode_layanan ?: '—' }}</td><td class="px-3 py-3 font-semibold">Rp {{ number_format($row->total_harga,0,',','.') }}</td><td class="px-3 py-3">{{ str_replace('_',' ',ucfirst($row->status)) }}</td></tr>@empty<tr><td colspan="9" class="px-3 py-8 text-center text-slate-400">{{ $q ? 'Data tidak ditemukan.' : 'Belum ada data laporan pada periode ini.' }}</td></tr>@endforelse</tbody></table></div>{{ $reservationRows->links() }}</section>


            <section class="report-card rounded-2xl p-5 md:p-6"><div class="mb-4 flex items-end justify-between"><div><h3 class="text-lg font-extrabold">Laporan Pembayaran</h3><p class="text-xs text-slate-500">Berhasil {{ $paymentCounts['berhasil'] }} · Menunggu {{ $paymentCounts['menunggu'] }} · Ditolak {{ $paymentCounts['ditolak'] }} · Nominal berhasil Rp {{ number_format($paymentCounts['nominal_berhasil'],0,',','.') }}</p></div><span class="text-xs font-bold text-slate-500">{{ $paymentRows->total() }} transaksi</span></div>
                <div class="overflow-x-auto"><table class="w-full min-w-[800px] text-left text-xs"><thead class="border-y border-slate-100 bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500"><tr><th class="px-3 py-3">No</th><th class="px-3 py-3">ID Pembayaran</th><th class="px-3 py-3">No. Reservasi</th><th class="px-3 py-3">Customer</th><th class="px-3 py-3">Tanggal</th><th class="px-3 py-3">Metode</th><th class="px-3 py-3">Nominal</th><th class="px-3 py-3">Status</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($paymentRows as $i=>$row)<tr><td class="px-3 py-3">{{ $paymentRows->firstItem()+$i }}</td><td class="px-3 py-3 font-bold"><a class="text-sky-700 hover:underline" href="{{ route($reportAreaPrefix . '.pembayaran.show', $row->id_pembayaran) }}">#{{ $row->id_pembayaran }}</a></td><td class="px-3 py-3">#{{ $row->id_reservasi }}</td><td class="px-3 py-3">{{ $row->reservasi?->user?->nama ?? $row->reservasi?->nama_pelanggan ?? 'Pelanggan' }}</td><td class="px-3 py-3">{{ ($row->tanggal_bayar ?? $row->created_at)?->format('d/m/Y H:i') ?? '—' }}</td><td class="px-3 py-3">{{ $row->metode_bayar ?: $row->metode ?: '—' }}</td><td class="px-3 py-3">Rp {{ number_format($row->jumlah ?? 0,0,',','.') }}</td><td class="px-3 py-3">{{ ucfirst($row->status) }}</td></tr>@empty<tr><td colspan="8" class="px-3 py-8 text-center text-slate-400">{{ $q ? 'Data tidak ditemukan.' : 'Belum ada data laporan pada periode ini.' }}</td></tr>@endforelse</tbody></table></div>{{ $paymentRows->links() }}</section>

            <section class="report-card rounded-2xl p-5 md:p-6"><div class="mb-4 flex items-end justify-between"><div><h3 class="text-lg font-extrabold">Laporan Pelanggan</h3><p class="text-xs text-slate-500">Pelanggan pada periode {{ $customerCounts['total'] }} · Aktif {{ $customerCounts['aktif'] }} · Baru {{ $customerCounts['baru'] }} · Dengan transaksi {{ $customerCounts['transaksi'] }}</p></div><span class="text-xs font-bold text-slate-500">{{ $customerRows->total() }} data</span></div>
                <div class="overflow-x-auto"><table class="w-full min-w-[750px] text-left text-xs"><thead class="border-y border-slate-100 bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500"><tr><th class="px-3 py-3">No</th><th class="px-3 py-3">Nama</th><th class="px-3 py-3">Email</th><th class="px-3 py-3">Nomor Telepon</th><th class="px-3 py-3">Reservasi</th><th class="px-3 py-3">Total Transaksi</th><th class="px-3 py-3">Status</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($customerRows as $i=>$row)<tr><td class="px-3 py-3">{{ $customerRows->firstItem()+$i }}</td><td class="px-3 py-3 font-semibold">{{ $row->nama }}</td><td class="px-3 py-3">{{ $row->email }}</td><td class="px-3 py-3">{{ $row->no_telp ?: '—' }}</td><td class="px-3 py-3">{{ $row->jumlah_reservasi }}</td><td class="px-3 py-3">Rp {{ number_format($row->total_transaksi ?? 0,0,',','.') }}</td><td class="px-3 py-3">{{ $row->status ?: '—' }}</td></tr>@empty<tr><td colspan="7" class="px-3 py-8 text-center text-slate-400">{{ $q ? 'Data tidak ditemukan.' : 'Belum ada data laporan pada periode ini.' }}</td></tr>@endforelse</tbody></table></div>{{ $customerRows->links() }}</section>

            <section class="report-card rounded-2xl p-5 md:p-6"><div class="mb-4 flex items-end justify-between"><div><h3 class="text-lg font-extrabold">Laporan Layanan</h3><p class="text-xs text-slate-500">Urut berdasarkan jumlah reservasi pada periode.</p></div><span class="text-xs font-bold text-slate-500">{{ $serviceRows->total() }} layanan</span></div>
                <div class="overflow-x-auto"><table class="w-full min-w-[620px] text-left text-xs"><thead class="border-y border-slate-100 bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500"><tr><th class="px-3 py-3">Nama Layanan</th><th class="px-3 py-3">Jumlah Reservasi</th><th class="px-3 py-3">Jumlah Sepatu</th><th class="px-3 py-3">Total Pendapatan</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($serviceRows as $row)<tr><td class="px-3 py-3 font-semibold">{{ $row->nama_layanan }}</td><td class="px-3 py-3">{{ $row->jumlah_reservasi }}</td><td class="px-3 py-3">{{ $row->jumlah_sepatu }}</td><td class="px-3 py-3">Rp {{ number_format($row->total_pendapatan,0,',','.') }}</td></tr>@empty<tr><td colspan="4" class="px-3 py-8 text-center text-slate-400">{{ $q ? 'Data tidak ditemukan.' : 'Belum ada data laporan pada periode ini.' }}</td></tr>@endforelse</tbody></table></div>{{ $serviceRows->links() }}</section>
        </div>
        <footer class="shrink-0 border-t border-slate-200 bg-white px-5 py-4 text-center text-xs text-slate-500">&copy; {{ now()->year }} ALBRK.SHOESCARE</footer>
    </main>
    <script>
        const periodSelect = document.getElementById('periode');
        const toggleCustomDates = () => document.querySelectorAll('.custom-date').forEach((element) => element.classList.toggle('hidden', periodSelect.value !== 'custom'));
        periodSelect.addEventListener('change', toggleCustomDates); toggleCustomDates();
    </script>
</body>
</html>

