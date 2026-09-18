<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('schedules', function (Blueprint $table) {
            // teacher_id column already exists from create_base_tables migration
            // Only add the foreign key constraint here
            $table->foreign('teacher_id')->references('id')->on('teachers')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
        });
    }
};
