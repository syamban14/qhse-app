<?php

namespace Database\Seeders;

use App\Models\AuditTemplate;
use Illuminate\Database\Seeder;

class AuditTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'title' => 'Inspeksi Kendaraan Pra-Perjalanan',
                'description' => 'Checklist inspeksi harian yang harus dilakukan oleh pengemudi sebelum memulai perjalanan.',
                'questions' => [
                    'Apakah kondisi ban (tekanan angin, keausan) dalam keadaan baik?',
                    'Apakah semua lampu (utama, rem, sein) berfungsi dengan baik?',
                    'Apakah level oli mesin, air radiator, dan minyak rem dalam batas normal?',
                    'Apakah klakson dan wiper berfungsi?',
                    'Apakah surat-surat kendaraan (STNK, KIR) lengkap dan valid?',
                    'Apakah perlengkapan darurat (dongkrak, kunci roda, P3K) tersedia?',
                ]
            ],
            [
                'title' => 'Audit Keselamatan Gudang Bulanan',
                'description' => 'Audit bulanan untuk memastikan area gudang mematuhi standar keselamatan.',
                'questions' => [
                    'Apakah jalur pejalan kaki dan jalur forklift bebas dari hambatan?',
                    'Apakah semua Alat Pemadam Api Ringan (APAR) berada di tempatnya dan tidak kedaluwarsa?',
                    'Apakah kondisi rak penyimpanan (racking) aman dan tidak ada kerusakan terlihat?',
                    'Apakah pencahayaan di semua area gudang memadai?',
                    'Apakah semua operator forklift menggunakan Alat Pelindung Diri (APD) yang sesuai?',
                    'Apakah area pengisian baterai forklift memiliki ventilasi yang baik dan bebas dari bahan mudah terbakar?',
                    'Apakah pintu darurat tidak terhalang dan mudah diakses?',
                ]
            ]
        ];

        foreach ($templates as $templateData) {
            $template = AuditTemplate::create([
                'title' => $templateData['title'],
                'description' => $templateData['description'],
            ]);

            foreach ($templateData['questions'] as $question) {
                $template->questions()->create(['question_text' => $question]);
            }
        }
    }
}
