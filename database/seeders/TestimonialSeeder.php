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
                'content' => '"SertiKu merevolusi cara kami menerbitkan sertifikat. Prosesnya sangat cepat dan efisien."',
                'avatar' => 'https://ui-avatars.com/api/?name=Panji+Ihsaudin+Fajri&background=2B7FFF&color=fff&bold=true&size=128',
                'initial' => 'P',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Rama Danadipa',
                'role' => 'Mahasiswa',
                'institution' => 'Universitas AMIKOM Yogyakarta',
                'content' => '"Platform yang sangat membantu dalam digitalisasi sertifikat. Sistem verifikasinya sangat canggih."',
                'avatar' => 'https://ui-avatars.com/api/?name=Rama+Danadipa&background=00B8DB&color=fff&bold=true&size=128',
                'initial' => 'R',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Nakada Alpha',
                'role' => 'Mahasiswa',
                'institution' => 'Universitas AMIKOM Yogyakarta',
                'content' => '"Mahasiswa seperti kami sangat terbantu dengan sertifikat digital yang bisa diverifikasi kapan saja."',
                'avatar' => 'https://ui-avatars.com/api/?name=Nakada+Alpha&background=EC4899&color=fff&bold=true&size=128',
                'initial' => 'N',
                'rating' => 5,
                'is_featured' => true,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::create($t);
        }
    }
}
