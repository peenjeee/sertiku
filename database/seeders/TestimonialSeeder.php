<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Panji Ihsaudin Fajri',
                'role' => 'Mahasiswa',
                'institution' => 'Universitas AMIKOM Yogyakarta',
                'content' => '“SertiKu merevolusi cara kami menerbitkan sertifikat. Prosesnya sangat cepat dan efisien.”',
                'initial' => 'P',
                'rating' => 5
            ],
            [
                'name' => 'Rama Danadipa',
                'role' => 'Mahasiswa',
                'institution' => 'Universitas AMIKOM Yogyakarta',
                'content' => '“Platform yang sangat membantu dalam digitalisasi sertifikat. Sistem verifikasinya sangat canggih.”',
                'initial' => 'R',
                'rating' => 5
            ],
            [
                'name' => 'Nakada Alpha',
                'role' => 'Mahasiswa',
                'institution' => 'Universitas AMIKOM Yogyakarta',
                'content' => '“Mahasiswa seperti kami sangat terbantu dengan sertifikat digital yang bisa diverifikasi kapan saja.”',
                'initial' => 'N',
                'rating' => 5
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::create($t);
        }
    }
}
