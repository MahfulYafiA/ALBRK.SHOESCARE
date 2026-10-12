<?php

namespace App\Services\Admin;

use App\Repositories\Contracts\LaporanRepositoryInterface;
use App\Services\Contracts\LaporanServiceInterface;

class LaporanService implements LaporanServiceInterface
{
    public function __construct(private LaporanRepositoryInterface $laporanRepository) {}

    public function getReportData(array $filters): array
    {
        return $this->laporanRepository->getReportData($filters);
    }
}
