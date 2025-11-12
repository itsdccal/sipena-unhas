<?php

namespace Database\Seeders;

use App\Models\ActivityTemplate;
use Illuminate\Database\Seeder;

class ActivityTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            ['activity_name' => 'Orientasi Fakultas/RSWS Kuliah MDUP/KIK', 'description' => 'Orientasi untuk mahasiswa baru fakultas'],
            ['activity_name' => 'Etika medik/kolegal (2 SKS x 16 TM)', 'description' => 'Mata kuliah etika medis dan aspek hukum'],
            ['activity_name' => 'Metode Penelitian (2 SKS x 16 TM)', 'description' => 'Mata kuliah metodologi penelitian'],
            ['activity_name' => 'Biostatistik & Komputer Statistik (2 SKS x 16 TM)', 'description' => 'Statistik dan aplikasi komputer'],
            ['activity_name' => 'Epidemiologi Klinik & Kedokteran Berbasis Bukti (2 SKS x 16 TM)', 'description' => 'Epidemiologi dan evidence-based medicine'],
            ['activity_name' => 'Biologi Molekuler (2 SKS x 16 TM)', 'description' => 'Mata kuliah biologi molekuler'],
            ['activity_name' => 'Dasar-dasar Fisika Radiasi (Fisika Medik,Radiologi Poteksi Padasi) (2 SKS x 16 TM)', 'description' => 'Fisika radiasi dan proteksi'],
            ['activity_name' => 'Bioinformatika (2 SKS x 16 TM)', 'description' => 'Informatika dalam bidang biologi'],
            ['activity_name' => 'Bahasa Inggris Medis/Klinis (2 SKS x 16 TM)', 'description' => 'Bahasa Inggris untuk medis'],
            ['activity_name' => 'Farmakoterapi Klinik (2 SKS x 16 TM)', 'description' => 'Terapi obat dalam praktek klinik'],
            ['activity_name' => 'Kultur Jaringan (2 SKS x 16 TM)', 'description' => 'Teknik kultur jaringan'],
            ['activity_name' => 'Teknologi Informasi Kedokteran (2 SKS x 16 TM)', 'description' => 'IT dalam bidang kedokteran'],
            ['activity_name' => 'Pemeriksaan Fisik dan Diagnostik Radiologi (2 SKS x 16 TM)', 'description' => 'Pemeriksaan fisik dan diagnostik'],
            ['activity_name' => 'Radiografi Umum (2 SKS x 16 TM)', 'description' => 'Teknik radiografi dasar'],
            ['activity_name' => 'Radiografi Gigi & Maksilofasial (2 SKS x 16 TM)', 'description' => 'Radiografi untuk gigi'],
            ['activity_name' => 'Pemeriksaan MRI (2 SKS x 16 TM)', 'description' => 'Magnetic Resonance Imaging'],
            ['activity_name' => 'Pemeriksaan CT-Scan (2 SKS x 16 TM)', 'description' => 'Computed Tomography Scan'],
            ['activity_name' => 'Ultrasonografi (2 SKS x 16 TM)', 'description' => 'Teknik ultrasonografi'],
            ['activity_name' => 'Praktikum Radiografi Umum', 'description' => 'Praktikum radiografi'],
            ['activity_name' => 'Praktikum Radiografi Gigi & Maksilofasial', 'description' => 'Praktikum radiografi gigi'],
            ['activity_name' => 'Praktikum MRI', 'description' => 'Praktikum MRI'],
            ['activity_name' => 'Praktikum CT-Scan', 'description' => 'Praktikum CT-Scan'],
            ['activity_name' => 'Praktikum USG', 'description' => 'Praktikum ultrasonografi'],
            ['activity_name' => 'Ujian Semester', 'description' => 'Ujian akhir semester'],
            ['activity_name' => 'Ujian Praktikum', 'description' => 'Ujian praktikum'],
            ['activity_name' => 'Penelitian/Thesis', 'description' => 'Penelitian untuk tesis'],
            ['activity_name' => 'Seminar Proposal', 'description' => 'Presentasi proposal penelitian'],
            ['activity_name' => 'Seminar Hasil', 'description' => 'Presentasi hasil penelitian'],
            ['activity_name' => 'Ujian Akhir/Thesis', 'description' => 'Ujian akhir atau sidang tesis'],
        ];

        foreach ($templates as $template) {
            ActivityTemplate::create([
                'activity_name' => $template['activity_name'],
                'description' => $template['description'],
                'is_active' => true,
            ]);
        }

        $this->command->info('Activity templates seeded successfully!');
    }
}
