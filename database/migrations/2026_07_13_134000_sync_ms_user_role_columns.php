<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ms_user') || ! Schema::hasColumn('ms_user', 'id_role') || ! Schema::hasColumn('ms_user', 'role')) {
            return;
        }

        DB::table('ms_user')->whereIn('role', ['superadmin', 'super_admin', 'super admin'])->update(['id_role' => 1]);
        DB::table('ms_user')->where('role', 'admin')->update(['id_role' => 2]);
        DB::table('ms_user')->whereIn('role', ['pelanggan', 'customer'])->update(['id_role' => 3]);

        DB::table('ms_user')->where('id_role', 1)->update(['role' => 'superadmin']);
        DB::table('ms_user')->where('id_role', 2)->update(['role' => 'admin']);
        DB::table('ms_user')->where('id_role', 3)->update(['role' => 'pelanggan']);
    }

    public function down(): void
    {
        // Data sync only; nothing to roll back safely.
    }
};
