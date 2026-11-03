<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Layanan - ALBRK.SHOESCARE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f3f7fa; color: #17303d; }
        .custom-scroll { scrollbar-width: thin; scrollbar-color: #cbd8de transparent; }
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd8de; border-radius: 8px; }
        @media (min-width: 600px) { .service-modal-fields { grid-template-columns: 120px minmax(0, 1fr); } .service-modal-inputs { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    </style>
    @include('shared.albrk-redesign')
    <style>
        html, body { width: 100%; max-width: 100%; overflow-x: hidden !important; }
        body:has(.admin-sidebar) > main { flex: 1 1 0%; margin-left: 218px; width: calc(100% - 218px); max-width: calc(100% - 218px); overflow-x: hidden !important; }
        body:has(.admin-sidebar) > main > div { min-width: 0; max-width: 100%; overflow-x: hidden; }
        @media (max-width: 760px) { body:has(.admin-sidebar) > main { margin-left: 62px; width: calc(100% - 62px); max-width: calc(100% - 62px); } }
    </style>
</head>


<body class="text-slate-700 antialiased flex h-screen overflow-hidden selection:bg-sky-100 selection:text-[#17303d]">
    @include('shared.admin-sidebar')

    <main class="flex h-screen min-w-0 flex-1 flex-col bg-[#f3f7fa]">
        <header class="flex shrink-0 items-center justify-between border-b border-[#29424c] bg-[#17303d] px-5 py-3.5 text-white md:px-8 z-40">
            <div class="flex items-center gap-3 md:gap-4"></div>
            @include('shared.admin-header-actions')
        </header>

        <div class="custom-scroll flex-1 overflow-y-auto p-3 lg:p-4">
            <div class="w-full space-y-6">
                @if(session('success'))<div role="status" class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800"><i class="fa-solid fa-circle-check"></i>{{ session('success') }}</div>@endif
                @if(session('error'))<div role="alert" class="flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800"><i class="fa-solid fa-circle-exclamation"></i>{{ session('error') }}</div>@endif
                @if($errors->any())<div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ $errors->first() }}</div>@endif

                <section class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div><p class="text-xs font-semibold uppercase tracking-[.16em] text-sky-700">Katalog & harga</p><h1 class="mt-1 text-2xl font-extrabold tracking-tight text-[#17303d] sm:text-3xl">Master Layanan</h1><p class="mt-2 text-sm text-slate-500">Kelola layanan, harga, dan gambar yang tampil di halaman toko.</p></div>
                    <button type="button" onclick="openAddModal()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#17303d] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#244653] focus:outline-none focus:ring-2 focus:ring-[#17303d]/30"><i class="fa-solid fa-plus"></i>Tambah layanan</button>
                </section>

                <section id="pengaturan-sistem" class="grid scroll-mt-6 gap-5 xl:grid-cols-2" aria-label="Gambar halaman utama">
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="mb-4 flex items-center gap-3"><span class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 text-[#17303d]"><i class="fa-regular fa-image"></i></span><div><h2 class="text-sm font-bold text-slate-900">Banner beranda</h2><p class="mt-0.5 text-xs text-slate-500">Gambar utama halaman depan</p></div></div>
                        <form action="{{ route('update.hero.universal') }}" method="POST" enctype="multipart/form-data" class="space-y-4">@csrf
                            <label for="hero_input" class="group relative block h-44 cursor-pointer overflow-hidden rounded-xl border border-slate-200 bg-slate-50 sm:h-52">
                                <img id="hero_image_preview" src="{{ $bannerTersimpan }}" alt="Pratinjau banner beranda" class="h-full w-full object-cover {{ $hasBanner ? '' : 'hidden' }}">
                                <div id="hero_placeholder" class="flex h-full flex-col items-center justify-center gap-2 text-slate-400 {{ $hasBanner ? 'hidden' : '' }}"><i class="fa-solid fa-cloud-arrow-up text-2xl"></i><span class="text-xs font-semibold">Pilih gambar banner</span></div>
                                <span class="absolute bottom-3 right-3 rounded-lg bg-white/95 px-3 py-2 text-xs font-bold text-slate-700 shadow-sm"><i class="fa-solid fa-camera mr-1.5"></i>Ganti gambar</span>
                                <input type="file" name="hero_image" id="hero_input" accept="image/jpeg,image/png,image/gif,image/webp" class="sr-only" onchange="previewHero(event)" {{ $hasBanner ? '' : 'required' }}>
                            </label>
                            <p class="text-[11px] text-slate-500">JPG, PNG, GIF, atau WebP Â· Maksimal 2 MB</p>
                            <button type="submit" class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50"><i class="fa-solid fa-floppy-disk mr-1.5"></i>Simpan banner</button>
                        </form>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="mb-4 flex items-center gap-3"><span class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 text-[#17303d]"><i class="fa-solid fa-store"></i></span><div><h2 class="text-sm font-bold text-slate-900">Foto profil toko</h2><p class="mt-0.5 text-xs text-slate-500">Gambar pada bagian Tentang Kami</p></div></div>
                        <form action="{{ route('update.tentang.universal') }}" method="POST" enctype="multipart/form-data" class="space-y-4">@csrf
                            <label for="tentang_input" class="group relative block h-44 cursor-pointer overflow-hidden rounded-xl border border-slate-200 bg-slate-50 sm:h-52">
                                <img id="tentang_image_preview" src="{{ $tentangTersimpan }}" alt="Pratinjau foto profil toko" class="h-full w-full bg-white object-contain p-4 {{ $hasTentang ? '' : 'hidden' }}">
                                <div id="tentang_placeholder" class="flex h-full flex-col items-center justify-center gap-2 text-slate-400 {{ $hasTentang ? 'hidden' : '' }}"><i class="fa-solid fa-cloud-arrow-up text-2xl"></i><span class="text-xs font-semibold">Pilih foto profil toko</span></div>
                                <span class="absolute bottom-3 right-3 rounded-lg bg-white/95 px-3 py-2 text-xs font-bold text-slate-700 shadow-sm"><i class="fa-solid fa-camera mr-1.5"></i>Ganti gambar</span>
                                <input type="file" name="tentang_image" id="tentang_input" accept="image/jpeg,image/png,image/gif,image/webp" class="sr-only" onchange="previewTentang(event)" {{ $hasTentang ? '' : 'required' }}>
                            </label>
                            <p class="text-[11px] text-slate-500">JPG, PNG, GIF, atau WebP Â· Maksimal 2 MB</p>
                            <button type="submit" class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50"><i class="fa-solid fa-floppy-disk mr-1.5"></i>Simpan foto toko</button>
                        </form>
                    </article>
                </section>

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6"><div><h2 class="text-base font-bold text-slate-900">Daftar layanan</h2><p class="mt-1 text-xs text-slate-500">{{ $layanans->count() }} layanan tersedia</p></div><span class="rounded-full bg-slate-100 px-3 py-1.5 text-[11px] font-semibold text-slate-600">Harga per pasang</span></div>
                    @if($layanans->isNotEmpty())
                        <div class="grid gap-4 p-4 sm:p-5 md:grid-cols-2 xl:grid-cols-3">
                            @foreach($layanans as $l)
                                @php($gambarLayanan = $l->gambar ? route('media.public', ['path' => $l->gambar]) : '')
                                <article class="group overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md">
                                    <div class="flex gap-4 p-4">
                                        @if($gambarLayanan)<img src="{{ $gambarLayanan }}" alt="Foto {{ $l->nama_layanan }}" class="h-20 w-20 shrink-0 rounded-lg border border-slate-100 object-cover">@else<span class="grid h-20 w-20 shrink-0 place-items-center rounded-lg border border-dashed border-slate-300 bg-slate-50 text-slate-400"><i class="fa-solid fa-image text-xl"></i></span>@endif
                                        <div class="min-w-0 flex-1"><h3 class="truncate font-bold text-slate-900">{{ $l->nama_layanan }}</h3><p class="mt-1 line-clamp-3 text-xs leading-relaxed text-slate-500">{{ $l->deskripsi ?: 'Belum ada deskripsi layanan.' }}</p></div>
                                    </div>
                                    <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/70 px-4 py-3"><p class="font-extrabold text-[#17303d]">Rp {{ number_format($l->harga, 0, ',', '.') }} <span class="text-[10px] font-medium text-slate-500">/ pasang</span></p><div class="flex items-center gap-2"><button type="button" data-edit-service data-id="{{ $l->id_layanan }}" data-name="{{ $l->nama_layanan }}" data-price="{{ $l->harga }}" data-description="{{ $l->deskripsi }}" data-image="{{ $gambarLayanan }}" class="grid h-9 w-9 place-items-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700" aria-label="Edit {{ $l->nama_layanan }}"><i class="fa-solid fa-pen text-xs"></i></button><form action="{{ route('admin.layanan.destroy', $l->id_layanan) }}" method="POST" onsubmit="return confirm('Hapus layanan ini beserta fotonya?')" class="m-0">@csrf @method('DELETE')<button type="submit" class="grid h-9 w-9 place-items-center rounded-lg border border-rose-200 bg-white text-rose-600 transition hover:bg-rose-50" aria-label="Hapus {{ $l->nama_layanan }}"><i class="fa-regular fa-trash-can text-xs"></i></button></form></div></div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-16 text-center"><span class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-slate-100 text-slate-400"><i class="fa-solid fa-box-open"></i></span><h3 class="mt-3 text-sm font-bold text-slate-800">Belum ada layanan</h3><p class="mt-1 text-xs text-slate-500">Tambahkan layanan pertama untuk ditampilkan di katalog.</p></div>
                    @endif
                </section>
            </div>
        </div>
        <footer class="shrink-0 border-t border-[#dbe5ea] bg-white px-6 py-4 text-center text-xs text-slate-500">&copy; {{ now()->year }} <span class="font-semibold text-slate-600">ALBRK.SHOESCARE</span></footer>
    </main>

    {{-- MODAL --}}
    <div id="modalLayanan" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm">
        <div class="relative max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl sm:overflow-visible sm:p-7">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div><p class="text-xs font-semibold uppercase tracking-wider text-sky-700">Katalog layanan</p><h3 id="modalTitle" class="mt-1 text-xl font-extrabold text-[#17303d]">Tambah layanan</h3></div>
                <button type="button" onclick="toggleModal()" class="grid h-9 w-9 shrink-0 place-items-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-50" aria-label="Tutup">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="layananForm" method="POST" enctype="multipart/form-data" class="space-y-5 m-0">
                @csrf
                <div id="methodField"></div>

                <div class="service-modal-fields grid items-start gap-5">
                    <div>
                        <label for="inputGambar" class="mb-2 block text-xs font-semibold text-slate-700">Gambar layanan</label>
                        <input type="file" name="gambar" id="inputGambar" accept="image/jpeg,image/png,image/gif" class="sr-only" onchange="previewItem(event)">
                        <label for="inputGambar" class="mx-auto flex h-24 w-24 cursor-pointer items-center justify-center overflow-hidden rounded-xl border border-dashed border-slate-300 bg-slate-50 text-slate-500 transition hover:border-sky-400 hover:bg-sky-50">
                            <img id="item_preview" src="" alt="Pratinjau gambar layanan" class="hidden h-full w-full object-contain">
                            <span id="item_placeholder" class="grid h-10 w-10 place-items-center rounded-lg bg-white text-slate-400"><i class="fa-solid fa-image text-xl"></i></span>
                        </label>
                        <p class="mt-1 text-center text-[11px] text-slate-500">Klik untuk tambah gambar</p>
                    </div>

                    <div class="space-y-4">
                        <div class="service-modal-inputs grid gap-4">
                            <div>
                                <label for="inputNama" class="mb-2 block text-xs font-semibold text-slate-700">Nama layanan</label>
                                <input type="text" name="nama_layanan" id="inputNama" required placeholder="Contoh: Fast Clean" class="h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100 placeholder:text-slate-400">
                            </div>
                            <div>
                                <label for="inputHarga" class="mb-2 block text-xs font-semibold text-slate-700">Harga per pasang (Rp)</label>
                                <input type="number" name="harga" id="inputHarga" min="0" required placeholder="35000" class="h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100 placeholder:text-slate-400">
                            </div>
                        </div>
                        <div>
                            <label for="inputDeskripsi" class="mb-2 block text-xs font-semibold text-slate-700">Deskripsi</label>
                            <textarea name="deskripsi" id="inputDeskripsi" rows="3" required placeholder="Jelaskan proses dan bagian sepatu yang dirawat..." class="w-full resize-y rounded-lg border border-slate-200 bg-white px-3 py-3 text-sm text-slate-800 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100 placeholder:text-slate-400"></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-slate-100 pt-5">
                    <button type="button" onclick="toggleModal()" class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="rounded-lg bg-[#17303d] px-5 py-2.5 text-xs font-bold text-white transition hover:bg-[#244653]">Simpan layanan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewHero(event) {
            previewImage(event, 'hero_image_preview', 'hero_placeholder');
        }

        function previewTentang(event) {
            previewImage(event, 'tentang_image_preview', 'tentang_placeholder');
        }

        function previewItem(event) {
            const file = event.target.files && event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('item_preview');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                document.getElementById('item_placeholder').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }

        function previewImage(event, imageId, placeholderId) {
            const file = event.target.files && event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(imageId).src = e.target.result;
                document.getElementById(imageId).classList.remove('hidden');
                document.getElementById(placeholderId).classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }

        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Tambah layanan';
            document.getElementById('layananForm').action = "{{ route('admin.layanan.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('inputNama').value = '';
            document.getElementById('inputHarga').value = '';
            document.getElementById('inputDeskripsi').value = '';
            document.getElementById('inputGambar').value = '';
            document.getElementById('item_preview').src = '';
            document.getElementById('item_preview').classList.add('hidden');
            document.getElementById('item_placeholder').classList.remove('hidden');
            toggleModal();
        }

        function openEditModal(id, nama, harga, deskripsi, gambarUrl) {
            document.getElementById('modalTitle').textContent = 'Edit layanan';
            document.getElementById('layananForm').action = "/admin/layanan/" + id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('inputNama').value = nama;
            document.getElementById('inputHarga').value = harga;
            document.getElementById('inputDeskripsi').value = deskripsi || '';
            document.getElementById('inputGambar').value = '';
            const preview = document.getElementById('item_preview');
            const placeholder = document.getElementById('item_placeholder');
            if (gambarUrl) {
                preview.src = gambarUrl;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                preview.src = '';
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
            toggleModal();
        }

        function toggleModal() {
            const modal = document.getElementById('modalLayanan');
            const isOpen = !modal.classList.contains('hidden');
            modal.classList.toggle('hidden', isOpen);
            modal.classList.toggle('flex', !isOpen);
            document.body.style.overflow = isOpen ? 'auto' : 'hidden';
        }

        document.querySelectorAll('[data-edit-service]').forEach(button => {
            button.addEventListener('click', () => openEditModal(
                button.dataset.id,
                button.dataset.name,
                button.dataset.price,
                button.dataset.description,
                button.dataset.image
            ));
        });

    </script>
</body>
</html>

