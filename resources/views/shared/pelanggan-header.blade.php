<header class="z-40 flex shrink-0 items-center justify-between border-b border-[#29424c] bg-[#17303d] px-5 py-3.5 text-white md:px-8">
    @if(!empty($pageTitle))<h1 class="text-sm font-bold text-white sm:text-base">{{ $pageTitle }}</h1>@endif
    <div class="ml-auto flex items-center gap-3 sm:gap-5">
        <span class="hidden rounded-full border border-white/10 bg-white/10 px-4 py-2 text-[9px] font-bold uppercase tracking-widest text-slate-300 sm:block">Hari ini: <strong class="text-white">{{ now()->format('d M Y') }}</strong></span>
        <a href="{{ route('pelanggan.notifikasi') }}" aria-label="Notifikasi, {{ $headerUnreadCount }} belum dibaca" title="Notifikasi" class="relative grid h-9 w-9 place-items-center rounded-full border border-white/10 bg-white/10 text-slate-200 transition hover:bg-white/15"><i class="fa-regular fa-bell"></i>@if($headerUnreadCount)<span class="absolute -right-1 -top-1 grid h-4 min-w-4 place-items-center rounded-full bg-rose-500 px-1 text-[9px] font-bold text-white">{{ $headerUnreadCount > 99 ? '99+' : $headerUnreadCount }}</span>@endif</a>
        @include('shared.user-guide-trigger')
        <div class="relative">
            <button type="button" id="customerProfileButton" aria-expanded="false" aria-controls="customerProfileMenu" onclick="const menu=document.getElementById('customerProfileMenu');const open=menu.classList.toggle('hidden');this.setAttribute('aria-expanded',String(!open))" class="flex items-center rounded-full border border-white/10 bg-white/10 p-1 pr-3 transition hover:border-sky-400/50">
                @if(auth()->user()?->foto_profil)<img src="{{ route('profil.foto') }}" alt="Foto profil" class="h-8 w-8 rounded-full border border-white/20 object-cover" onerror="this.classList.add('hidden');this.nextElementSibling.classList.remove('hidden');this.nextElementSibling.classList.add('grid')"><span class="hidden h-8 w-8 place-items-center rounded-full bg-sky-700 text-[10px] font-bold">{{ strtoupper(substr(auth()->user()->nama ?? 'PL',0,2)) }}</span>@else<span class="grid h-8 w-8 place-items-center rounded-full bg-sky-700 text-[10px] font-bold">{{ strtoupper(substr(auth()->user()->nama ?? 'PL',0,2)) }}</span>@endif
                <span class="ml-2 hidden text-left sm:block"><strong class="block text-[9px] font-bold uppercase tracking-wider">{{ explode(' ',auth()->user()->nama ?? 'Pelanggan')[0] }}</strong><small class="mt-0.5 block text-[8px] font-semibold uppercase text-sky-300">{{ $profileRoleLabel ?? 'Pelanggan' }}</small></span>
                <i class="fa-solid fa-chevron-down ml-2 text-[9px] text-slate-400"></i>
            </button>
            <div id="customerProfileMenu" class="absolute right-0 z-50 mt-2 hidden w-48 overflow-hidden rounded-xl border border-slate-200 bg-white text-xs text-slate-700 shadow-xl">
                <a href="{{ route('profil.index') }}" class="flex items-center gap-3 px-4 py-3 font-semibold hover:bg-slate-50"><i class="fa-solid fa-user-gear w-4 text-center"></i>Kelola Profil</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-left font-semibold text-rose-600 hover:bg-rose-50"><i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>Keluar</button></form>
            </div>
        </div>
    </div>
</header>
@include('shared.user-guide')
<script>
document.addEventListener('click', event => {
    const menu = document.getElementById('customerProfileMenu');
    const button = document.getElementById('customerProfileButton');
    if (menu && button && !menu.contains(event.target) && !button.contains(event.target)) {
        menu.classList.add('hidden');
        button.setAttribute('aria-expanded', 'false');
    }
});
</script>
