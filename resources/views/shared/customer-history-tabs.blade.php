<nav class="mt-4 flex flex-wrap gap-2" aria-label="Jenis riwayat">
    <a href="{{ route('pelanggan.riwayat-reservasi') }}" @class(['inline-flex h-9 items-center gap-2 rounded-xl px-4 text-xs font-semibold transition', 'bg-[#17303d] text-white' => ($active ?? '') === 'reservations', 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' => ($active ?? '') !== 'reservations'])>
        <i class="fa-solid fa-calendar-check"></i>Riwayat Reservasi
    </a>
    <a href="{{ route('pelanggan.pembayaran') }}" @class(['inline-flex h-9 items-center gap-2 rounded-xl px-4 text-xs font-semibold transition', 'bg-[#17303d] text-white' => ($active ?? '') === 'payments', 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' => ($active ?? '') !== 'payments'])>
        <i class="fa-regular fa-credit-card"></i>Riwayat Pembayaran
    </a>
</nav>
