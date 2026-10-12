<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | Tetap gunakan 'log' saat development.
    | Nanti kalau sudah hosting (production), Mas tinggal ganti di .env menjadi 'smtp'.
    |
    */

    'default' => env('MAIL_MAILER', 'log'),

    /* ... bagian mailers biarkan default ... */

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | Di sini kita ganti agar email yang keluar (meskipun cuma di log)
    | terlihat datang dari brand Mas Rofi'i.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'admin@albrkshoesclean.com'),
        'name' => env('MAIL_FROM_NAME', 'ALBRK SHOESCLEAN Support'),
    ],

];
