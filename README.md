# ALBRK Shoesclean

ALBRK Shoesclean adalah aplikasi web untuk mengelola reservasi dan operasional jasa cuci sepatu. Aplikasi dibuat dengan Laravel dan Blade untuk tiga role utama: pelanggan, admin, dan superadmin.

## Fungsi Aplikasi

### Pengunjung

- Melihat halaman utama dan katalog layanan.
- Membuat akun, masuk, dan menggunakan fitur pemulihan kata sandi.

### Pelanggan

- Memilih satu atau beberapa layanan aktif untuk satu reservasi.
- Menentukan jumlah pasang sepatu, metode penyerahan, dan metode pengembalian.
- Membayar secara tunai atau melalui payment gateway Midtrans.
- Melihat riwayat dan progres reservasi.
- Membatalkan reservasi yang masih menunggu atau sudah diterima.

Harga reservasi dihitung dengan menjumlahkan harga layanan yang dipilih, lalu mengalikannya dengan jumlah pasang sepatu. Setiap layanan disimpan sebagai baris detail tersendiri pada reservasi.

### Admin

- Melihat dashboard operasional dan antrean reservasi.
- Memperbarui status reservasi: menunggu, diterima, sedang diproses, selesai, atau dibatalkan.
- Menandai reservasi selesai sebagai sudah diambil. Reservasi selesai tetap terlihat di antrean sampai ditandai sudah diambil.
- Mengelola katalog layanan dan pengguna.
- Mencatat transaksi langsung melalui kasir offline.
- Melihat dan mengunduh laporan omzet.

### Superadmin

Superadmin memiliki dashboard, antrean, katalog layanan, kasir offline, dan laporan yang sama dengan admin, serta kontrol tambahan untuk mengelola akun dan role pengguna.

Semua pengguna yang masuk dapat mengelola profil. Superadmin juga dapat mengubah gambar banner dan bagian tentang pada halaman utama.

## Alur Reservasi

1. Pelanggan memilih satu atau beberapa layanan aktif dan jumlah sepatu.
2. Aplikasi menghitung total harga dan menyimpan satu reservasi dengan satu detail untuk setiap layanan.
3. Admin memproses reservasi melalui antrean. Pelanggan dapat melihat perubahan status di riwayat reservasi.
4. Setelah status selesai, reservasi tetap tampil sampai admin menandainya sudah diambil.
5. Pembayaran gateway diproses melalui Midtrans; pembayaran tunai dicatat sebagai pembayaran di kasir.

## Teknologi dan Arsitektur

- Laravel 12, PHP 8.2+, Blade, dan Tailwind CSS.
- MySQL untuk penyimpanan data.
- Vite dan npm untuk aset frontend.
- Midtrans untuk pembayaran online.
- Layer aplikasi menggunakan konsep MVM dengan Service dan Repository.

```text
Route -> Controller -> ViewModel -> Service -> Repository -> Model -> Database
                           |
                           v
                         Blade View
```

- `Controller` menerima request dan menentukan alur aplikasi.
- `ViewModel` menyiapkan data untuk halaman.
- `Service` menangani aturan bisnis, seperti perhitungan harga reservasi.
- `Repository` membaca dan mengubah data melalui model.
- `Model` merepresentasikan tabel dan relasi database.
- `resources/views` berisi halaman Blade untuk pengunjung, pelanggan, admin, dan superadmin.

## Struktur Folder

```text
albrkshoesclean/
|-- app/                      # Controller, model, request, service, repository, ViewModel
|-- bootstrap/                # Bootstrap Laravel
|-- config/                   # Konfigurasi aplikasi
|-- database/                 # Migration, seeder, dan factory
|-- public/                   # Document root dan aset publik
|-- resources/views/          # Tampilan Blade
|-- routes/                   # Route web dan console
|-- storage/                  # Log, cache, dan file runtime
|-- tests/                    # Test aplikasi
|-- artisan
|-- composer.json
`-- package.json
```

## Menjalankan di Lokal

Persyaratan: PHP 8.2 atau lebih baru, Composer, Node.js/npm, dan MySQL.

Jalankan dari direktori root project:

```powershell
composer install
Copy-Item .env.example .env # lakukan hanya jika .env belum tersedia
php artisan key:generate
npm install
```

Isi koneksi database dan kredensial layanan yang diperlukan di `.env`. Contoh koneksi MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=albrk_shoescare
DB_USERNAME=root
DB_PASSWORD=
```

Buat database tersebut, lalu jalankan migrasi dan data awal:

```powershell
php artisan migrate --seed
```

> `php artisan migrate:fresh --seed` menghapus semua tabel dan data sebelum membangunnya ulang.

Jalankan server Laravel dan Vite:

```powershell
composer run dev
```

Aplikasi tersedia di `http://127.0.0.1:8000`.

## Format Kode

Gunakan Laravel Pint untuk merapikan format file PHP:

```powershell
composer format
```

## Build Production

```powershell
npm install
npm run build
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Atur nilai production di `.env`, termasuk `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, koneksi database, dan kredensial Midtrans.

Untuk pembayaran Midtrans, isi `MIDTRANS_SERVER_KEY` dan `MIDTRANS_CLIENT_KEY`. Gunakan kredensial sandbox dengan `MIDTRANS_IS_PRODUCTION=false` saat uji coba; ubah ke `true` hanya setelah kredensial akun production siap. URL callback yang didaftarkan pada dashboard Midtrans adalah `https://<domain>/midtrans/callback`.

Pastikan document root web server mengarah ke folder `public`, bukan root proyek. Beri proses PHP hak tulis ke `storage` dan `bootstrap/cache`, aktifkan HTTPS, lalu jalankan `php artisan optimize` setelah `.env` production siap. Cache dan queue bawaan menggunakan `file` dan `sync`, sehingga tidak memerlukan tabel tambahan atau worker queue. Jika hosting memakai queue database, siapkan konfigurasi koneksi dan migrasi tabel queue terlebih dahulu.
