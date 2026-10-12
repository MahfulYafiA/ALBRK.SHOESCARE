<style>
    .customer-sidebar { background:#142a34; }
    .customer-nav-link { display:flex; align-items:center; gap:11px; border-radius:8px; padding:9px 10px; color:#d5dfe3; transition:background .18s,color .18s; }
    .customer-nav-link i { width:16px; text-align:center; font-size:12px; }
    .customer-nav-link:hover,.customer-nav-link.is-active { background:#ffffff29; color:#fff; }
    .customer-main { margin-left:218px; width:calc(100% - 218px); }
    @media(max-width:760px) {
        .customer-sidebar { width:62px; padding-left:6px; padding-right:6px; }
        .customer-sidebar .brand-label,.customer-sidebar .nav-label,.customer-sidebar .help-card { display:none; }
        .customer-sidebar .brand-link { justify-content:center; padding-left:0; padding-right:0; }
        .customer-sidebar .customer-nav-link { justify-content:center; padding:11px 0; font-size:0; }
        .customer-sidebar .customer-nav-link i { font-size:13px; }
        .customer-main { margin-left:62px!important; width:calc(100% - 62px)!important; }
    }
    @media print { .customer-sidebar { display:none!important; } .customer-main { margin:0!important; width:100%!important; } }
</style>
