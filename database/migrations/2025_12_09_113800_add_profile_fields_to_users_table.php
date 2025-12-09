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
        Schema::table('users', function (Blueprint $table) {
            // Account type
            $table->enum('account_type', ['personal', 'institution'])->nullable()->after('avatar');
            
            // Personal fields
            $table->string('phone')->nullable()->after('account_type');
            $table->string('occupation')->nullable()->after('phone');
            $table->string('user_institution')->nullable()->after('occupation');
            
            // Institution fields
            $table->string('institution_name')->nullable()->after('user_institution');
            $table->string('institution_type')->nullable()->after('institution_name');
            $table->string('sector')->nullable()->after('institution_type');
            $table->string('website')->nullable()->after('sector');
            $table->text('description')->nullable()->after('website');
            
            // Address fields (for institution)
            $table->string('address_line')->nullable()->after('description');
            $table->string('city')->nullable()->after('address_line');
            $table->string('province')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('province');
            $table->string('country')->default('Indonesia')->after('postal_code');
            
            // Admin fields (for institution)
            $table->string('admin_name')->nullable()->after('country');
            $table->string('admin_phone')->nullable()->after('admin_name');
            $table->string('admin_position')->nullable()->after('admin_phone');
            
            // Wallet address
            $table->string('wallet_address')->nullable()->after('admin_position');
            
            // Profile completion status
            $table->boolean('profile_completed')->default(false)->after('wallet_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'account_type',
                'phone',
                'occupation',
                'user_institution',
                'institution_name',
                'institution_type',
                'sector',
                'website',
                'description',
                'address_line',
                'city',
                'province',
                'postal_code',
                'country',
                'admin_name',
                'admin_phone',
                'admin_position',
                'wallet_address',
                'profile_completed',
            ]);
        });
    }
};
