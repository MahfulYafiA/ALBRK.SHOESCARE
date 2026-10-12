<?php

namespace App\Services\Contracts;

use Illuminate\Http\UploadedFile;

interface SiteSettingServiceInterface
{
    public function imageUrl(string $key): ?string;

    public function updateImage(string $key, string $prefix, UploadedFile $file): array;
}
