<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Contracts\UserServiceInterface;
use App\ViewModels\Shared\UserManagementViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function __construct(
        private UserManagementViewModel $userManagementViewModel,
        private UserServiceInterface $userService,
    ) {}

    /**
     * Show users page
     */
    public function index(): View
    {
        return view('admin.pengguna', $this->userManagementViewModel->getAdminPageData());
    }

    /**
     * Delete user
     */
    public function destroy(int $id): RedirectResponse
    {
        return $this->resultResponse($this->userService->deleteUser($id, (int) auth()->id()));
    }

    private function resultResponse(array $result): RedirectResponse
    {
        return $result['success']
            ? back()->with('success', $result['message'])
            : back()->with('error', $result['message'])->withInput();
    }
}
