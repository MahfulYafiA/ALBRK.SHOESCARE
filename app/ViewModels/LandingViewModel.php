<?php

namespace App\ViewModels;

use App\Repositories\Contracts\LayananRepositoryInterface;
use App\Services\Contracts\SiteSettingServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class LandingViewModel
{
    public function __construct(
        private LayananRepositoryInterface $layananRepository,
        private SiteSettingServiceInterface $siteSettingService,
    ) {}

    public function getPageData(): array
    {
        $layanans = Schema::hasTable('ms_layanan') ? $this->layananRepository->getActive() : collect();
        $fallbackImages = ['fast' => 'fastclean.png', 'deep' => 'deepclean.png', 'unyellow' => 'unyellowing.png'];

        foreach ($layanans as $layanan) {
            $name = mb_strtolower($layanan->nama_layanan);
            $kind = collect(array_keys($fallbackImages))->first(fn (string $key): bool => str_contains($name, $key));
            $fallback = $kind ? 'images/'.$fallbackImages[$kind] : null;
            $layanan->landing_icon = match ($kind) {
                'fast' => 'fa-bolt',
                'deep' => 'fa-brush',
                'unyellow' => 'fa-wand-magic-sparkles',
                default => 'fa-shoe-prints',
            };
            $layanan->landing_image = $layanan->gambar && Storage::disk('public')->exists($layanan->gambar)
                ? route('media.public', ['path' => $layanan->gambar])
                : ($fallback && file_exists(public_path($fallback)) ? asset($fallback) : null);
            $layanan->landing_price = number_format($layanan->harga, 0, ',', '.');
        }

        return [
            'layanans' => $layanans,
            'heroPath' => $this->siteSettingService->imageUrl('hero_image') ?? asset('images/Logo.jpeg'),
            'tentangPath' => $this->siteSettingService->imageUrl('tentang_image') ?? asset('images/fototentangkami.jpeg'),
        ];
    }
}
