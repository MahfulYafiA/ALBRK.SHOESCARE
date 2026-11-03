<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Notifikasi - ALBRK SHOESCARE</title><script src="https://cdn.tailwindcss.com"></script><link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">@if($role === 'pelanggan')
@include('shared.pelanggan-shell-styles')
@else
@include('shared.albrk-redesign')
@endif
<style>body{font-family:'Plus Jakarta Sans',sans-serif;background:#f3f7fa;color:#17303d}</style></head>
<body class="flex h-screen overflow-hidden antialiased">
@if($role === 'pelanggan')
@include('shared.pelanggan-sidebar')<div class="customer-main ml-[218px] flex h-screen min-w-0 flex-1 flex-col">@include('shared.pelanggan-header',['pageTitle'=>'Notifikasi','showNotificationIcon'=>true])
@else
@if($role === 'superadmin')
@include('shared.superadmin-sidebar')
@else
@include('shared.admin-sidebar')
@endif
<main class="ml-0 flex h-screen min-w-0 flex-1 flex-col bg-[#f3f7fa] md:ml-[218px]"><header class="flex shrink-0 items-center justify-end border-b border-[#29424c] bg-[#17303d] px-5 py-3.5 text-white md:px-8">@include('shared.admin-header-actions')</header>
@endif
<div class="flex-1 overflow-y-auto p-3 lg:p-4"><div class="w-full space-y-6">
<div class="flex justify-end">@if($unreadCount)<form method="POST" action="{{ route($notificationRoute . '.read-all') }}">@csrf @method('PATCH')<button class="inline-flex h-10 items-center gap-2 rounded-xl bg-[#17303d] px-4 text-xs font-semibold text-white hover:bg-[#244653]"><i class="fa-solid fa-check-double"></i>Tandai semua sudah dibaca</button></form>@endif</div>
@if(session('success'))<div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>@endif
@if(!empty($canManageNotifications))
<section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
    <div class="mb-4"><h1 class="font-bold text-slate-900">Kirim Notifikasi</h1><p class="mt-1 text-xs text-slate-500">Kirim informasi kepada satu pelanggan atau seluruh pelanggan.</p></div>
    <form method="POST" action="{{ route($storeNotificationRoute) }}" class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
        @csrf
        <label class="grid gap-1 text-xs font-semibold text-slate-600">Penerima<select name="recipient" id="notificationRecipient" class="h-10 rounded-xl border border-slate-200 bg-white px-3 font-normal text-slate-800"><option value="all" @selected(old('recipient', 'all') === 'all')>Semua pelanggan</option><option value="specific" @selected(old('recipient') === 'specific')>Pelanggan tertentu</option></select></label>
        <label id="notificationCustomerField" class="hidden grid gap-1 text-xs font-semibold text-slate-600">Pilih pelanggan<select name="customer_id" class="h-10 rounded-xl border border-slate-200 bg-white px-3 font-normal text-slate-800"><option value="">Pilih pelanggan</option>@foreach($customers as $customer)<option value="{{ $customer->getKey() }}" @selected((string) old('customer_id') === (string) $customer->getKey())>{{ $customer->nama }} ({{ $customer->email }})</option>@endforeach</select></label>
        <label class="grid gap-1 text-xs font-semibold text-slate-600">Judul<input name="title" value="{{ old('title') }}" maxlength="100" required placeholder="Contoh: Informasi layanan" class="h-10 rounded-xl border border-slate-200 px-3 font-normal text-slate-800"></label>
        <label class="grid gap-1 text-xs font-semibold text-slate-600 md:col-span-2 xl:col-span-3">Pesan<textarea name="message" maxlength="1000" required rows="2" placeholder="Tulis informasi yang ingin disampaikan..." class="rounded-xl border border-slate-200 px-3 py-2 font-normal text-slate-800">{{ old('message') }}</textarea></label>
        <div class="flex items-end"><button type="submit" class="h-10 rounded-xl bg-[#17303d] px-5 text-xs font-semibold text-white hover:bg-[#244653]"><i class="fa-regular fa-paper-plane mr-2"></i>Kirim Notifikasi</button></div>
        @if($errors->any())<p class="text-xs text-rose-600 md:col-span-2 xl:col-span-4">{{ $errors->first() }}</p>@endif
    </form>
</section>
<script>
    (() => {
        const recipient = document.getElementById('notificationRecipient');
        const customerField = document.getElementById('notificationCustomerField');
        const customerSelect = customerField.querySelector('select');
        const updateRecipientField = () => {
            const isSpecific = recipient.value === 'specific';
            customerField.classList.toggle('hidden', !isSpecific);
            customerSelect.required = isSpecific;
        };
        recipient.addEventListener('change', updateRecipientField);
        updateRecipientField();
    })();
</script>
@endif
<section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"><div class="mb-4"><form method="GET" action="{{ route($notificationIndexRoute) }}" class="flex w-full gap-2 sm:max-w-md"><input type="hidden" name="filter" value="{{ $filters['filter'] ?? 'semua' }}"><label class="relative min-w-0 flex-1"><span class="sr-only">Cari notifikasi</span><i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i><input type="search" name="q" value="{{ $filters['q'] ?? '' }}" maxlength="100" placeholder="Cari notifikasi..." class="h-10 w-full rounded-xl border border-slate-200 pl-9 pr-3 text-xs outline-none focus:border-slate-400"></label><button class="rounded-xl border border-slate-200 px-3 text-xs font-semibold">Cari</button></form></div><nav class="flex gap-2 overflow-x-auto pb-1" aria-label="Filter notifikasi">@foreach(['semua'=>'Semua','belum_dibaca'=>'Belum Dibaca','reservasi'=>'Reservasi','pembayaran'=>'Pembayaran','informasi'=>'Informasi'] as $key=>$label)<a href="{{ route($notificationIndexRoute, array_filter(['filter'=>$key,'q'=>$filters['q'] ?? null])) }}" class="shrink-0 rounded-full px-3.5 py-2 text-xs font-semibold {{ ($filters['filter'] ?? 'semua')===$key ? 'bg-[#17303d] text-white':'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">{{ $label }} @if($key==='belum_dibaca')<span class="ml-1 rounded-full bg-white/20 px-1.5 py-0.5">{{ $unreadCount }}</span>@endif</a>@endforeach</nav>
<div class="divide-y divide-slate-100">@forelse($notifications as $notification)@php($data=$notification->data)@php($category=$data['category'] ?? 'informasi')@php($icons=['pesanan'=>'fa-box','pembayaran'=>'fa-credit-card','reservasi'=>'fa-calendar-check','informasi'=>'fa-circle-info'])@php($tones=['pesanan'=>'bg-sky-50 text-sky-700','pembayaran'=>'bg-emerald-50 text-emerald-700','reservasi'=>'bg-violet-50 text-violet-700','informasi'=>'bg-slate-100 text-slate-600'])
<article class="flex gap-3 rounded-xl p-4 sm:gap-4 {{ $notification->unread()?'bg-sky-50/60':'' }}"><span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl {{ $tones[$category] ?? $tones['informasi'] }}"><i class="fa-solid {{ $icons[$category] ?? $icons['informasi'] }}"></i></span><div class="min-w-0 flex-1"><div class="flex flex-wrap items-start justify-between gap-2"><div><a href="{{ route($notificationRoute . '.show',$notification->id) }}" class="font-bold text-slate-900 hover:underline">{{ $data['title'] ?? 'Notifikasi' }}</a><span class="ml-2 rounded-full bg-white px-2 py-1 text-[10px] font-semibold capitalize text-slate-500">{{ $category === 'pesanan' ? 'Reservasi' : ucfirst($category) }}</span></div>@if($notification->unread())<span class="mt-1 h-2 w-2 rounded-full bg-sky-600" title="Belum dibaca"></span>@endif</div><p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $data['message'] ?? '' }}</p><div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-slate-400"><span>{{ $notification->created_at?->diffForHumans() }}</span><a href="{{ route($notificationRoute . '.show',$notification->id) }}" class="font-semibold text-[#17303d]">{{ $data['action'] ?? (in_array($category,['pesanan','reservasi'])?'Lihat Reservasi':($category==='pembayaran'?'Lihat Pembayaran':'Lihat Detail')) }} <i class="fa-solid fa-arrow-right ml-1"></i></a>@if($notification->unread())<form method="POST" action="{{ route($notificationRoute . '.read',$notification->id) }}">@csrf @method('PATCH')<button class="font-semibold text-slate-500 hover:text-slate-900">Tandai sudah dibaca</button></form>@else<span class="font-medium text-emerald-700"><i class="fa-solid fa-check mr-1"></i>Dibaca</span>@endif</div></div></article>
@empty<div class="px-4 py-16 text-center"><span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-slate-400"><i class="fa-regular fa-bell text-xl"></i></span><h2 class="mt-4 font-bold text-slate-800">Belum ada notifikasi</h2><p class="mt-1 text-sm text-slate-500">Notifikasi terbaru mengenai reservasi dan pembayaran Anda akan muncul di sini.</p></div>@endforelse</div>
@if($notifications->hasPages())<div class="mt-4 border-t border-slate-100 pt-4">{{ $notifications->links() }}</div>@endif</section></div></div><footer class="shrink-0 border-t border-[#dbe5ea] bg-white px-6 py-4 text-center text-xs text-slate-500">&copy; {{ now()->year }} ALBRK.SHOESCARE</footer>@if($role === 'pelanggan')</div>@else</main>@endif</body></html>
