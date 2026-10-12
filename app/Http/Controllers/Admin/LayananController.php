<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Layanan\StoreLayananRequest;
use App\Http\Requests\Layanan\UpdateLayananRequest;
use App\Services\Contracts\LayananServiceInterface;
use App\Services\Contracts\SiteSettingServiceInterface;
use App\ViewModels\Shared\LayananViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LayananController extends Controller
{
    public function __construct(
        private LayananViewModel $layananViewModel,
        private LayananServiceInterface $layananService,
        private SiteSettingServiceInterface $siteSettingService,
    ) {}

    /**
     * Show layanan list
     */
    public function index(): View
    {
        $data = $this->layananViewModel->getPageData();

        // Check if superadmin
        if (auth()->user()->isSuperAdmin()) {
            return view('superadmin.layanan', $data);
        }

        return view('admin.layanan', $data);
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

    /**
     * Update hero banner
     */
    public function updateHero(Request $request): RedirectResponse
    {
        if (! $request->hasFile('hero_image')) {
            return back()->with('error', 'Tidak ada file yang dipilih.');
        }

        $result = $this->siteSettingService->updateImage('hero_image', 'hero', $request->file('hero_image'));

        return $result['success']
            ? back()->with('success', 'Hero banner berhasil diperbarui!')
            : back()->with('error', $result['message']);
    }

    /**
     * Update tentang kami image
     */
    public function updateTentang(Request $request): RedirectResponse
    {
        if (! $request->hasFile('tentang_image')) {
            return back()->with('error', 'Tidak ada file yang dipilih.');
        }

        $result = $this->siteSettingService->updateImage('tentang_image', 'tentang', $request->file('tentang_image'));

        return $result['success']
            ? back()->with('success', 'Gambar tentang kami berhasil diperbarui!')
            : back()->with('error', $result['message']);
    }

    private function serviceResponse(array $result): RedirectResponse
    {
        return $result['success']
            ? back()->with('success', $result['message'])
            : back()->with('error', $result['message'])->withInput();
    }
}
