<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ms_user') || ! Schema::hasColumn('ms_user', 'id_role')) {
            return;
        }

        if (Schema::hasColumn('ms_user', 'role')) {
            DB::table('ms_user')->where('id_role', 1)->update(['role' => 'superadmin']);
            DB::table('ms_user')->where('id_role', 2)->update(['role' => 'admin']);
            DB::table('ms_user')->where('id_role', 3)->update(['role' => 'pelanggan']);
        }

        Schema::table('ms_user', function (Blueprint $table): void {
            $table->dropColumn('id_role');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('ms_user') || Schema::hasColumn('ms_user', 'id_role')) {
            return;
        }

        Schema::table('ms_user', function (Blueprint $table): void {
            $table->integer('id_role')->default(3)->after('password');
        });

        if (Schema::hasColumn('ms_user', 'role')) {
            DB::table('ms_user')->where('role', 'superadmin')->update(['id_role' => 1]);
            DB::table('ms_user')->where('role', 'admin')->update(['id_role' => 2]);
            DB::table('ms_user')->where('role', 'pelanggan')->update(['id_role' => 3]);
        }
    }
};
