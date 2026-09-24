<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Find and drop the old unique index (name may vary — check first).
        $indexes = collect(DB::select("SHOW INDEX FROM menu_permissions"))
            ->where('Non_unique', 0)
            ->where('Key_name', '!=', 'PRIMARY')
            ->pluck('Key_name')
            ->unique();

        Schema::table('menu_permissions', function (Blueprint $table) use ($indexes) {
            foreach ($indexes as $indexName) {
                $table->dropUnique($indexName);
            }

            $table->unique(['module_key', 'role_name', 'department_id'], 'menu_permissions_scoped_unique');
        });
    }

    public function down(): void
    {
        Schema::table('menu_permissions', function (Blueprint $table) {
            $table->dropUnique('menu_permissions_scoped_unique');
            $table->unique(['module_key', 'role_name'], 'menu_permissions_unique');
        });
    }
};
