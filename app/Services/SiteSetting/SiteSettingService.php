<?php

namespace App\Services\SiteSetting;

use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Services\Contracts\SiteSettingServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SiteSettingService implements SiteSettingServiceInterface
{
    public function __construct(private SettingRepositoryInterface $settingRepository) {}

    public function imageUrl(string $key): ?string
    {
        $path = $this->settingRepository->value($key);

        return $path && Storage::disk('public')->exists($path)
            ? route('media.public', ['path' => $path])
            : null;
    }

    public function updateImage(string $key, string $prefix, UploadedFile $file): array
    {
        if (! $file->isValid()) {
            return ['success' => false, 'message' => 'File upload tidak valid.'];
        }

        if (! in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], true)) {
            return ['success' => false, 'message' => 'Format file harus JPG, PNG, GIF, atau WebP.'];
        }

        if ($file->getSize() > 2 * 1024 * 1024) {
            return ['success' => false, 'message' => 'Ukuran file maksimal 2MB.'];
        }

        $oldPath = $this->settingRepository->value($key);
        $path = $file->storeAs('images', $prefix.'_'.uniqid('', true).'.'.$file->extension(), 'public');

        $this->settingRepository->set($key, $path);

        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return ['success' => true];
    }
}
