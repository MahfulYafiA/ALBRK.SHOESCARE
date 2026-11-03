<dialog id="userGuideDialog" class="fixed inset-0 m-0 h-screen max-h-none w-screen max-w-none rounded-none p-0 text-slate-800 shadow-2xl backdrop:bg-slate-950/80">
    @php
        $guideFiles = glob(public_path('files/*.pdf')) ?: [];
        usort($guideFiles, fn ($first, $second) => filemtime($second) <=> filemtime($first));
        $guidePath = $guideFiles[0] ?? null;
        $guideFilename = $guidePath ? basename($guidePath) : null;
        $guideUrl = $guideFilename ? asset('files/'.rawurlencode($guideFilename)) : null;
    @endphp
    <div class="flex items-start justify-between border-b border-slate-100 px-6 py-5">
        <div><p class="text-xs font-bold uppercase tracking-widest text-sky-700">ALBRK.SHOESCARE</p><h2 class="mt-1 text-xl font-extrabold">Panduan Pengguna</h2></div>
        <button type="button" onclick="document.getElementById('userGuideDialog').close()" aria-label="Tutup panduan" class="grid h-8 w-8 place-items-center rounded-full text-slate-500 hover:bg-slate-100"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="h-[calc(100%-8.5rem)] bg-slate-100">
        @if($guidePath)
            <iframe src="{{ $guideUrl }}?v={{ filemtime($guidePath) }}" title="Pratinjau PDF {{ $guideFilename }}" class="h-full w-full" loading="eager"></iframe>
        @else
            <div class="grid h-full place-items-center p-8 text-center"><div><i class="fa-regular fa-file-pdf text-4xl text-slate-400"></i><p class="mt-3 font-semibold text-slate-700">File PDF panduan belum tersedia</p><p class="mt-1 text-sm text-slate-500">Tambahkan file PDF ke folder <code>public/files</code> melalui VS Code.</p></div></div>
        @endif
    </div>
    <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 px-6 py-4">
        @if($guidePath)<a href="{{ $guideUrl }}" target="_blank" rel="noopener" class="text-xs font-semibold text-slate-600 hover:underline">Buka PDF di tab baru</a>@else<span></span>@endif
        <button type="button" onclick="document.getElementById('userGuideDialog').close()" class="ml-auto rounded-xl border border-slate-200 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50">Tutup</button>
    </div>
</dialog>
<script>
document.getElementById('userGuideDialog')?.addEventListener('click', event => {
    if (event.target === event.currentTarget) event.currentTarget.close();
});
</script>
