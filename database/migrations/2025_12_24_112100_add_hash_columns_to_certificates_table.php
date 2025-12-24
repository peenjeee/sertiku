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
        // Support both table names (sertifikat for local, certificates for production)
        $tableName = Schema::hasTable('sertifikat') ? 'sertifikat' : 'certificates';

        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            // Certificate file hashes
            if (!Schema::hasColumn($tableName, 'certificate_sha256')) {
                $table->string('certificate_sha256', 64)->nullable();
            }
            if (!Schema::hasColumn($tableName, 'certificate_md5')) {
                $table->string('certificate_md5', 32)->nullable();
            }
            // QR code hashes
            if (!Schema::hasColumn($tableName, 'qr_sha256')) {
                $table->string('qr_sha256', 64)->nullable();
            }
            if (!Schema::hasColumn($tableName, 'qr_md5')) {
                $table->string('qr_md5', 32)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = Schema::hasTable('sertifikat') ? 'sertifikat' : 'certificates';

        Schema::table($tableName, function (Blueprint $table) {
            $table->dropColumn(['certificate_sha256', 'certificate_md5', 'qr_sha256', 'qr_md5']);
        });
    }
};
