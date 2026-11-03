<aside class="customer-sidebar fixed inset-y-0 left-0 z-50 flex w-[218px] flex-col border-r border-white/5 px-3 py-5 text-white">
    <a href="{{ route('pelanggan.dashboard') }}" class="brand-link mb-8 flex items-center gap-2.5 px-2">
        <img src="{{ asset('images/Logo.jpeg') }}" alt="ALBRK" class="h-9 w-9 shrink-0 rounded-md bg-white object-contain p-0.5">
        <span class="brand-label text-[15px] font-extrabold leading-none tracking-tight">ALBRK<small class="mt-1 block text-[8px] font-semibold tracking-[.14em]">SHOESCARE</small></span>
    </a>
    <nav class="space-y-1 text-[11px] font-semibold" aria-label="Navigasi pelanggan">
        <a href="{{ route('landing') }}" class="customer-nav-link"><i class="fa-solid fa-house"></i><span class="nav-label">Home</span></a>
        <a href="{{ route('pelanggan.dashboard') }}" class="customer-nav-link {{ request()->routeIs('pelanggan.dashboard') ? 'is-active' : '' }}"><i class="fa-solid fa-chart-pie"></i><span class="nav-label">Dashboard</span></a>
        <a href="{{ route('reservasi.create') }}" class="customer-nav-link {{ request()->routeIs('reservasi.create') ? 'is-active' : '' }}"><i class="fa-solid fa-calendar-check"></i><span class="nav-label">Reservasi</span></a>
        <a href="{{ route('pelanggan.riwayat-reservasi') }}" class="customer-nav-link {{ request()->routeIs('pelanggan.riwayat-reservasi*') ? 'is-active' : '' }}"><i class="fa-solid fa-clock-rotate-left"></i><span class="nav-label">Riwayat Reservasi</span></a>
        <a href="{{ route('pelanggan.pembayaran') }}" class="customer-nav-link {{ request()->routeIs('pelanggan.pembayaran*') ? 'is-active' : '' }}"><i class="fa-regular fa-credit-card"></i><span class="nav-label">Pembayaran</span></a>
        <a href="{{ route('profil.index') }}" class="customer-nav-link {{ request()->routeIs('profil.index') ? 'is-active' : '' }}"><i class="fa-solid fa-user-gear"></i><span class="nav-label">Profil</span></a>
    </nav>
    <div class="help-card mt-auto rounded-xl border border-white/10 bg-white/[.04] p-3.5">
        <div class="mb-2 flex items-center gap-2 text-[10px] font-bold"><i class="fa-solid fa-headset"></i>Butuh Bantuan?</div>
        <p class="mb-3 text-[9px] leading-relaxed text-slate-400">Hubungi kami jika ada kendala atau pertanyaan.</p>
        <a href="https://wa.me/6285736084686" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border border-white/30 px-2.5 py-2 text-[9px]">Hubungi Admin <i class="fa-solid fa-arrow-right"></i></a>
    </div>
</aside>
