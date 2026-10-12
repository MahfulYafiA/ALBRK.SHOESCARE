<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Detail Notifikasi - ALBRK SHOESCARE</title><script src="https://cdn.tailwindcss.com"></script><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">@if($role === 'pelanggan')
@include('shared.pelanggan-shell-styles')
@else
@include('shared.albrk-redesign')
@endif
</head><body class="flex h-screen overflow-hidden bg-slate-50">@if($role === 'pelanggan')
@include('shared.pelanggan-sidebar')<div class="customer-main ml-[218px] flex flex-1 flex-col">@include('shared.pelanggan-header',['pageTitle'=>'Notifikasi','showNotificationIcon'=>true])
@else
@if($role === 'superadmin')
@include('shared.superadmin-sidebar')
@else
@include('shared.admin-sidebar')
@endif<main class="ml-0 flex h-screen min-w-0 flex-1 flex-col bg-[#f3f7fa] md:ml-[218px]"><header class="flex shrink-0 items-center justify-end border-b border-[#29424c] bg-[#17303d] px-5 py-3.5 text-white md:px-8">@include('shared.admin-header-actions')</header>@endif
<div class="flex-1 p-5 sm:p-8"><a href="{{ route($notificationIndexRoute) }}" class="text-sm font-semibold text-slate-500"><i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke notifikasi</a><article class="mx-auto mt-6 max-w-2xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><span class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ ucfirst($item->data['category'] ?? 'Informasi') }} · {{ $item->created_at?->diffForHumans() }}</span><h1 class="mt-2 text-2xl font-extrabold text-slate-900">{{ $item->data['title'] ?? 'Notifikasi' }}</h1><p class="mt-4 leading-relaxed text-slate-600">{{ $item->data['message'] ?? '' }}</p><p class="mt-6 text-xs text-emerald-700"><i class="fa-solid fa-check mr-1"></i>Notifikasi telah ditandai sebagai sudah dibaca.</p></article></div>@if($role === 'pelanggan')</div>@else</main>@endif</body></html>
