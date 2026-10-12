<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection Name
    |--------------------------------------------------------------------------
    |
    | Saya sarankan ganti dari 'database' ke 'sync'.
    | 'sync' berarti pekerjaan (seperti kirim email) langsung dijalankan saat itu juga.
    | Mas tidak perlu menjalankan perintah 'php artisan queue:work'.
    |
    */

    'default' => env('QUEUE_CONNECTION', 'sync'),

    /* ... bagian connections biarkan default ... */

    /*
    |--------------------------------------------------------------------------
    | Job Batching
    |--------------------------------------------------------------------------
    */

    'batching' => [
        'database' => env('DB_CONNECTION', 'mysql'), // Ubah dari sqlite ke mysql
        'table' => 'job_batches',
    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    */

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'mysql'), // Ubah dari sqlite ke mysql
        'table' => 'failed_jobs',
    ],

];
