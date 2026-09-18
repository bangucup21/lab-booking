<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sunday_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('booking_date');
            $table->string('teacher_name');
            $table->string('teacher_phone', 50);
            $table->string('class_name')->nullable();
            $table->string('subject_name')->nullable();
            $table->string('title', 500);
            $table->text('description')->nullable();
            $table->integer('participant_count')->default(0);
            $table->string('status', 50)->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Prevent double booking per lab per hari Minggu
            $table->unique(['resource_id', 'booking_date'], 'uk_sunday_resource_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sunday_bookings');
    }
};