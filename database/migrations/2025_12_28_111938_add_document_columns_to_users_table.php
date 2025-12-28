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
        Schema::table('users', function (Blueprint $table) {
            $table->string('doc_npwp_path')->nullable()->after('wallet_address');
            $table->string('doc_akta_path')->nullable()->after('doc_npwp_path');
            $table->string('doc_siup_path')->nullable()->after('doc_akta_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['doc_npwp_path', 'doc_akta_path', 'doc_siup_path']);
        });
    }
};
