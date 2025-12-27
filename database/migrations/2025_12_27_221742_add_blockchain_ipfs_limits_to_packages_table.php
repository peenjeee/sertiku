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
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('blockchain_limit')->default(0)->after('certificates_limit'); // 0 = unlimited
            $table->integer('ipfs_limit')->default(0)->after('blockchain_limit'); // 0 = unlimited
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['blockchain_limit', 'ipfs_limit']);
        });
    }
};
