<?php

namespace App\ViewModels\Pelanggan;

use App\Models\User;
use App\Services\Contracts\NotificationServiceInterface;

class NotifikasiViewModel
{
    public function __construct(private NotificationServiceInterface $notificationService) {}

    public function getIndexData(User $user, array $filters): array
    {
        return $this->notificationService->getIndexData($user, $filters);
    }

    public function getShowData(User $user, string $notificationId): array
    {
        return $this->notificationService->getShowData($user, $notificationId);
    }
}
