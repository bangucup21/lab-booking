<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── resources: composite index untuk query WHERE status = 'active' ORDER BY name ──
        Schema::table('resources', function (Blueprint $table) {
            $indexes = collect(DB::select("SHOW INDEX FROM resources WHERE Key_name = 'idx_status_name'"))
                ->pluck('Key_name')->toArray();

            if (!in_array('idx_status_name', $indexes)) {
                $table->index(['status', 'name'], 'idx_status_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropIndex('idx_status_name');
        });
    }
};