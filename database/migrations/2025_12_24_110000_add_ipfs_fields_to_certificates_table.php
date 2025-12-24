<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('ipfs_cid')->nullable()->after('blockchain_status');
            $table->string('ipfs_metadata_cid')->nullable()->after('ipfs_cid');
            $table->string('ipfs_url')->nullable()->after('ipfs_metadata_cid');
            $table->timestamp('ipfs_uploaded_at')->nullable()->after('ipfs_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn([
                'ipfs_cid',
                'ipfs_metadata_cid',
                'ipfs_url',
                'ipfs_uploaded_at',
            ]);
        });
    }
};
