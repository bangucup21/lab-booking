<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // username: kolom login utama (menggantikan name untuk auth)
            $table->string('username')->nullable()->unique()->after('name');

            // password_hash: kolom password yang dipakai User::getAuthPassword()
            // (kolom 'password' dari base migration tetap ada untuk kompatibilitas)
            $table->string('password_hash')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn(['username', 'password_hash']);
        });
    }
};
