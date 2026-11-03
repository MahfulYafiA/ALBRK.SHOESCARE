<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AntreanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\ReservasiController as AdminReservasiController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MidtransCallbackController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Pelanggan\NotifikasiController;
use App\Http\Controllers\Pelanggan\PembayaranController;
use App\Http\Controllers\Pelanggan\ReservasiController;
use App\Http\Controllers\Pelanggan\RiwayatReservasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SuperAdminLaporanController;
use App\Http\Controllers\SuperAdmin\SuperAdminLayananController;
use App\Http\Controllers\SuperAdmin\SuperAdminUserController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// ==========================================
// 1. RUTE LANDING PAGE (Publik)
// ==========================================
Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/media/{path}', function (string $path) {
    abort_unless(Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('media.public');

// ==========================================
// 2. RUTE WEBHOOK MIDTRANS (Wajib di luar auth)
// ==========================================
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'callback'])->name('midtrans.callback');

// ==========================================
// 3. RUTE AUTENTIKASI
// ==========================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [LoginController::class, 'handleGoogleCallback']);

    // Register
    Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
    Route::post('/register', [LoginController::class, 'register']);

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'updatePassword'])->name('password.update');
});

// ==========================================
// 4. RUTE TERPROTEKSI
// ==========================================
Route::middleware(['auth', 'active'])->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Settings (Universal Banner Update)
    Route::middleware('role:admin,superadmin')->group(function () {
        Route::post('/update-hero-banner', [LayananController::class, 'updateHero'])->name('update.hero.universal');
        Route::post('/update-tentang-kami', [LayananController::class, 'updateTentang'])->name('update.tentang.universal');
    });

    // Profil Management
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/foto', [ProfileController::class, 'foto'])->name('foto');
        Route::patch('/update', [ProfileController::class, 'update'])->name('update');
        Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('updatePassword');
        Route::patch('/foto', [ProfileController::class, 'updateFoto'])->name('updateFoto');
        Route::delete('/foto', [ProfileController::class, 'hapusFoto'])->name('hapusFoto');
    });

    // Notification pages are separated by role and always scoped to the signed-in user's records.
    Route::middleware('role:pelanggan')->group(function () {
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('pelanggan.notifikasi');
        Route::get('/notifikasi/{notification}', [NotifikasiController::class, 'show'])->name('pelanggan.notifikasi.show');
        Route::patch('/notifikasi/{notification}/read', [NotifikasiController::class, 'markRead'])->name('pelanggan.notifikasi.read');
        Route::patch('/notifikasi/read-all', [NotifikasiController::class, 'markAllRead'])->name('pelanggan.notifikasi.read-all');
    });
    Route::middleware('role:admin')->prefix('admin/notifikasi')->name('admin.notifikasi.')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index'])->name('index');
        Route::post('/', [NotifikasiController::class, 'store'])->name('store');
        Route::get('/{notification}', [NotifikasiController::class, 'show'])->name('show');
        Route::patch('/{notification}/read', [NotifikasiController::class, 'markRead'])->name('read');
        Route::patch('/read-all', [NotifikasiController::class, 'markAllRead'])->name('read-all');
    });
    Route::middleware('role:superadmin')->prefix('superadmin/notifikasi')->name('superadmin.notifikasi.')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index'])->name('index');
        Route::post('/', [NotifikasiController::class, 'store'])->name('store');
        Route::get('/{notification}', [NotifikasiController::class, 'show'])->name('show');
        Route::patch('/{notification}/read', [NotifikasiController::class, 'markRead'])->name('read');
        Route::patch('/read-all', [NotifikasiController::class, 'markAllRead'])->name('read-all');
    });
    // Dashboard Redirect
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('pelanggan.dashboard');
    })->name('dashboard');

    // ==========================================
    // AREA PELANGGAN
    // ==========================================
    Route::middleware('role:pelanggan')->prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/riwayat', fn () => redirect()->route('pelanggan.riwayat-reservasi'))->name('riwayat');
        Route::get('/riwayat-reservasi', [RiwayatReservasiController::class, 'index'])->name('riwayat-reservasi');
        Route::get('/riwayat-reservasi/{id}', [RiwayatReservasiController::class, 'show'])
            ->whereNumber('id')->name('riwayat-reservasi.show');
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
        Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->whereNumber('id')->name('pembayaran.show');
    });

    Route::middleware('role:pelanggan')->prefix('reservasi')->name('reservasi.')->group(function () {
        Route::get('/baru', [ReservasiController::class, 'create'])->name('create');
        Route::post('/baru', [ReservasiController::class, 'store'])->name('store');
        Route::get('/riwayat', fn () => redirect()->route('pelanggan.riwayat-reservasi'))->name('riwayat');
        Route::get('/pembayaran/{id}', [ReservasiController::class, 'pembayaran'])->name('pembayaran');
        Route::post('/pilih-pengembalian/{id}', [ReservasiController::class, 'pilihPengembalian'])->name('pilih-pengembalian');
        Route::post('/cancel/{id}', [ReservasiController::class, 'cancel'])->name('cancel');
    });

    // Pelanggan Dashboard
    Route::get('/pelanggan/dashboard', [ReservasiController::class, 'dashboard'])
        ->middleware('role:pelanggan')->name('pelanggan.dashboard');

    // ==========================================
    // AREA ADMIN
    // ==========================================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

        // User Management
        Route::get('/users', [UserManagementController::class, 'index'])->name('users');
        Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');

        // Antrean Management
        Route::get('/antrean', [AntreanController::class, 'index'])->name('antrean');
        Route::get('/antrean/{id}', [AntreanController::class, 'show'])->whereNumber('id')->name('antrean.show');
        Route::post('/antrean/{id}/status', [AntreanController::class, 'updateStatus'])->whereNumber('id')->name('antrean.update');
        Route::post('/antrean/{id}/batal', [AntreanController::class, 'cancel'])->whereNumber('id')->name('antrean.cancel');

        // Reservation operations use the reservation records as their source of truth.
        Route::get('/reservasi', [AdminReservasiController::class, 'index'])->name('reservasi.index');
        Route::get('/reservasi/{id}', [AdminReservasiController::class, 'show'])->whereNumber('id')->name('reservasi.show');
        Route::post('/reservasi/{id}/status', [AdminReservasiController::class, 'updateStatus'])->name('reservasi.update');

        // Payment review is available to Admin only.
        Route::middleware('role:admin')->group(function () {
            Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
            Route::get('/pembayaran/{id}', [AdminPembayaranController::class, 'show'])->whereNumber('id')->name('pembayaran.show');
            Route::post('/pembayaran/{id}/verifikasi', [AdminPembayaranController::class, 'verify'])->whereNumber('id')->name('pembayaran.verify');
            Route::post('/pembayaran/{id}/tolak', [AdminPembayaranController::class, 'reject'])->whereNumber('id')->name('pembayaran.reject');
        });

        // Layanan Management
        Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
        Route::post('/layanan', [LayananController::class, 'store'])->name('layanan.store');
        Route::put('/layanan/{id}', [LayananController::class, 'update'])->name('layanan.update');
        Route::delete('/layanan/{id}', [LayananController::class, 'destroy'])->name('layanan.destroy');

        // Laporan bisnis berisi seluruh data transaksi dan hanya untuk Admin.
        Route::middleware('role:admin')->get('/laporan', [LaporanController::class, 'index'])->name('laporan');

        // Transaksi Offline
        Route::get('/kasir-offline', [TransaksiController::class, 'createOffline'])->name('transaksi.offline');
        Route::post('/kasir-offline', [TransaksiController::class, 'storeOffline'])->name('transaksi.store-offline');
    });

    // ==========================================
    // AREA SUPERADMIN
    // ==========================================
    Route::middleware('role:superadmin')->prefix('superadmin')->name('superadmin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'dashboard'])->name('dashboard');

        // Layanan management
        Route::get('/layanan', [SuperAdminLayananController::class, 'index'])->name('layanan.index');
        Route::post('/layanan', [SuperAdminLayananController::class, 'store'])->name('layanan.store');
        Route::put('/layanan/{id}', [SuperAdminLayananController::class, 'update'])->name('layanan.update');
        Route::delete('/layanan/{id}', [SuperAdminLayananController::class, 'destroy'])->name('layanan.destroy');

        // Business reports
        Route::get('/laporan', [SuperAdminLaporanController::class, 'index'])->name('laporan');
        Route::get('/reservasi/{id}', [AdminReservasiController::class, 'show'])->whereNumber('id')->name('reservasi.show');
        Route::get('/pembayaran/{id}', [AdminPembayaranController::class, 'show'])->whereNumber('id')->name('pembayaran.show');

        // User Management
        Route::get('/users', [SuperAdminUserController::class, 'index'])->name('users');
        Route::post('/users', [SuperAdminUserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [SuperAdminUserController::class, 'update'])->name('users.update');
        Route::post('/users/{id}/toggle', [SuperAdminUserController::class, 'toggle'])->name('users.toggle');
        Route::delete('/users/{id}', [SuperAdminUserController::class, 'destroy'])->name('users.destroy');

    });
});
