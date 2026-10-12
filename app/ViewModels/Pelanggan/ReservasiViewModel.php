<?php

namespace App\ViewModels\Pelanggan;

use App\Services\Contracts\LayananServiceInterface;

class ReservasiViewModel
{
    public function __construct(private LayananServiceInterface $layananService) {}

    /**
     * Get available services
     */
    public function getLayanans(): array
    {
        return $this->layananService->getActiveLayanan();
    }
}
