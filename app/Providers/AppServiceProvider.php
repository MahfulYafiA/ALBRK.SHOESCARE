<?php

namespace App\Providers;

use App\Repositories\AdminPaymentRepository;
use App\Repositories\Contracts\AdminPaymentRepositoryInterface;
use App\Repositories\Contracts\CustomerHistoryRepositoryInterface;
use App\Repositories\Contracts\CustomerPaymentRepositoryInterface;
use App\Repositories\Contracts\DetailReservasiRepositoryInterface;
use App\Repositories\Contracts\LaporanRepositoryInterface;
use App\Repositories\Contracts\LayananRepositoryInterface;
use App\Repositories\Contracts\PembayaranRepositoryInterface;
use App\Repositories\Contracts\ReservasiRepositoryInterface;
use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\CustomerHistoryRepository;
use App\Repositories\CustomerPaymentRepository;
use App\Repositories\DetailReservasiRepository;
use App\Repositories\LaporanRepository;
use App\Repositories\LayananRepository;
use App\Repositories\PembayaranRepository;
use App\Repositories\ReservasiRepository;
use App\Repositories\SettingRepository;
use App\Repositories\UserRepository;
use App\Services\Admin\AntreanService;
use App\Services\Admin\LaporanService;
use App\Services\Admin\PaymentReviewService;
use App\Services\Auth\AuthService;
use App\Services\Auth\PasswordResetService;
use App\Services\Contracts\AntreanServiceInterface;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\CustomerHistoryServiceInterface;
use App\Services\Contracts\CustomerPaymentServiceInterface;
use App\Services\Contracts\DashboardServiceInterface;
use App\Services\Contracts\LaporanServiceInterface;
use App\Services\Contracts\LayananServiceInterface;
use App\Services\Contracts\MidtransServiceInterface;
use App\Services\Contracts\NotificationServiceInterface;
use App\Services\Contracts\OfflineTransactionServiceInterface;
use App\Services\Contracts\PasswordResetServiceInterface;
use App\Services\Contracts\PaymentReviewServiceInterface;
use App\Services\Contracts\ProfileServiceInterface;
use App\Services\Contracts\ReservasiServiceInterface;
use App\Services\Contracts\SiteSettingServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\Dashboard\DashboardService;
use App\Services\Layanan\LayananService;
use App\Services\Payment\MidtransService;
use App\Services\Pelanggan\CustomerHistoryService;
use App\Services\Pelanggan\CustomerPaymentService;
use App\Services\Pelanggan\NotificationService;
use App\Services\Profile\ProfileService;
use App\Services\Reservasi\ReservasiService;
use App\Services\SiteSetting\SiteSettingService;
use App\Services\Transaction\OfflineTransactionService;
use App\Services\User\UserService;
use App\ViewModels\Shared\NotificationBadgeViewModel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Repositories
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ReservasiRepositoryInterface::class, ReservasiRepository::class);
        $this->app->bind(PembayaranRepositoryInterface::class, PembayaranRepository::class);
        $this->app->bind(LayananRepositoryInterface::class, LayananRepository::class);
        $this->app->bind(LaporanRepositoryInterface::class, LaporanRepository::class);
        $this->app->bind(DetailReservasiRepositoryInterface::class, DetailReservasiRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(AdminPaymentRepositoryInterface::class, AdminPaymentRepository::class);
        $this->app->bind(CustomerPaymentRepositoryInterface::class, CustomerPaymentRepository::class);
        $this->app->bind(CustomerHistoryRepositoryInterface::class, CustomerHistoryRepository::class);

        // Bind Services
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(ReservasiServiceInterface::class, ReservasiService::class);
        $this->app->bind(MidtransServiceInterface::class, MidtransService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(DashboardServiceInterface::class, DashboardService::class);
        $this->app->bind(LayananServiceInterface::class, LayananService::class);
        $this->app->bind(OfflineTransactionServiceInterface::class, OfflineTransactionService::class);
        $this->app->bind(ProfileServiceInterface::class, ProfileService::class);
        $this->app->bind(PasswordResetServiceInterface::class, PasswordResetService::class);
        $this->app->bind(SiteSettingServiceInterface::class, SiteSettingService::class);
        $this->app->bind(AntreanServiceInterface::class, AntreanService::class);
        $this->app->bind(PaymentReviewServiceInterface::class, PaymentReviewService::class);
        $this->app->bind(LaporanServiceInterface::class, LaporanService::class);
        $this->app->bind(CustomerPaymentServiceInterface::class, CustomerPaymentService::class);
        $this->app->bind(CustomerHistoryServiceInterface::class, CustomerHistoryService::class);
        $this->app->bind(NotificationServiceInterface::class, NotificationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        View::composer([
            'shared.admin-header-actions',
            'shared.pelanggan-header',
            'pelanggan.dasbor',
            'pelanggan.reservasi.create',
        ], function ($view): void {
            $view->with(
                'headerUnreadCount',
                $this->app->make(NotificationBadgeViewModel::class)->getUnreadCount(auth()->user()),
            );
        });
    }
}
