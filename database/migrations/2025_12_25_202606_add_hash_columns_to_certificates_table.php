<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration is skipped because the columns were already added 
        // in 2025_12_24_112100_add_hash_columns_to_certificates_table.php
        // Schema::table('certificates', function (Blueprint $table) {
        //     $table->string('certificate_sha256')->nullable()->after('ipfs_uploaded_at');
        //     $table->string('certificate_md5')->nullable()->after('certificate_sha256');
        //     $table->string('qr_sha256')->nullable()->after('certificate_md5');
        //     $table->string('qr_md5')->nullable()->after('qr_sha256');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            //
        });
    }
};
