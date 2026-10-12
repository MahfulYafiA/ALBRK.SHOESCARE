<?php

namespace App\Repositories;

use App\Repositories\Contracts\SettingRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SettingRepository implements SettingRepositoryInterface
{
    public function value(string $key): ?string
    {
        if (! Schema::hasTable('ms_pengaturan')) {
            return null;
        }

        return DB::table('ms_pengaturan')->where('key', $key)->value('value');
    }

    public function set(string $key, string $value): void
    {
        DB::table('ms_pengaturan')->updateOrInsert(
            ['key' => $key],
            ['value' => $value, 'updated_at' => now()],
        );
    }
}
