<aside class="admin-sidebar fixed inset-y-0 left-0 z-50 flex w-[218px] flex-col border-r border-white/5 px-3 py-5 text-white">
    <a href="{{ route('admin.dashboard') }}" class="mb-8 flex items-center gap-2.5 px-2">
        <img src="{{ asset('images/Logo.jpeg') }}" alt="ALBRK" class="h-9 w-9 rounded-md bg-white object-contain p-0.5">
        <span class="text-[15px] font-extrabold leading-none tracking-tight">ALBRK<small class="mt-1 block text-[8px] font-semibold tracking-[.14em]">SHOESCARE</small></span>
    </a>
    <nav class="space-y-1 text-[11px] font-semibold" aria-label="Navigasi admin">
        <a href="{{ route('landing') }}" class="admin-nav-link"><i class="fa-solid fa-house"></i>Home</a>
        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}"><i class="fa-solid fa-chart-pie"></i>Dashboard</a>
        <a href="{{ route('admin.antrean') }}" class="admin-nav-link {{ request()->routeIs('admin.antrean*') ? 'is-active' : '' }}"><i class="fa-solid fa-list-check"></i>Antrean</a>
        <a href="{{ route('admin.pembayaran.index') }}" class="admin-nav-link {{ request()->routeIs('admin.pembayaran.*') ? 'is-active' : '' }}"><i class="fa-regular fa-credit-card"></i>Pembayaran</a>
        <a href="{{ route('admin.users') }}" class="admin-nav-link {{ request()->routeIs('admin.users') ? 'is-active' : '' }}"><i class="fa-solid fa-users-gear"></i>Manajemen Pengguna</a>
        <a href="{{ route('admin.layanan.index') }}" class="admin-nav-link {{ request()->routeIs('admin.layanan.*') ? 'is-active' : '' }}"><i class="fa-solid fa-shoe-prints"></i>Master Layanan</a>
        <a href="{{ route('admin.laporan') }}" class="admin-nav-link {{ request()->routeIs('admin.laporan') ? 'is-active' : '' }}"><i class="fa-solid fa-chart-column"></i>Laporan</a>
        <a href="{{ route('admin.notifikasi.index') }}" class="admin-nav-link {{ request()->routeIs('admin.notifikasi.*') ? 'is-active' : '' }}"><i class="fa-regular fa-bell"></i>Notifikasi</a>
        <a href="{{ route('profil.index') }}" class="admin-nav-link {{ request()->routeIs('profil.*') ? 'is-active' : '' }}"><i class="fa-solid fa-user-gear"></i>Profil</a>
    </nav>
    <div class="admin-help mt-auto rounded-xl border border-white/10 bg-white/[.04] p-3.5">
        <div class="mb-2 flex items-center gap-2 text-[10px] font-bold"><i class="fa-solid fa-headset"></i>Butuh Bantuan?</div>
        <p class="mb-3 text-[9px] leading-relaxed text-slate-400">Hubungi tim dukungan ALBRK.</p>
        <a href="https://wa.me/6285736084686" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border border-white/30 px-2.5 py-2 text-[9px]">Hubungi Admin <i class="fa-solid fa-arrow-right"></i></a>
    </div>
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
