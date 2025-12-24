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
        Schema::table('sertifikat', function (Blueprint $table) {
            if (!Schema::hasColumn('sertifikat', 'ipfs_cid')) {
                $table->string('ipfs_cid')->nullable();
            }
            if (!Schema::hasColumn('sertifikat', 'ipfs_metadata_cid')) {
                $table->string('ipfs_metadata_cid')->nullable();
            }
            if (!Schema::hasColumn('sertifikat', 'ipfs_url')) {
                $table->string('ipfs_url')->nullable();
            }
            if (!Schema::hasColumn('sertifikat', 'ipfs_uploaded_at')) {
                $table->timestamp('ipfs_uploaded_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sertifikat', function (Blueprint $table) {
            $table->dropColumn(['ipfs_cid', 'ipfs_metadata_cid', 'ipfs_url', 'ipfs_uploaded_at']);
        });
    }
};
