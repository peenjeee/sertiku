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
        Schema::table('certificates', function (Blueprint $table) {
            // 1. Cek jika ipfs_url belum ada, maka buatkan dulu
            if (!Schema::hasColumn('certificates', 'ipfs_url')) {
                $table->string('ipfs_url')->nullable();
            }

            // 2. Baru tambahkan ipfs_status setelah ipfs_url
            $table->string('ipfs_status')->nullable()->after('ipfs_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            // Hapus kedua kolom jika migrasi di-rollback
            $table->dropColumn(['ipfs_url', 'ipfs_status']);
        });
    }
};
