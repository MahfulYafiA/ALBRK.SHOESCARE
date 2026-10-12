<?php

namespace App\Services\Contracts;

use App\Models\User;

interface NotificationServiceInterface
{
    public function getIndexData(User $user, array $filters): array;

    public function getShowData(User $user, string $notificationId): array;

    public function markRead(User $user, string $notificationId): void;

    public function markAllRead(User $user): void;
}
