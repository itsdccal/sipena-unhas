<?php

namespace Database\Seeders;

use App\Models\StudyProgram;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            ['faculty_id' => 1, 'degree_id' => 1, 'sp_code' => 'KED-001', 'sp_name' => 'Pendidikan Dokter'],
            ['faculty_id' => 1, 'degree_id' => 2, 'sp_code' => 'KED-002', 'sp_name' => 'Magister Kedokteran Klinik'],
            ['faculty_id' => 2, 'degree_id' => 1, 'sp_code' => 'KDG-001', 'sp_name' => 'Pendidikan Dokter Gigi'],
            ['faculty_id' => 2, 'degree_id' => 2, 'sp_code' => 'KDG-002', 'sp_name' => 'Spesialis Ortodonti'],
            ['faculty_id' => 3, 'degree_id' => 1, 'sp_code' => 'KEP-001', 'sp_name' => 'Ilmu Keperawatan'],
            ['faculty_id' => 3, 'degree_id' => 2, 'sp_code' => 'KEP-002', 'sp_name' => 'Magister Keperawatan'],
            ['faculty_id' => 4, 'degree_id' => 1, 'sp_code' => 'FAR-001', 'sp_name' => 'Farmasi'],
            ['faculty_id' => 4, 'degree_id' => 2, 'sp_code' => 'FAR-002', 'sp_name' => 'Magister Ilmu Farmasi'],
            ['faculty_id' => 5, 'degree_id' => 1, 'sp_code' => 'TEK-001', 'sp_name' => 'Teknik Sipil'],
            ['faculty_id' => 5, 'degree_id' => 1, 'sp_code' => 'TEK-002', 'sp_name' => 'Teknik Mesin'],
            ['faculty_id' => 5, 'degree_id' => 1, 'sp_code' => 'TEK-003', 'sp_name' => 'Teknik Elektro'],
            ['faculty_id' => 5, 'degree_id' => 1, 'sp_code' => 'TEK-004', 'sp_name' => 'Teknik Arsitektur'],
            ['faculty_id' => 6, 'degree_id' => 1, 'sp_code' => 'EKB-001', 'sp_name' => 'Ilmu Ekonomi'],
            ['faculty_id' => 6, 'degree_id' => 1, 'sp_code' => 'EKB-002', 'sp_name' => 'Manajemen'],
            ['faculty_id' => 6, 'degree_id' => 1, 'sp_code' => 'EKB-003', 'sp_name' => 'Akuntansi'],
            ['faculty_id' => 7, 'degree_id' => 1, 'sp_code' => 'HUK-001', 'sp_name' => 'Ilmu Hukum'],
            ['faculty_id' => 8, 'degree_id' => 1, 'sp_code' => 'FIS-001', 'sp_name' => 'Ilmu Pemerintahan'],
            ['faculty_id' => 8, 'degree_id' => 1, 'sp_code' => 'FIS-002', 'sp_name' => 'Ilmu Komunikasi'],
            ['faculty_id' => 8, 'degree_id' => 1, 'sp_code' => 'FIS-003', 'sp_name' => 'Administrasi Publik'],
            ['faculty_id' => 9, 'degree_id' => 1, 'sp_code' => 'PSI-001', 'sp_name' => 'Psikologi'],
            ['faculty_id' => 10, 'degree_id' => 1, 'sp_code' => 'MIP-001', 'sp_name' => 'Matematika'],
            ['faculty_id' => 10, 'degree_id' => 1, 'sp_code' => 'MIP-002', 'sp_name' => 'Fisika'],
            ['faculty_id' => 10, 'degree_id' => 1, 'sp_code' => 'MIP-003', 'sp_name' => 'Kimia'],
            ['faculty_id' => 10, 'degree_id' => 1, 'sp_code' => 'MIP-004', 'sp_name' => 'Biologi'],
            ['faculty_id' => 11, 'degree_id' => 1, 'sp_code' => 'BUD-001', 'sp_name' => 'Sastra Inggris'],
            ['faculty_id' => 11, 'degree_id' => 1, 'sp_code' => 'BUD-002', 'sp_name' => 'Sastra Indonesia'],
            ['faculty_id' => 13, 'degree_id' => 1, 'sp_code' => 'PERT-001', 'sp_name' => 'Agroteknologi'],
            ['faculty_id' => 13, 'degree_id' => 1, 'sp_code' => 'PERT-002', 'sp_name' => 'Agribisnis'],
            ['faculty_id' => 14, 'degree_id' => 1, 'sp_code' => 'PETER-001', 'sp_name' => 'Ilmu Peternakan'],
            ['faculty_id' => 15, 'degree_id' => 1, 'sp_code' => 'PERI-001', 'sp_name' => 'Ilmu Perikanan'],
            ['faculty_id' => 16, 'degree_id' => 1, 'sp_code' => 'KES-001', 'sp_name' => 'Kesehatan Masyarakat'],
            ['faculty_id' => 17, 'degree_id' => 1, 'sp_code' => 'KOM-001', 'sp_name' => 'Ilmu Komputer'],
            ['faculty_id' => 17, 'degree_id' => 1, 'sp_code' => 'KOM-002', 'sp_name' => 'Sistem Informasi'],
            ['faculty_id' => 18, 'degree_id' => 1, 'sp_code' => 'OLA-001', 'sp_name' => 'Pendidikan Jasmani'],
            ['faculty_id' => 19, 'degree_id' => 1, 'sp_code' => 'DES-001', 'sp_name' => 'Desain Komunikasi Visual'],
            ['faculty_id' => 20, 'degree_id' => 1, 'sp_code' => 'HEW-001', 'sp_name' => 'Kedokteran Hewan'],
        ];

        foreach ($programs as $program) {
            StudyProgram::create($program);
        }
    }
}
