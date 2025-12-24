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
            if (!Schema::hasColumn($tableName, 'ipfs_cid')) {
                $table->string('ipfs_cid')->nullable();
            }
            if (!Schema::hasColumn($tableName, 'ipfs_metadata_cid')) {
                $table->string('ipfs_metadata_cid')->nullable();
            }
            if (!Schema::hasColumn($tableName, 'ipfs_url')) {
                $table->string('ipfs_url')->nullable();
            }
            if (!Schema::hasColumn($tableName, 'ipfs_uploaded_at')) {
                $table->timestamp('ipfs_uploaded_at')->nullable();
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
            $table->dropColumn(['ipfs_cid', 'ipfs_metadata_cid', 'ipfs_url', 'ipfs_uploaded_at']);
        });
    }
};
