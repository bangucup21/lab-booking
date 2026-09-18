<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration awal — membuat semua tabel dasar yang dibutuhkan
 * untuk fitur booking, jadwal, rekap, user, dan setting.
 *
 * Urutan: organizations → resources → classes → users → time_slots
 *       → schedules → bookings → notifications → activity_logs
 *       → holidays → user_sessions
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── organizations ─────────────────────────────────────────
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 50)->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->text('address')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email')->nullable();
            $table->text('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // ── resources (lab) ───────────────────────────────────────
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 50);
            $table->foreignId('parent_id')->nullable()->constrained('resources')->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->string('building', 100)->nullable();
            $table->integer('floor')->nullable();
            $table->string('room_number', 50)->nullable();
            $table->integer('capacity')->nullable();
            $table->string('status', 50)->default('active');
            $table->text('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ── classes ───────────────────────────────────────────────
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->char('pin', 6)->nullable();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->string('grade_level', 50)->nullable();
            $table->string('major', 100)->nullable();
            $table->integer('student_count')->default(0);
            $table->string('academic_year', 20)->nullable();
            $table->string('semester', 10)->nullable();
            $table->text('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // ── users ─────────────────────────────────────────────────
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('full_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role', 50)->default('operator');
            $table->string('phone', 50)->nullable();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->text('metadata')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // ── time_slots ────────────────────────────────────────────
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('day_of_week', 20)->nullable();
            $table->integer('slot_order')->default(0);
            $table->boolean('is_break')->default(false);
            $table->boolean('is_after_hours')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── schedules ─────────────────────────────────────────────
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
            $table->foreignId('time_slot_id')->constrained('time_slots')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('day_of_week', 20);
            $table->string('teacher_name');
            $table->string('subject_name')->nullable();
            $table->text('notes')->nullable();
            $table->string('academic_year', 20)->nullable();
            $table->string('semester', 10)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status', 50)->default('active');
            $table->text('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ── bookings ──────────────────────────────────────────────
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->char('session_id', 36)->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
            $table->foreignId('time_slot_id')->constrained('time_slots')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('organization_id');
            $table->date('booking_date');
            $table->string('teacher_name');
            $table->string('teacher_phone', 50);
            $table->string('class_name')->nullable();
            $table->string('subject_name')->nullable();
            $table->string('title', 500);
            $table->text('description')->nullable();
            $table->integer('participant_count')->default(0);
            $table->string('status', 50)->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ── notifications ─────────────────────────────────────────
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type', 50);
            $table->string('title', 500);
            $table->text('message')->nullable();
            $table->string('action_url', 500)->nullable();
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('priority', 50)->default('normal');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->text('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // ── activity_logs ─────────────────────────────────────────
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable();
            $table->string('action', 100);
            $table->string('entity_type', 50)->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('table_name', 50)->nullable();
            $table->unsignedBigInteger('record_id')->nullable();
            $table->text('description')->nullable();
            $table->text('changes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // ── holidays ──────────────────────────────────────────────
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('name');
            $table->string('type', 50)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── user_sessions ─────────────────────────────────────────
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('token', 64)->unique();
            $table->text('payload')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
        Schema::dropIfExists('holidays');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('time_slots');
        Schema::dropIfExists('users');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('resources');
        Schema::dropIfExists('organizations');
    }
};
