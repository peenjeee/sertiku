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
                'name' => 'Normal',
                'slug' => 'starter',
                'price' => 0,
                'price_label' => 'Gratis',
                'description' => 'Untuk individu dan organisasi kecil',
                'features' => [
                    'Hingga 50 sertifikat/bulan',
                    '50 Blockchain/bulan',
                    '50 IPFS/bulan',
                    'Verifikasi QR Code',
                    'Dashboard dasar',
                    'Email support',
                ],
                'certificates_limit' => 50,
                'blockchain_limit' => 50,
                'ipfs_limit' => 50,
                'is_popular' => false,
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'price' => 599000,
                'price_label' => null,
                'description' => 'Untuk lembaga dan institusi menengah',
                'features' => [
                    'Hingga 5.000 sertifikat/bulan',
                    '5.000 Blockchain/bulan',
                    '5.000 IPFS/bulan',
                    'Semua fitur Normal',
                    'Buat Template Sertifikat',
                    'Bulk Upload',
                    'API access',
                    'Priority support',
                ],
                'certificates_limit' => 5000,
                'blockchain_limit' => 5000,
                'ipfs_limit' => 5000,
                'is_popular' => true,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price' => 1000000,
                'price_label' => null,
                'description' => 'Untuk universitas dan korporasi besar',
                'features' => [
                    'Hingga 10.000 sertifikat/bulan',
                    '10.000 Blockchain/bulan',
                    '10.000 IPFS/bulan',
                    'Semua fitur Professional',
                    'Custom integration',
                    'Dedicated account manager',
                    '24/7 phone support',
                    'SLA guarantee',
                ],
                'certificates_limit' => 10000,
                'blockchain_limit' => 10000,
                'ipfs_limit' => 10000,
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
