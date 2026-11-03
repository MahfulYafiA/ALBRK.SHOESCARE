@php
    $isStaff = $user->isAdmin() || $user->isSuperAdmin();
    $profileRoleLabel = $user->isSuperAdmin() ? 'Superadmin' : ($user->isAdmin() ? 'Admin' : 'Pelanggan');
@endphp
<!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Profil Saya - ALBRK.SHOESCARE</title>
<script src="https://cdn.tailwindcss.com"></script><link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">@include('shared.pelanggan-shell-styles')@if($isStaff)@include('shared.albrk-redesign')@endif
<style>
body{font-family:'Plus Jakarta Sans',sans-serif;background:#f3f7fa;color:#17303d}
.profile-scroll{scrollbar-width:thin;scrollbar-color:#cbd8de transparent}
@media (min-width:1024px) and (min-height:900px){main.profile-scroll{overflow-y:hidden!important}}
@media (min-width:1024px) and (min-height:900px){
main.profile-scroll>div{display:flex;flex-direction:column;gap:.5rem!important}
main.profile-scroll>div> :not([hidden])~ :not([hidden]){margin-top:0!important}
main.profile-scroll section{padding:.75rem!important}
main.profile-scroll section>div:first-child{margin-bottom:.5rem!important}
main.profile-scroll .grid.items-start{gap:.5rem!important}
main.profile-scroll form{gap:.5rem!important}
main.profile-scroll form> :not([hidden])~ :not([hidden]){margin-top:.5rem!important}
main.profile-scroll input{height:2.25rem!important}
main.profile-scroll textarea{height:3.25rem!important;min-height:3.25rem!important;padding:.5rem .75rem!important}
main.profile-scroll dl>div{margin-top:.35rem!important;padding-bottom:.35rem!important}
main.profile-scroll section .mb-5,main.profile-scroll section .mb-4{margin-bottom:.5rem!important}
main.profile-scroll .bg-slate-50{padding:.6rem!important}
main.profile-scroll label>span:first-child{margin-bottom:.2rem!important}
main.profile-scroll .h-20,main.profile-scroll .sm\:h-24{height:4rem!important;width:4rem!important}
main.profile-scroll button{min-height:2.25rem}
}
@if($isStaff)
body:has(.admin-sidebar) main.profile-scroll{position:fixed;top:65px;right:0;bottom:0;left:218px;box-sizing:border-box;width:auto!important;max-width:none!important;min-height:0!important;margin:0!important;overflow-x:hidden!important;overflow-y:auto!important;overscroll-behavior-y:contain}
@media(max-width:760px){body:has(.admin-sidebar) main.profile-scroll{left:62px}}
@endif
</style></head>
<body class="flex h-screen overflow-hidden antialiased">
@if($isStaff)
    @if($user->isSuperAdmin())
        @include('shared.superadmin-sidebar')
    @else
        @include('shared.admin-sidebar')
    @endif
@else
    @include('shared.pelanggan-sidebar')
@endif
<div class="customer-main ml-[218px] flex h-screen min-h-0 min-w-0 flex-1 flex-col">
@include('shared.pelanggan-header',['pageTitle'=>'','showNotificationIcon'=>true,'profileRoleLabel'=>$profileRoleLabel])
<main class="profile-scroll flex-1 overflow-y-auto p-2 lg:p-2"><div class="w-full space-y-2">

@if(session('success'))<div role="status" class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800"><i class="fa-solid fa-circle-check"></i>{{ session('success') }}</div>@endif
@if($errors->any())<div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800"><p class="font-semibold">Periksa kembali data yang dimasukkan.</p><ul class="mt-1 list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

<section class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm"><div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
<div class="flex min-w-0 items-center gap-3"><div class="grid h-14 w-14 shrink-0 place-items-center overflow-hidden rounded-full border border-slate-200 bg-slate-100 text-lg font-bold text-slate-500 sm:h-16 sm:w-16">
@if($user->foto_profil)<img id="profilePreview" src="{{ route('profil.foto') }}" alt="Foto {{ $user->nama }}" class="h-full w-full object-cover" onerror="this.classList.add('hidden');document.getElementById('profileInitials').classList.remove('hidden')">@endif
<span id="profileInitials" class="{{ $user->foto_profil ? 'hidden' : '' }}">{{ strtoupper(substr($user->nama,0,2)) }}</span></div>
<div class="min-w-0"><p class="text-xl font-extrabold text-slate-900">{{ $user->nama }}</p><p class="mt-1 truncate text-sm text-slate-500">{{ $user->email }}</p><p class="mt-1 text-xs text-slate-500">{{ $user->no_telp ?: 'Nomor telepon belum ditambahkan' }}</p><div class="mt-3 flex flex-wrap items-center gap-2"><span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200">{{ $user->status ?: 'Aktif' }}</span><span class="text-[11px] text-slate-400">Bergabung {{ $user->created_at?->translatedFormat('d M Y') ?? '—' }}</span></div></div></div>
<form id="photoForm" method="POST" action="{{ route('profil.updateFoto') }}" enctype="multipart/form-data" class="flex flex-col items-start gap-2 sm:items-end">@csrf @method('PATCH')<input id="photoInput" type="file" name="foto_profil" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="sr-only" onchange="previewProfilePhoto(this)"><button type="button" onclick="document.getElementById('photoInput').click()" class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50"><i class="fa-regular fa-image"></i>Ubah Foto</button><span class="text-[10px] text-slate-400">JPG, PNG, WEBP · maks. 2 MB</span><button id="photoSubmit" type="submit" class="hidden text-xs font-semibold text-[#17303d] underline">Simpan foto baru</button></form>
</div></section>

<div class="grid items-stretch gap-2 lg:grid-cols-2">
<div class="flex flex-col gap-2 lg:contents">
<section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7 lg:col-start-1 lg:row-start-1"><div class="mb-5 flex items-center gap-3"><span class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 text-[#17303d]"><i class="fa-regular fa-user"></i></span><div><h2 class="text-base font-bold text-slate-900">Informasi Pribadi</h2><p class="mt-0.5 text-xs text-slate-500">Perbarui nama, email login, dan nomor telepon.</p></div></div>
<form method="POST" action="{{ route('profil.update') }}" class="space-y-4">@csrf @method('PATCH')<div class="grid gap-4 sm:grid-cols-2"><label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Nama Lengkap</span><input name="nama" value="{{ old('nama',$user->nama) }}" maxlength="40" required class="h-11 w-full rounded-xl border border-slate-200 px-3.5 text-sm text-slate-800 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-100">@error('nama')<span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>@enderror</label>
<label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Nomor Telepon</span><input name="no_hp" value="{{ old('no_hp',$user->no_telp) }}" maxlength="15" autocomplete="tel" placeholder="Contoh: 081234567890" class="h-11 w-full rounded-xl border border-slate-200 px-3.5 text-sm text-slate-800 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-100">@error('no_hp')<span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>@enderror</label></div>
<label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Email</span><input type="email" name="email" value="{{ old('email',$user->email) }}" maxlength="50" required autocomplete="email" class="h-11 w-full rounded-xl border border-slate-200 px-3.5 text-sm text-slate-800 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-100"><span class="mt-1 block text-[11px] text-slate-400">Email ini digunakan untuk masuk ke akun Anda.</span>@error('email')<span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>@enderror</label>
<button class="inline-flex h-11 items-center gap-2 rounded-xl bg-[#17303d] px-5 text-xs font-semibold text-white transition hover:bg-[#244653]"><i class="fa-solid fa-check"></i>Simpan Perubahan</button></form></section>

<section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7 lg:col-start-2 lg:row-start-2"><div class="mb-4 flex items-center gap-3"><span class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 text-[#17303d]"><i class="fa-solid fa-location-dot"></i></span><div><h2 class="text-base font-bold text-slate-900">Alamat</h2><p class="mt-0.5 text-xs text-slate-500">Alamat utama untuk kebutuhan layanan.</p></div></div><p class="mb-4 rounded-xl bg-slate-50 px-3.5 py-3 text-xs leading-relaxed text-slate-500">Alamat digunakan untuk kebutuhan layanan antar-jemput jika tersedia.</p>
<form method="POST" action="{{ route('profil.update') }}" class="space-y-3">@csrf @method('PATCH')<input type="hidden" name="nama" value="{{ old('nama',$user->nama) }}"><input type="hidden" name="email" value="{{ old('email',$user->email) }}"><input type="hidden" name="no_hp" value="{{ old('no_hp',$user->no_telp) }}"><label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Alamat Lengkap</span><textarea name="alamat" rows="3" maxlength="200" placeholder="Nama jalan, nomor, kelurahan..." class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-100">{{ old('alamat',$user->alamat) }}</textarea>@error('alamat')<span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>@enderror</label><button class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"><i class="fa-regular fa-floppy-disk"></i>Simpan Alamat</button></form></section>
</div>

<div class="flex flex-col gap-2 lg:contents">
<section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7 lg:col-start-2 lg:row-start-1"><div class="mb-4 flex items-center gap-3"><span class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 text-[#17303d]"><i class="fa-regular fa-id-card"></i></span><h2 class="text-base font-bold text-slate-900">Informasi Akun</h2></div><dl class="space-y-3 text-xs"><div class="flex justify-between gap-3 border-b border-slate-100 pb-3"><dt class="text-slate-500">ID {{ $profileRoleLabel }}</dt><dd class="font-semibold text-slate-800">#{{ $user->id_user }}</dd></div><div class="flex justify-between gap-3 border-b border-slate-100 pb-3"><dt class="text-slate-500">Email</dt><dd class="max-w-[65%] truncate text-right font-semibold text-slate-800">{{ $user->email }}</dd></div><div class="flex justify-between gap-3 border-b border-slate-100 pb-3"><dt class="text-slate-500">Tanggal Registrasi</dt><dd class="text-right font-semibold text-slate-800">{{ $user->created_at?->translatedFormat('d M Y') ?? '—' }}</dd></div><div class="flex justify-between gap-3"><dt class="text-slate-500">Status Akun</dt><dd class="font-semibold text-emerald-700">{{ $user->status ?: 'Aktif' }}</dd></div></dl></section>
<section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7 lg:col-start-1 lg:row-start-2"><div class="mb-4 flex items-center gap-3"><span class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 text-[#17303d]"><i class="fa-solid fa-shield-halved"></i></span><div><h2 class="text-base font-bold text-slate-900">Keamanan Akun</h2><p class="mt-0.5 text-xs text-slate-500">Jaga akun dengan kata sandi yang kuat.</p></div></div><div class="mb-5 rounded-xl bg-slate-50 p-4"><p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Status Keamanan</p><p class="mt-1 text-sm font-semibold text-slate-700"><i class="fa-solid fa-lock mr-1.5 text-emerald-600"></i>Kata sandi terlindungi</p><p class="mt-2 text-[11px] text-slate-500">Informasi waktu pembaruan kata sandi tidak tersedia.</p></div>
<form method="POST" action="{{ route('profil.updatePassword') }}" class="space-y-3">@csrf @method('PATCH')<label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Password Saat Ini</span><input type="password" name="current_password" autocomplete="current-password" required class="h-11 w-full rounded-xl border border-slate-200 px-3.5 text-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-100">@error('current_password')<span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>@enderror</label><label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Password Baru</span><input type="password" name="new_password" autocomplete="new-password" minlength="8" required class="h-11 w-full rounded-xl border border-slate-200 px-3.5 text-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-100">@error('new_password')<span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>@enderror</label><label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Konfirmasi Password Baru</span><input type="password" name="new_password_confirmation" autocomplete="new-password" minlength="8" required class="h-11 w-full rounded-xl border border-slate-200 px-3.5 text-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-100"></label><button class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-[#17303d] px-4 text-xs font-semibold text-white transition hover:bg-[#244653]"><i class="fa-solid fa-key"></i>Ubah Password</button></form></section>

</div></div></div></main><footer class="shrink-0 border-t border-[#dbe5ea] bg-white px-6 py-4 text-center text-xs text-slate-500">&copy; {{ now()->year }} <span class="font-semibold text-slate-600">ALBRK.SHOESCARE</span></footer></div>
<script>
function previewProfilePhoto(input){const file=input.files?.[0];if(!file)return;const allowed=['image/jpeg','image/png','image/webp'];if(!allowed.includes(file.type)||file.size>2*1024*1024){input.value='';alert('Pilih foto JPG, PNG, atau WEBP dengan ukuran maksimal 2 MB.');return;}const image=document.getElementById('profilePreview');const initials=document.getElementById('profileInitials');const url=URL.createObjectURL(file);if(image){image.src=url;image.classList.remove('hidden');image.onload=()=>URL.revokeObjectURL(url);}else{const preview=document.createElement('img');preview.id='profilePreview';preview.src=url;preview.alt='Preview foto profil';preview.className='h-full w-full object-cover';preview.onload=()=>URL.revokeObjectURL(url);initials.before(preview);initials.classList.add('hidden');}document.getElementById('photoSubmit').classList.remove('hidden');}
</script></body></html>
