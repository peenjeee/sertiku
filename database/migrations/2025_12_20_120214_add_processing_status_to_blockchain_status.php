<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change ENUM to include 'processing' and 'disabled' status
        DB::statement("ALTER TABLE certificates MODIFY COLUMN blockchain_status ENUM('pending', 'processing', 'confirmed', 'failed', 'disabled') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original ENUM
        DB::statement("ALTER TABLE certificates MODIFY COLUMN blockchain_status ENUM('pending', 'confirmed', 'failed') NULL");
    }
};
