<?php

namespace App\Services\Pelanggan;

use App\Models\User;
use App\Services\Contracts\NotificationServiceInterface;
use Illuminate\Support\Facades\Schema;

class NotificationService implements NotificationServiceInterface
{
    public function getIndexData(User $user, array $filters): array
    {
        abort_unless(Schema::hasTable('notifications'), 503, 'Notifikasi belum tersedia. Jalankan migrasi database.');

        [$role, $notificationRoute, $notificationIndexRoute] = $this->context($user);
        $unreadCount = $user->unreadNotifications()->count();
        $query = $user->notifications();

        if (($filters['filter'] ?? 'semua') === 'belum_dibaca') {
            $query->whereNull('read_at');
        } elseif (($filters['filter'] ?? 'semua') === 'reservasi') {
            $query->whereIn('data->category', ['reservasi', 'pesanan']);
        } elseif (($filters['filter'] ?? 'semua') !== 'semua') {
            $query->where('data->category', $filters['filter']);
        }
        if (! empty($filters['q'])) {
            $term = trim($filters['q']);
            $query->where(fn ($search) => $search->where('data->title', 'like', "%{$term}%")
                ->orWhere('data->message', 'like', "%{$term}%"));
        }

        $counts = ['semua' => $user->notifications()->count(), 'belum_dibaca' => $unreadCount];
        foreach (['pembayaran', 'informasi'] as $category) {
            $counts[$category] = $user->notifications()->where('data->category', $category)->count();
        }
        $counts['reservasi'] = $user->notifications()->whereIn('data->category', ['reservasi', 'pesanan'])->count();

        return [
            'notifications' => $query->latest()->paginate(10)->withQueryString(),
            'unreadCount' => $unreadCount,
            'counts' => $counts,
            'filters' => $filters,
            'role' => $role,
            'notificationRoute' => $notificationRoute,
            'notificationIndexRoute' => $notificationIndexRoute,
        ];
    }

    public function getShowData(User $user, string $notificationId): array
    {
        $item = $user->notifications()->whereKey($notificationId)->firstOrFail();
        if ($item->unread()) {
            $item->markAsRead();
        }

        [$role, $notificationRoute, $notificationIndexRoute] = $this->context($user);
        $data = $item->data;
        if ($role === 'pelanggan' && ! empty($data['reservation_id'])) {
            $route = match ($data['category'] ?? '') {
                'pembayaran' => 'pelanggan.pembayaran.show',
                'reservasi' => 'pelanggan.riwayat-reservasi.show',
                default => 'pelanggan.riwayat-reservasi.show',
            };

            return [
                'redirect_route' => $route,
                'redirect_parameters' => [$data['reservation_id']],
            ];
        }

        if (in_array($role, ['admin', 'superadmin'], true)
            && ($data['category'] ?? null) === 'reservasi'
            && ! empty($data['reservation_id'])) {
            return [
                'redirect_route' => 'admin.antrean.show',
                'redirect_parameters' => [$data['reservation_id']],
            ];
        }

        return compact('item', 'role', 'notificationRoute', 'notificationIndexRoute');
    }

    public function markRead(User $user, string $notificationId): void
    {
        $user->notifications()->whereKey($notificationId)->firstOrFail()->markAsRead();
    }

    public function markAllRead(User $user): void
    {
        $user->unreadNotifications()->update(['read_at' => now()]);
    }

    private function context(User $user): array
    {
        if ($user->isSuperAdmin()) {
            return ['superadmin', 'superadmin.notifikasi', 'superadmin.notifikasi.index'];
        }
        if ($user->isAdmin()) {
            return ['admin', 'admin.notifikasi', 'admin.notifikasi.index'];
        }

        return ['pelanggan', 'pelanggan.notifikasi', 'pelanggan.notifikasi'];
    }
}
