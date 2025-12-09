<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'price' => 0,
                'price_label' => 'Gratis',
                'description' => 'Untuk individu dan organisasi kecil',
                'features' => [
                    'Hingga 67 sertifikat/bulan',
                    'Verifikasi QR Code',
                    'Dashboard dasar',
                    'Email support',
                ],
                'certificates_limit' => 67,
                'is_popular' => false,
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'price' => 67000,
                'price_label' => null,
                'description' => 'Untuk lembaga dan institusi menengah',
                'features' => [
                    'Hingga 6.700 sertifikat/bulan',
                    'Analytics lengkap',
                    'Priority support',
                    'API access',
                    'Bulk upload',
                    'White-label option',
                ],
                'certificates_limit' => 6700,
                'is_popular' => true,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price' => 0,
                'price_label' => 'Custom',
                'description' => 'Untuk universitas dan korporasi besar',
                'features' => [
                    'Unlimited sertifikat',
                    'Custom integration',
                    'Dedicated account manager',
                    '24/7 phone support',
                    'On-premise deployment',
                    'SLA guarantee',
                ],
                'certificates_limit' => 0, // 0 = unlimited
                'is_popular' => false,
            ],
        ];

        foreach ($packages as $package) {
            Package::updateOrCreate(
                ['slug' => $package['slug']],
                $package
            );
        }
    }
}
