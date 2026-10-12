<aside class="admin-sidebar superadmin-sidebar fixed inset-y-0 left-0 z-50 flex w-[218px] flex-col border-r border-white/5 px-3 py-5 text-white">
    <a href="{{ route('superadmin.dashboard') }}" class="mb-8 flex items-center gap-2.5 px-2">
        <img src="{{ asset('images/Logo.jpeg') }}" alt="ALBRK" class="h-9 w-9 rounded-md bg-white object-contain p-0.5">
        <span class="text-[15px] font-extrabold leading-none tracking-tight">ALBRK<small class="mt-1 block text-[8px] font-semibold tracking-[.14em]">SHOESCARE</small></span>
    </a>
    <nav class="space-y-1 text-[11px] font-semibold" aria-label="Navigasi superadmin">
        <a href="{{ route('landing') }}" class="admin-nav-link"><i class="fa-solid fa-house"></i>Home</a>
        <a href="{{ route('superadmin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('superadmin.dashboard') ? 'is-active' : '' }}"><i class="fa-solid fa-chart-pie"></i>Dashboard</a>
        <a href="{{ route('superadmin.layanan.index') }}" class="admin-nav-link {{ request()->routeIs('superadmin.layanan.*') ? 'is-active' : '' }}"><i class="fa-solid fa-shoe-prints"></i>Master Layanan</a>
        <a href="{{ route('superadmin.laporan') }}" class="admin-nav-link {{ request()->routeIs('superadmin.laporan') ? 'is-active' : '' }}"><i class="fa-solid fa-chart-column"></i>Laporan</a>
        <a href="{{ route('superadmin.users') }}" class="admin-nav-link {{ request()->routeIs('superadmin.users') ? 'is-active' : '' }}"><i class="fa-solid fa-users-gear"></i>Manajemen Pengguna</a>
        <a href="{{ route('profil.index') }}" class="admin-nav-link {{ request()->routeIs('profil.*') ? 'is-active' : '' }}"><i class="fa-solid fa-user-gear"></i>Profil</a>
    </nav>
</aside>
<script>
    if (!window.__adminBackToLanding) {
        window.__adminBackToLanding = true;
        history.pushState({ adminPage: true }, '', location.href);
        window.addEventListener('popstate', () => {
            window.location.replace(@json(route('landing')));
        });
    }
</script>
