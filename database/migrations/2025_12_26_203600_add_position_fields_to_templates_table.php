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
        Schema::table('templates', function (Blueprint $table) {
            // Name position (percentage-based for responsiveness)
            $table->integer('name_position_x')->default(50)->after('placeholders'); // Center X
            $table->integer('name_position_y')->default(45)->after('name_position_x'); // Upper-center Y
            $table->integer('name_font_size')->default(52)->after('name_position_y');
            $table->string('name_font_color', 10)->default('#1a1a1a')->after('name_font_size');

            // QR code position
            $table->integer('qr_position_x')->default(90)->after('name_font_color'); // Bottom-right X
            $table->integer('qr_position_y')->default(85)->after('qr_position_x'); // Bottom-right Y
            $table->integer('qr_size')->default(80)->after('qr_position_y'); // QR size in pixels
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn([
                'name_position_x',
                'name_position_y',
                'name_font_size',
                'name_font_color',
                'qr_position_x',
                'qr_position_y',
                'qr_size',
            ]);
        });
    }
};
