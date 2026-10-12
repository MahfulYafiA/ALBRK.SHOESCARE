<?php

namespace App\Services\Contracts;

interface LaporanServiceInterface
{
    public function getReportData(array $filters): array;
}
