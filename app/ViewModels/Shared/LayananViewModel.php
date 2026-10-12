<?php

namespace App\ViewModels\Shared;

use App\Repositories\Contracts\LayananRepositoryInterface;
use App\Services\Contracts\SiteSettingServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class LayananViewModel
{
    public function __construct(
        private LayananRepositoryInterface $layananRepository,
        private SiteSettingServiceInterface $siteSettingService,
    ) {}

    /**
     * Get all layanan
     */
    public function getAllLayanan(): Collection
    {
        return $this->layananRepository->getAll();
    }

    public function getPageData(): array
    {
        $banner = $this->siteSettingService->imageUrl('hero_image');
        $tentang = $this->siteSettingService->imageUrl('tentang_image');

        return [
            'layanans' => $this->getAllLayanan(),
            'bannerTersimpan' => $banner ?? '',
            'hasBanner' => $banner !== null,
            'tentangTersimpan' => $tentang ?? '',
            'hasTentang' => $tentang !== null,
        ];
    }
}
