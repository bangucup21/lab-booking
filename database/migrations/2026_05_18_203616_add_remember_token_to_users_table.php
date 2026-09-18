<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // remember_token column already exists from create_base_tables migration
        // Nothing to do here
    }

    public function down(): void
    {
        // Nothing to undo
    }
};
