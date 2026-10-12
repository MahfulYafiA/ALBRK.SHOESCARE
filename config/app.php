<?php

return [

    /* ... bagian atas biarkan default ... */

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Ubah dari UTC ke Asia/Jakarta agar tanggal reservasi & laporan omzet
    | sesuai dengan waktu lokal (WIB).
    |
    */

    'timezone' => 'Asia/Jakarta',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | Ubah ke 'id' agar pesan error dan format tanggal otomatis berbahasa Indonesia.
    |
    */

    'locale' => 'id',

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | Ubah ke id_ID agar saat membuat data testing (Seeder),
    | nama dan alamat yang muncul adalah nama orang Indonesia.
    |
    */

    'faker_locale' => 'id_ID',

    /* ... bagian bawah biarkan default ... */
];
