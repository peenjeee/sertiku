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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('file_path');                  // Path to original template file
            $table->string('thumbnail_path')->nullable(); // Path to preview thumbnail
            $table->enum('orientation', ['landscape', 'portrait'])->default('landscape');
            $table->integer('width')->nullable();     // Template width in pixels
            $table->integer('height')->nullable();    // Template height in pixels
            $table->json('placeholders')->nullable(); // JSON config for text/image placeholders
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
