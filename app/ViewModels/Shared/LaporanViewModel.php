<?php

namespace App\ViewModels\Shared;

use App\Services\Contracts\LaporanServiceInterface;

class LaporanViewModel
{
    public function __construct(private LaporanServiceInterface $laporanService) {}

    public function getReportData(array $filters): array
    {
        return $this->laporanService->getReportData($filters);
    }
}
