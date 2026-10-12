<?php

namespace App\ViewModels\Admin;

use App\Repositories\Contracts\LayananRepositoryInterface;

class TransaksiViewModel
{
    public function __construct(private LayananRepositoryInterface $layananRepository) {}

    public function getCreateData(): array
    {
        return ['layanans' => $this->layananRepository->getActive()];
    }
}
