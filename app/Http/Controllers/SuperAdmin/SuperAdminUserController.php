<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Services\Contracts\UserServiceInterface;
use App\ViewModels\Shared\UserManagementViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SuperAdminUserController extends Controller
{
    public function __construct(
        private UserManagementViewModel $userManagementViewModel,
        private UserServiceInterface $userService,
    ) {}

    /**
     * Show users list (including admins and superadmins)
     */
    public function index(): View
    {
        return view('superadmin.pengguna', $this->userManagementViewModel->getSuperAdminPageData());
    }

    /**
     * Store new admin/superadmin
     */
    public function store(StoreAdminRequest $request): RedirectResponse
    {
        return $this->resultResponse($this->userService->createUser($request->validated()));
    }

    /**
     * Update user
     */
    public function update(StoreAdminRequest $request, int $id): RedirectResponse
    {
        return $this->resultResponse($this->userService->updateUser($id, $request->validated()));
    }

    /**
     * Toggle user status (aktif/nonaktif)
     */
    public function toggle(int $id): RedirectResponse
    {
        return $this->resultResponse($this->userService->toggleUserStatus($id));
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
