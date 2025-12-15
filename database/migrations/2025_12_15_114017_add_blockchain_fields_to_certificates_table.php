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
            $table->boolean('blockchain_enabled')->default(false)->after('status');
            $table->string('blockchain_tx_hash')->nullable()->after('blockchain_enabled');
            $table->string('blockchain_hash')->nullable()->after('blockchain_tx_hash');
            $table->timestamp('blockchain_verified_at')->nullable()->after('blockchain_hash');
            $table->enum('blockchain_status', ['pending', 'confirmed', 'failed'])->nullable()->after('blockchain_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn([
                'blockchain_enabled',
                'blockchain_tx_hash',
                'blockchain_hash',
                'blockchain_verified_at',
                'blockchain_status',
            ]);
        });
    }
};
