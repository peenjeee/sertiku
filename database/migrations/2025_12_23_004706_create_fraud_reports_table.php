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
        Schema::create('fraud_reports', function (Blueprint $table) {
            $table->id();
            $table->string('reported_hash'); // Hash yang dilaporkan
            $table->string('reporter_name');
            $table->string('reporter_email');
            $table->string('reporter_phone')->nullable();
            $table->text('description');                 // Deskripsi pemalsuan
            $table->string('evidence_path')->nullable(); // Path bukti
            $table->enum('status', ['pending', 'investigating', 'resolved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fraud_reports');
    }
};
