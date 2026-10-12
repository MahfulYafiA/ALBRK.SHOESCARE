<?php

namespace App\ViewModels\Shared;

use App\Models\User;
use Illuminate\Support\Facades\Schema;

class NotificationBadgeViewModel
{
    public function getUnreadCount(?User $user): int
    {
        if (! $user || ! Schema::hasTable('notifications')) {
            return 0;
        }

        return $user->unreadNotifications()->count();
    }
}
