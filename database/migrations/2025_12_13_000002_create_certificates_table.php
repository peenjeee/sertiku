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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Issuer
            $table->foreignId('template_id')->nullable()->constrained()->onDelete('set null');

            // Recipient info
            $table->string('recipient_name');
            $table->string('recipient_email')->nullable();

            // Certificate details
            $table->string('course_name');
            $table->string('category')->nullable(); // Bootcamp, Workshop, Seminar, etc.
            $table->text('description')->nullable();
            $table->date('issue_date');
            $table->date('expire_date')->nullable();

            // Unique identifiers
            $table->string('certificate_number')->unique();
            $table->string('hash')->unique(); // For verification
            $table->string('qr_code_path')->nullable();

            // Generated files
            $table->string('pdf_path')->nullable();
            $table->string('image_path')->nullable();

            // Status
            $table->enum('status', ['active', 'revoked', 'expired'])->default('active');
            $table->timestamp('revoked_at')->nullable();
            $table->string('revoked_reason')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'issue_date']);
            $table->index('certificate_number');
            $table->index('hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
