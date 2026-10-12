<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Layanan\StoreLayananRequest;
use App\Http\Requests\Layanan\UpdateLayananRequest;
use App\Services\Contracts\LayananServiceInterface;
use App\ViewModels\Shared\LayananViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SuperAdminLayananController extends Controller
{
    public function __construct(
        private LayananViewModel $layananViewModel,
        private LayananServiceInterface $layananService,
    ) {}

    /**
     * Show layanan list
     */
    public function index(): View
    {
        return view('superadmin.layanan', $this->layananViewModel->getPageData());
    }

    /**
     * Store new layanan
     */
    public function store(StoreLayananRequest $request): RedirectResponse
    {
        return $this->serviceResponse($this->layananService->createLayanan($request->validated()));
    }

    /**
     * Update layanan
     */
    public function update(UpdateLayananRequest $request, int $id): RedirectResponse
    {
        return $this->serviceResponse($this->layananService->updateLayanan($id, $request->validated()));
    }

    /**
     * Delete layanan
     */
    public function destroy(int $id): RedirectResponse
    {
        return $this->layananService->deleteLayanan($id)
            ? back()->with('success', 'Layanan berhasil dihapus.')
            : back()->with('error', 'Gagal menghapus layanan.');
    }

    private function serviceResponse(array $result): RedirectResponse
    {
        return $result['success']
            ? back()->with('success', $result['message'])
            : back()->with('error', $result['message'])->withInput();
    }
}
