<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pelanggan - ALBRK.SHOESCARE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; overflow: hidden; }
        
        /* Glassmorphism Panel */
        .glass-panel { 
            background: rgba(30, 41, 59, 0.4); 
            backdrop-filter: blur(12px); 
            border: 1px solid rgba(255, 255, 255, 0.05); 
        }

        .custom-scroll { scrollbar-width: none; -ms-overflow-style: none; }
        .custom-scroll::-webkit-scrollbar { display: none; }
        .admin-queue-table tbody td { padding-top: .5rem; padding-bottom: .5rem; }
    </style>
    @include('shared.albrk-redesign')
    <style>
        html, body { max-width: 100%; overflow-x: hidden !important; }
        body:has(.admin-sidebar) > main { min-width: 0; }
        body:has(.admin-sidebar) > main > .custom-scroll {
            min-width: 0;
            max-width: 100%;
            overflow-x: hidden;
            scrollbar-width: none !important;
            -ms-overflow-style: none;
        }
        body:has(.admin-sidebar) > main > .custom-scroll::-webkit-scrollbar,
        body:has(.admin-sidebar) > main .overflow-x-auto::-webkit-scrollbar { display: none !important; }
        body:has(.admin-sidebar) > main .overflow-x-auto { scrollbar-width: none; -ms-overflow-style: none; }
        body:has(.admin-sidebar) > main .bg-indigo-600\/10,
        body:has(.admin-sidebar) > main .bg-indigo-500\/10,
        body:has(.admin-sidebar) > main .bg-indigo-500\/20 { background-color: #eaf3f7 !important; }
        body:has(.admin-sidebar) > main .border-indigo-500\/30,
        body:has(.admin-sidebar) > main .border-indigo-500\/50 { border-color: #b9d2df !important; }
        body:has(.admin-sidebar) > main .text-indigo-400,
        body:has(.admin-sidebar) > main .text-indigo-500 { color: #3e7895 !important; }
        body:has(.admin-sidebar) > main .bg-slate-700 { background-color: #eaf3f7 !important; }
        body:has(.admin-sidebar) > main .text-slate-300 { color: #344b55 !important; }
        body:has(.admin-sidebar) > main .bg-slate-700.text-slate-300 { color: #3e7895 !important; }
    </style>
</head>
<body class="text-slate-200 antialiased flex h-screen overflow-hidden relative">
    @if(auth()->user()->isSuperAdmin())
        @include('shared.superadmin-sidebar')
    @else
        @include('shared.admin-sidebar')
    @endif

    {{-- KONTEN UTAMA (TANPA SIDEBAR SAMA SEKALI) --}}
    <main class="flex-1 flex flex-col min-w-0 bg-[#0f172a] relative z-10 h-screen">
        
        {{-- TOP NAVIGATION --}}
        <header class="flex shrink-0 items-center justify-between border-b border-[#29424c] bg-[#17303d] px-5 py-3.5 text-white md:px-8 z-40">
    <div class="flex items-center gap-3 md:gap-4"></div>
    @include('shared.admin-header-actions')
</header>
        {{-- AREA SCROLLABLE --}}
        <div class="p-6 lg:p-12 flex-1 overflow-y-auto custom-scroll relative">
            
            {{-- Background Glow Biru --}}
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/4"></div>

            {{-- ALERT MESSAGES --}}
            @if(session('success'))
                <div class="bg-indigo-500/20 border border-indigo-500/50 text-indigo-400 px-6 py-4 rounded-2xl mb-8 text-xs font-bold shadow-lg flex items-center gap-3 backdrop-blur-sm relative z-10">
                    <i class="fa-solid fa-circle-check text-lg"></i> {{ session('success') }}
                </div>
            @endif

            {{-- TABEL DATA --}}
            <div class="relative z-10 mb-10 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="admin-queue-table w-full min-w-[1120px] text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500">
                                <th class="w-[30%] px-4 py-3">Nama Pelanggan</th>
                                <th class="w-[35%] px-4 py-3">Email & Kontak</th>
                                <th class="w-[20%] px-4 py-3">Tipe Akun</th>
                                <th class="w-[15%] px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($users as $u)
                            <tr class="hover:bg-slate-50/70 group">
                                <td class="px-4 py-4">
                                    <p class="font-bold text-slate-800 leading-tight group-hover:text-sky-700 transition-colors">{{ $u->nama }}</p>
                                    <p class="text-[10px] text-slate-500 mt-1">Daftar: {{ $u->created_at->format('d/m/Y') }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-xs text-slate-700 font-medium">{{ $u->email }}</p>
                                    <p class="text-[10px] text-slate-500 mt-1">
                                        <i class="fa-brands fa-whatsapp text-emerald-600 mr-1"></i>{{ $u->no_telp ?? '-' }}
                                    </p>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex rounded-full bg-sky-50 px-2.5 py-1.5 text-[10px] font-bold text-sky-700">Pelanggan</span>
                                </td>
                                <td class="px-4 py-4">
                                    <form action="{{ route(auth()->user()->isSuperAdmin() ? 'superadmin.pelanggan.destroy' : 'admin.users.destroy', $u->id_user) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini? Seluruh data riwayat reservasi pelanggan ini juga akan terhapus.');" class="m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-9 h-9 rounded-full bg-red-500/10 text-red-500 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center active:scale-90">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-24 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fa-solid fa-users-slash text-6xl mb-4 text-slate-400"></i>
                                        <p class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-400">Belum ada pelanggan terdaftar</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                    <div class="border-t border-slate-700/80 px-6 py-4">{{ $users->links() }}</div>
                @endif
            </div>

        </div>
        <footer class="mt-auto w-full shrink-0 border-t border-slate-700/80 bg-[#0f172a]/50 px-6 py-6 text-center">
            <p class="text-xs text-slate-500">&copy; 2026 <span class="font-semibold text-slate-400">ALBRK.SHOESCARE</span></p>
        </footer>
    </main>

</body>
</html>
