<div class="flex items-center gap-3 sm:gap-5">
    <div class="hidden rounded-full border border-slate-700 bg-slate-800/50 px-3 py-2 text-[9px] font-black uppercase tracking-widest text-slate-400 shadow-inner sm:block md:px-4 md:text-[10px]">
        Hari ini: <span class="text-white">{{ now()->format('d M Y') }}</span>
    </div>
    @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
    @php($notificationIndexRoute = auth()->user()->isSuperAdmin() ? 'superadmin.notifikasi.index' : 'admin.notifikasi.index')
    <a href="{{ route($notificationIndexRoute) }}" aria-label="Notifikasi, {{ $headerUnreadCount }} belum dibaca" class="relative grid h-9 w-9 place-items-center rounded-full border border-white/15 bg-white/15 text-slate-100 transition hover:bg-white/20">
        <i class="fa-regular fa-bell"></i>
        @if($headerUnreadCount)<span class="absolute -right-1 -top-1 grid h-4 min-w-4 place-items-center rounded-full bg-rose-500 px-1 text-[9px] font-bold text-white">{{ $headerUnreadCount > 99 ? '99+' : $headerUnreadCount }}</span>@endif
    </a>
    @endif
    @if(auth()->user()->isAdmin()) @include('shared.user-guide-trigger') @endif
    <div class="relative">
        <button type="button" id="userMenuButton" onclick="document.getElementById('userMenu').classList.toggle('hidden')" class="flex items-center rounded-full border border-slate-700 bg-slate-800/40 p-1 pr-4 shadow-inner transition-all hover:border-blue-500/40">
            <span class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-full border border-slate-700 bg-blue-600 text-[10px] font-black text-white shadow-xl shadow-blue-500/20">
                @if(auth()->user()->foto_profil)
                    <img src="{{ route('profil.foto') }}" alt="Foto profil" class="h-full w-full rounded-full bg-white object-contain p-0.5" onerror="this.classList.add('hidden');this.nextElementSibling.classList.remove('hidden')">
                    <span class="hidden">{{ strtoupper(substr(auth()->user()->nama ?? 'AD', 0, 2)) }}</span>
                @else
                    {{ strtoupper(substr(auth()->user()->nama ?? 'AD', 0, 2)) }}
                @endif
            </span>
            <span class="ml-3 hidden text-left md:block">
                <span class="block text-[10px] font-black uppercase tracking-widest text-white">{{ explode(' ', auth()->user()->nama ?? 'Admin')[0] }}</span>
                <span class="mt-0.5 block text-[7px] font-bold uppercase tracking-tighter text-blue-400/80">{{ auth()->user()->isSuperAdmin() ? 'Superadmin' : 'Admin' }}</span>
            </span>
            <i class="fa-solid fa-chevron-down ml-3 text-[10px] text-slate-500"></i>
        </button>
        <div id="userMenu" class="absolute right-0 z-50 mt-2 hidden w-48 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
            <a href="{{ route('profil.index') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-semibold text-slate-700 transition-colors hover:bg-slate-50"><i class="fa-solid fa-user-gear w-4 text-center"></i>Kelola Profil</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-left text-xs font-semibold text-rose-600 transition-colors hover:bg-rose-50"><i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>Keluar</button></form>
        </div>
    </div>
</div>
@include('shared.user-guide')
