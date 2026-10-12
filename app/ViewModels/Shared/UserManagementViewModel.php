<?php

namespace App\ViewModels\Shared;

use App\Repositories\Contracts\UserRepositoryInterface;

class UserManagementViewModel
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function getAdminPageData(): array
    {
        $customers = $this->userRepository->getPaginatedByRole(3, 10);

        return [
            'users' => $customers,
            'pelanggan' => $customers,
            'totalPelanggan' => $this->userRepository->countByRole(3),
        ];
    }

    public function getSuperAdminPageData(): array
    {
        $users = $this->userRepository->getPaginatedAll(10);

        return [
            'users' => $users,
            'admins' => $this->userRepository->getByRole(2),
            'pelanggans' => $this->userRepository->getByRole(3),
            'totalUsers' => $this->userRepository->countAll(),
            'totalAdmins' => $this->userRepository->countByRole(2),
            'totalCustomers' => $this->userRepository->countByRole(3),
        ];
    }
}
