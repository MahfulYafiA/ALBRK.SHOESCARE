<style>
/* Admin pages use the compact navy and white layout from the reference. */
body:has(.admin-sidebar) {
    --admin-ink: #17303d;
    --admin-muted: #758690;
    --admin-line: #dce6eb;
    --admin-canvas: #f3f7fa;
    background: var(--admin-canvas) !important;
    color: var(--admin-ink) !important;
}
body:has(.admin-sidebar) input:-webkit-autofill,
body:has(.admin-sidebar) input:-webkit-autofill:hover,
body:has(.admin-sidebar) input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 1000px #fff inset !important;
    -webkit-text-fill-color: var(--admin-ink) !important;
    caret-color: var(--admin-ink);
}
html:has(.admin-sidebar),
body:has(.admin-sidebar) { width: 100%; max-width: 100%; overflow-x: clip !important; }
body:has(.admin-sidebar) main { flex: none; min-width: 0; box-sizing: border-box; overflow-x: clip; margin-left: 218px; width: calc(100% - 218px); max-width: calc(100% - 218px); background: var(--admin-canvas) !important; color: var(--admin-ink) !important; }
body:has(.admin-sidebar) main > header { background: #142a34 !important; border-color: #253d47 !important; color: #fff !important; }
body:has(.admin-sidebar) main > header .text-slate-400 { color: #b7c6cd !important; }
body:has(.admin-sidebar) main > header .text-white { color: #fff !important; }
body:has(.admin-sidebar) main > header > div:last-child { margin-left: auto !important; }
body:has(.admin-sidebar) main > header .bg-slate-800\/50 { background: #ffffff1f !important; color: #fff !important; border-color: #ffffff1f !important; }
body:has(.admin-sidebar) main > header .bg-slate-800\/50,
body:has(.admin-sidebar) main > header .bg-slate-800\/50 * { color: #fff !important; }
body:has(.admin-sidebar) main > header,
body:has(.admin-sidebar) > header {
    min-height: 64px;
    padding: 14px 20px !important;
}
body:has(.admin-sidebar) > header {
    box-sizing: border-box;
    margin-left: 218px;
    width: calc(100% - 218px);
}
body:has(.admin-sidebar) main > div.flex-1.overflow-y-auto {
    padding: 20px 24px !important;
}
body:has(.admin-sidebar) main h1,
body:has(.admin-sidebar) main h2,
body:has(.admin-sidebar) main h3 {
    color: #17303d !important;
}
body:has(.admin-sidebar) main > header h1 {
    color: #fff !important;
}
body:has(.admin-sidebar) main > header #userMenuButton { background: #ffffff1a !important; border-color: #ffffff1f !important; color: #fff !important; }
body:has(.admin-sidebar) main > header #userMenuButton:hover { background: #ffffff29 !important; }
body:has(.admin-sidebar) main > header #userMenuButton .text-slate-500 { color: #ffffff99 !important; }
body:has(.admin-sidebar) main > header #userMenuButton .text-blue-400\/80,
body:has(.admin-sidebar) main > header #userMenuButton .text-emerald-400\/80 { color: #ffffff99 !important; }
body:has(.admin-sidebar) main > footer { background: #fff !important; border-color: var(--admin-line) !important; }

.admin-sidebar { background: #142a34; color: #e8eff2; border-color: #253d47 !important; }
.admin-sidebar .admin-nav-link { display: flex; align-items: center; gap: 11px; border-radius: 8px; padding: 9px 10px; color: #d5dfe3; transition: background .18s, color .18s; }
.admin-sidebar .admin-nav-link i { width: 16px; text-align: center; font-size: 12px; }
.admin-sidebar .admin-nav-link:hover, .admin-sidebar .admin-nav-link.is-active { background: #ffffff29; color: #fff; }
.admin-sidebar .admin-help { color: #eef3f5; }
.superadmin-sidebar { background: #142a34 !important; color: #e8eff2 !important; border-color: #253d47 !important; }
.superadmin-sidebar .admin-nav-link { color: #d5dfe3 !important; }
.superadmin-sidebar .admin-nav-link:hover,
.superadmin-sidebar .admin-nav-link.is-active { background: #ffffff29 !important; color: #fff !important; }
.superadmin-sidebar .admin-help { color: #eef3f5 !important; }

body:has(.admin-sidebar) main .glass-panel,
body:has(.admin-sidebar) main .stat-card,
body:has(.admin-sidebar) main .action-card {
    background: #fff !important;
    border: 1px solid var(--admin-line) !important;
    color: var(--admin-ink) !important;
    box-shadow: 0 4px 14px rgba(23, 48, 61, .045);
    backdrop-filter: none !important;
}
body:has(.admin-sidebar) main .stat-card { transition: transform .18s ease, box-shadow .18s ease; }
body:has(.admin-sidebar) main .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(23,48,61,.08); }
body:has(.admin-sidebar) main .stat-icon { background: #eaf3ff !important; border-color: #b9d2ff !important; }
body:has(.admin-sidebar) main .glass-panel .text-white,
body:has(.admin-sidebar) main .stat-card .text-white,
body:has(.admin-sidebar) main .action-card .text-white { color: var(--admin-ink) !important; }
body:has(.admin-sidebar) main .text-slate-200,
body:has(.admin-sidebar) main .text-slate-300 { color: #344b55 !important; }
body:has(.admin-sidebar) main .text-slate-400,
body:has(.admin-sidebar) main .text-slate-500 { color: var(--admin-muted) !important; }
body:has(.admin-sidebar) main .text-slate-900 { color: var(--admin-ink) !important; }
body:has(.admin-sidebar) main .text-blue-400 { color: #4c88a5 !important; }
body:has(.admin-sidebar) main .text-blue-500 { color: #3e7895 !important; }
body:has(.admin-sidebar) main .bg-slate-800\/50,
body:has(.admin-sidebar) main .bg-slate-800\/40,
body:has(.admin-sidebar) main .bg-slate-800\/30 { background: #eef4f7 !important; }
body:has(.admin-sidebar) main .bg-slate-900 { background: #eef4f7 !important; }
body:has(.admin-sidebar) main .bg-slate-800 { background: #eef4f7 !important; }
body:has(.admin-sidebar) main .text-indigo-400,
body:has(.admin-sidebar) main .text-indigo-500 { color: #3e7895 !important; }
body:has(.admin-sidebar) main .border-slate-700,
body:has(.admin-sidebar) main .border-slate-700\/80 { border-color: var(--admin-line) !important; }
body:has(.admin-sidebar) main table thead tr { background: #eef4f7 !important; color: #637984 !important; }
body:has(.admin-sidebar) main table tbody tr { border-color: #e7eef2 !important; }
body:has(.admin-sidebar) main table tbody tr:hover { background: #f6f9fb !important; }
body:has(.admin-sidebar) main input:not([type=checkbox]):not([type=radio]),
body:has(.admin-sidebar) main select,
body:has(.admin-sidebar) main textarea { background-color: #fff !important; color: var(--admin-ink) !important; border-color: #d4e0e6 !important; }
body:has(.admin-sidebar) main input::placeholder,
body:has(.admin-sidebar) main textarea::placeholder { color: #91a2aa !important; }
body:has(.admin-sidebar) main select option { background: #fff !important; color: var(--admin-ink) !important; }
body:has(.admin-sidebar) main [id$="Modal"] .bg-slate-900,
body:has(.admin-sidebar) main [id$="Modal"] .bg-slate-800 { background: #fff !important; }
body:has(.admin-sidebar) main [id$="Modal"] .text-white { color: var(--admin-ink) !important; }
body:has(.admin-sidebar) main [id$="Modal"] .text-slate-400 { color: var(--admin-muted) !important; }
body:has(.admin-sidebar) main .custom-scroll { scrollbar-width: thin; scrollbar-color: #cbd8de transparent; }
body:has(.admin-sidebar) main .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
body:has(.admin-sidebar) main .custom-scroll::-webkit-scrollbar-thumb { background: #cbd8de; border-radius: 8px; }

@media (max-width: 760px) {
    body:has(.admin-sidebar) main > header,
    body:has(.admin-sidebar) > header { padding-right: 14px !important; padding-left: 14px !important; }
    body:has(.admin-sidebar) > header { margin-left: 62px; width: calc(100% - 62px); }
    body:has(.admin-sidebar) main > div.flex-1.overflow-y-auto { padding: 16px !important; }
    .admin-sidebar { width: 62px; padding-left: 6px; padding-right: 6px; }
    .admin-sidebar > a { justify-content: center; padding-left: 0; padding-right: 0; }
    .admin-sidebar > a span { display: none; }
    .admin-sidebar > a img { width: 36px; height: 36px; }
    .admin-sidebar .admin-nav-link { justify-content: center; padding: 11px 0; font-size: 0; }
    .admin-sidebar .admin-nav-link i { font-size: 13px; }
    .admin-sidebar .admin-help { display: none; }
    body:has(.admin-sidebar) main { margin-left: 62px; width: calc(100% - 62px); max-width: calc(100% - 62px); }
}
@media (max-width: 640px) { body:has(.admin-sidebar) main .glass-panel { border-radius: 1.25rem !important; } }
@media print { .admin-sidebar { display: none !important; } body:has(.admin-sidebar) main { margin: 0 !important; width: 100% !important; } }
</style>
