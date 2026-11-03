<div class="relative">
    <button type="button" id="userGuideTrigger" aria-label="Panduan pengguna" title="Panduan pengguna" aria-expanded="false" onclick="const menu=document.getElementById('userGuideMenu');const open=menu.classList.toggle('hidden');this.setAttribute('aria-expanded',String(!open))" class="grid h-9 w-9 place-items-center rounded-full border border-white/10 bg-white/10 text-slate-200 transition hover:bg-white/15">
        <span class="grid h-5 w-5 place-items-center rounded-full bg-[#17303d] text-[13px] font-extrabold leading-none text-white">?</span>
    </button>
    <div id="userGuideMenu" class="absolute right-0 z-[60] mt-2 hidden w-56 overflow-hidden rounded-xl border border-slate-200 bg-white p-1 shadow-xl">
        <button type="button" onclick="document.getElementById('userGuideMenu').classList.add('hidden');document.getElementById('userGuideTrigger').setAttribute('aria-expanded','false');document.getElementById('userGuideDialog').showModal()" class="flex w-full items-center gap-3 rounded-lg px-3 py-3 text-left text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
            <i class="fa-solid fa-eye w-5 text-center text-[#17303d]"></i><span>Panduan Pengguna</span>
        </button>
    </div>
</div>
<script>
document.addEventListener('click', event => {
    const menu = document.getElementById('userGuideMenu');
    const trigger = document.getElementById('userGuideTrigger');
    if (menu && trigger && !menu.contains(event.target) && !trigger.contains(event.target)) {
        menu.classList.add('hidden');
        trigger.setAttribute('aria-expanded', 'false');
    }
});
</script>
