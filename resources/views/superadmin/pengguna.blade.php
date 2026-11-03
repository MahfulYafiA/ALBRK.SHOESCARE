<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna - ALBRK.SHOESCARE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f3f7fa; overflow: hidden; }
        
        /* Glassmorphism Panel */
        .glass-panel { 
            background: rgba(30, 41, 59, 0.4); 
            backdrop-filter: blur(12px); 
            border: 1px solid rgba(255, 255, 255, 0.05); 
        }

        .custom-scroll { scrollbar-width: none; -ms-overflow-style: none; }
        .custom-scroll::-webkit-scrollbar { display: none; }
        .admin-queue-table tbody td { padding-top: .5rem; padding-bottom: .5rem; }
        input:-webkit-autofill, input:-webkit-autofill:hover, input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #1e293b inset !important;
            -webkit-text-fill-color: #f8fafc !important;
            caret-color: #f8fafc;
        }
    </style>
    @include('shared.albrk-redesign')
    <style>
        html, body { max-width: 100%; overflow-x: hidden !important; }
        body:has(.admin-sidebar) > main { min-width: 0; }
        body:has(.admin-sidebar) > main > .custom-scroll {
            min-width: 0;
            max-width: 100%;
            overflow-x: hidden !important;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }
        body:has(.admin-sidebar) > main > .custom-scroll::-webkit-scrollbar,
        body:has(.admin-sidebar) > main .overflow-x-auto::-webkit-scrollbar,
        body:has(.admin-sidebar) > main .custom-scroll::-webkit-scrollbar { display: none !important; }
        body:has(.admin-sidebar) > main .overflow-x-auto {
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }
        .admin-queue-table thead th:last-child,
        .admin-queue-table tbody td:last-child {
            padding-left: 0 !important;
        }
        body:has(.admin-sidebar) > main .bg-blue-600\/10,
        body:has(.admin-sidebar) > main .bg-blue-500\/10,
        body:has(.admin-sidebar) > main .bg-blue-500\/20 { background-color: #eaf3f7 !important; }
        body:has(.admin-sidebar) > main .border-blue-500\/20,
        body:has(.admin-sidebar) > main .border-blue-500\/30,
        body:has(.admin-sidebar) > main .border-blue-500\/50 { border-color: #b9d2df !important; }
        body:has(.admin-sidebar) > main .text-blue-400,
        body:has(.admin-sidebar) > main .text-blue-500 { color: #3e7895 !important; }
        body:has(.admin-sidebar) > main .bg-slate-700 { background-color: #eaf3f7 !important; }
        body:has(.admin-sidebar) > main .text-slate-300 { color: #344b55 !important; }
        body:has(.admin-sidebar) > main .bg-slate-700.text-slate-300 { color: #3e7895 !important; }
        body:has(.admin-sidebar) > main button[class~="bg-blue-500"] { background-color: #17303d !important; color: #fff !important; }
        body:has(.admin-sidebar) > main button[class~="hover:bg-blue-400"]:hover { background-color: #244653 !important; }
        body:has(.admin-sidebar) > main .border-blue-500,
        body:has(.admin-sidebar) > main .focus\:border-blue-500:focus { border-color: #3e7895 !important; }
        body:has(.admin-sidebar) > main .text-blue-400,
        body:has(.admin-sidebar) > main .text-blue-500 { color: #3e7895 !important; }
        body:has(.admin-sidebar) > main button[class~="hover:bg-blue-500"]:hover { background-color: #17303d !important; }
        body:has(.admin-sidebar) > main button[title="Hapus"] {
            background-color: #fef2f2 !important;
            border-color: #fecaca !important;
            color: #dc2626 !important;
        }
        body:has(.admin-sidebar) > main button[title="Hapus"]:hover {
            background-color: #dc2626 !important;
            border-color: #dc2626 !important;
            color: #fff !important;
        }
        body:has(.admin-sidebar) [id^="modal"] > div[class*="bg-slate-900"] {
            background-color: #fff !important;
            border-color: #d5dfe4 !important;
            color: #17303d !important;
        }
        body:has(.admin-sidebar) [id^="modal"] .text-white { color: #17303d !important; }
        body:has(.admin-sidebar) [id^="modal"] [class*="text-blue-"] { color: #3e7895 !important; }
        body:has(.admin-sidebar) [id^="modal"] [class*="shadow-blue-"] { --tw-shadow-color: #b9d2df !important; }
        body:has(.admin-sidebar) [id^="modal"] .text-slate-400 { color: #758690 !important; }
        body:has(.admin-sidebar) [id^="modal"] .text-slate-500 { color: #8a999f !important; }
        body:has(.admin-sidebar) [id^="modal"] .bg-slate-800,
        body:has(.admin-sidebar) [id^="modal"] .bg-slate-800\/50 { background-color: #f1f4f5 !important; }
        body:has(.admin-sidebar) [id^="modal"] input:not([type="checkbox"]),
        body:has(.admin-sidebar) [id^="modal"] select {
            background-color: #fff !important;
            color: #17303d !important;
            border-color: #d5dfe4 !important;
        }
        body:has(.admin-sidebar) [id^="modal"] button[class*="bg-blue-500"] {
            background-color: #17303d !important;
            color: #fff !important;
        }
        body:has(.admin-sidebar) [id^="modal"] button[class*="hover:bg-blue-400"]:hover { background-color: #244653 !important; }
        body:has(.admin-sidebar) [id^="modal"] button[class*="bg-slate-800"] { background-color: #eef2f4 !important; }
        body:has(.admin-sidebar) [id^="modal"] .border-slate-800 { border-color: #e1e7ea !important; }
    </style>
</head>
<body class="text-slate-700 antialiased flex h-screen overflow-hidden relative">
    @include('shared.superadmin-sidebar')

    @php
        // âœ… LOGIKA DINAMIS: Cek Role
        $accent = 'blue';
    @endphp

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 flex flex-col min-w-0 bg-[#f3f7fa] relative z-10 h-screen">
        
        {{-- TOP NAVIGATION --}}
        <header class="flex shrink-0 items-center justify-between border-b border-[#29424c] bg-[#17303d] px-5 py-3.5 text-white md:px-8 z-40">
    <div class="flex items-center gap-3 md:gap-4"></div>
    @include('shared.admin-header-actions')
</header>
        {{-- AREA SCROLLABLE --}}
        <div class="p-3 lg:p-4 flex-1 overflow-y-auto custom-scroll relative"><div class="w-full space-y-4">
            
            @if(session('success'))
                <div class="bg-blue-500/20 border border-blue-500/50 text-blue-400 px-6 py-4 rounded-2xl mb-8 text-xs font-bold shadow-lg flex items-center gap-3 backdrop-blur-sm relative z-10">
                    <i class="fa-solid fa-circle-check text-lg"></i> {{ session('success') }}
                </div>
            @endif

            {{-- HEADER HALAMAN --}}
            <section class="relative z-10 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"><div><h1 class="text-lg font-extrabold text-slate-900">Manajemen Pengguna</h1><p class="mt-1 text-xs text-slate-500">Kelola akun admin dan pelanggan.</p></div><button onclick="openModal()" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-[#17303d] px-4 text-xs font-bold text-white shadow-sm transition hover:bg-[#244653]"><i class="fa-solid fa-user-plus"></i>Tambah User Baru</button></section>

            {{-- TABEL DATA USER --}}
            <div class="relative z-10 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="admin-queue-table w-full min-w-[1120px] text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500">
                                <th class="w-[28%] px-4 py-3">Nama Pengguna</th>
                                <th class="w-[32%] px-4 py-3">Email & Kontak</th>
                                <th class="w-[18%] px-4 py-3">Role</th>
                                <th class="w-[22%] pl-1 pr-4 py-3">Aksi</th>
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
                                        <i class="fa-brands fa-whatsapp text-emerald-600 mr-1"></i> {{ $u->no_telp ?? '-' }}
                                    </p>
                                </td>
                                <td class="px-4 py-4">
                                    @if($u->status === 'Nonaktif')
                                        <span class="inline-flex rounded-full bg-rose-50 px-2.5 py-1.5 text-[10px] font-bold text-rose-700">Nonaktif</span>
                                    @elseif($u->id_role == 1)
                                        <span class="inline-flex rounded-full bg-violet-50 px-2.5 py-1.5 text-[10px] font-bold text-violet-700">Superadmin</span>
                                    @elseif($u->id_role == 2)
                                        <span class="inline-flex rounded-full bg-sky-50 px-2.5 py-1.5 text-[10px] font-bold text-sky-700">Admin</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1.5 text-[10px] font-bold text-slate-600">Pelanggan</span>
                                    @endif
                                </td>
                                <td class="pl-1 pr-4 py-4 text-left">
                                    {{-- Proteksi: Tidak bisa edit/hapus diri sendiri atau Superadmin lain --}}
                                    @if($u->id_user == auth()->user()->id_user || $u->id_role == 1)
                                        <span class="text-[10px] font-medium text-slate-500">{{ $u->id_user == auth()->user()->id_user ? 'Akun Anda' : 'Protected' }}</span>
                                    @else
                                        <div class="flex items-center justify-start gap-1.5">
                                            {{-- Tombol Edit --}}
                                            <button type="button" onclick="openEditModal({{ $u->id_user }}, '{{ $u->nama }}', '{{ $u->email }}', '{{ $u->no_telp ?? '' }}', {{ $u->id_role }})" class="w-8 h-8 rounded-full bg-amber-500/10 text-amber-500 border border-amber-500/20 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center active:scale-90" title="Edit">
                                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                                            </button>
                                            {{-- Tombol Nonaktifkan/Aktifkan --}}
                                            @if($u->status === 'Nonaktif')
                                                <form action="{{ route('superadmin.users.toggle', $u->id_user) }}" method="POST" class="m-0">
                                                    @csrf
                                                    <button type="submit" class="w-8 h-8 rounded-full bg-blue-500/10 text-blue-500 border border-blue-500/20 hover:bg-blue-500 hover:text-white transition-all flex items-center justify-center active:scale-90" title="Aktifkan">
                                                        <i class="fa-solid fa-check text-sm"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('superadmin.users.toggle', $u->id_user) }}" method="POST" class="m-0">
                                                    @csrf
                                                    <button type="submit" class="w-8 h-8 rounded-full bg-orange-500/10 text-orange-500 border border-orange-500/20 hover:bg-orange-500 hover:text-white transition-all flex items-center justify-center active:scale-90" title="Nonaktifkan">
                                                        <i class="fa-solid fa-ban text-sm"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('superadmin.users.destroy', $u->id_user) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini beserta semua data reservasinya?');" class="m-0">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-8 h-8 rounded-full bg-red-500/10 text-red-500 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center active:scale-90" title="Hapus">
                                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-sm text-slate-400">Belum ada data pengguna.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                    <div class="border-t border-slate-100 px-4 py-3">{{ $users->links() }}</div>
                @endif
            </div>

            {{-- FOOTER --}}
        </div>
        </div>
        <footer class="w-full shrink-0 border-t border-[#dbe5ea] bg-white px-6 py-4 text-center">
            <p class="text-xs text-slate-500">&copy; {{ now()->year }} <span class="font-semibold text-slate-600">ALBRK.SHOESCARE</span></p>
        </footer>
    </main>

    {{-- MODAL TAMBAH USER (DARK THEME) --}}
    <div id="modalAdmin" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity"></div>

        <div class="relative w-full max-w-md bg-slate-900 border border-slate-700 rounded-[2.5rem] shadow-2xl p-8 md:p-10 max-h-[90vh] overflow-y-auto custom-scroll">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-black uppercase tracking-tight text-white">Tambah <span class="text-{{ $accent }}-500 italic">User</span></h3>
                <button onclick="closeModal()" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <form id="addUserForm" action="{{ route('superadmin.users.store') }}" method="POST" autocomplete="off" class="space-y-5 m-0">
                @csrf
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nama Lengkap</label>
                    <input type="text" name="nama" required placeholder="Nama User" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ $accent }}-500 focus:bg-slate-800 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">No WhatsApp</label>
                    <input type="text" name="no_telp" required placeholder="0812xxxx" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ $accent }}-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Email Login</label>
                    <input type="email" name="email" required placeholder="email@albrk.com" autocomplete="off" readonly onfocus="this.removeAttribute('readonly')" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ $accent }}-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Role</label>
                    <select name="id_role" required class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ $accent }}-500 outline-none transition-all">
                        <option value="2">Admin</option>
                        <option value="3">Pelanggan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="Min 8 karakter" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly')" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ $accent }}-500 outline-none">
                </div>

                <div class="pt-6 mt-6 border-t border-slate-800">
                    <button type="submit" class="w-full bg-{{ $accent }}-500 text-white py-4 rounded-[1.2rem] font-black uppercase text-[11px] tracking-widest hover:bg-{{ $accent }}-400 transition-all shadow-lg shadow-{{ $accent }}-500/20 active:scale-95">
                        Simpan User Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT USER (DARK THEME) --}}
    <div id="modalEdit" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity"></div>

        <div class="relative w-full max-w-md bg-slate-900 border border-slate-700 rounded-[2.5rem] shadow-2xl p-8 md:p-10 max-h-[90vh] overflow-y-auto custom-scroll">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-black uppercase tracking-tight text-white">Edit <span class="text-{{ $accent }}-500 italic">User</span></h3>
                <button onclick="closeEditModal()" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <form id="formEdit" method="POST" class="space-y-5 m-0">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nama Lengkap</label>
                    <input type="text" name="nama" id="edit_nama" required placeholder="Nama User" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ $accent }}-500 focus:bg-slate-800 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">No WhatsApp</label>
                    <input type="text" name="no_telp" id="edit_telp" placeholder="0812xxxx" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ $accent }}-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Email Login</label>
                    <input type="email" name="email" id="edit_email" required placeholder="email@albrk.com" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ $accent }}-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Role</label>
                    <select name="id_role" id="edit_role" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ $accent }}-500 outline-none transition-all">
                        <option value="2">Admin</option>
                        <option value="3">Pelanggan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Kata Sandi Baru <span class="text-slate-500">(Kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" placeholder="Min 8 karakter" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ $accent }}-500 outline-none">
                </div>

                <div class="pt-6 mt-6 border-t border-slate-800">
                    <button type="submit" class="w-full bg-{{ $accent }}-500 text-white py-4 rounded-[1.2rem] font-black uppercase text-[11px] tracking-widest hover:bg-{{ $accent }}-400 transition-all shadow-lg shadow-{{ $accent }}-500/20 active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        const modal = document.getElementById('modalAdmin');
        const modalEdit = document.getElementById('modalEdit');

        function openModal() {
            document.getElementById('addUserForm')?.reset();
            modal.classList.replace('hidden', 'flex');
        }
        function closeModal() { modal.classList.replace('flex', 'hidden'); }

        function openEditModal(id, nama, email, telp, role) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_telp').value = telp;
            document.getElementById('edit_role').value = role;
            document.getElementById('formEdit').action = '/superadmin/users/' + id;
            modalEdit.classList.replace('hidden', 'flex');
        }

        function closeEditModal() { modalEdit.classList.replace('flex', 'hidden'); }
    </script>
</body>
</html>



