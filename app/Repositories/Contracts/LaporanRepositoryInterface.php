<?php

namespace App\Repositories\Contracts;

interface LaporanRepositoryInterface
{
    public function getReportData(array $filters): array;
}
