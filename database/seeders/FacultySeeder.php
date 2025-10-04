<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculties = [
            ['faculty_code' => 'K', 'faculty_name' => 'Fakultas Kedokteran'],
            ['faculty_code' => 'D', 'faculty_name' => 'Fakultas Kedokteran Gigi'],
            ['faculty_code' => 'N', 'faculty_name' => 'Fakultas Keperawatan'],
            ['faculty_code' => 'F', 'faculty_name' => 'Fakultas Farmasi'],
            ['faculty_code' => 'T', 'faculty_name' => 'Fakultas Teknik'],
            ['faculty_code' => 'E', 'faculty_name' => 'Fakultas Ekonomi dan Bisnis'],
            ['faculty_code' => 'H', 'faculty_name' => 'Fakultas Hukum'],
            ['faculty_code' => 'S', 'faculty_name' => 'Fakultas Ilmu Sosial dan Ilmu Politik'],
            ['faculty_code' => 'P', 'faculty_name' => 'Fakultas Psikologi'],
            ['faculty_code' => 'M', 'faculty_name' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam'],
            ['faculty_code' => 'B', 'faculty_name' => 'Fakultas Ilmu Budaya'],
            ['faculty_code' => 'U', 'faculty_name' => 'Fakultas Ushuluddin'],
            ['faculty_code' => 'R', 'faculty_name' => 'Fakultas Pertanian'],
            ['faculty_code' => 'C', 'faculty_name' => 'Fakultas Peternakan'],
            ['faculty_code' => 'O', 'faculty_name' => 'Fakultas Perikanan dan Ilmu Kelautan'],
            ['faculty_code' => 'L', 'faculty_name' => 'Fakultas Kesehatan Masyarakat'],
            ['faculty_code' => 'I', 'faculty_name' => 'Fakultas Ilmu Komputer'],
            ['faculty_code' => 'Y', 'faculty_name' => 'Fakultas Ilmu Keolahragaan'],
            ['faculty_code' => 'Z', 'faculty_name' => 'Fakultas Desain'],
            ['faculty_code' => 'J', 'faculty_name' => 'Fakultas Kedokteran Hewan'],
        ];

        foreach ($faculties as $faculty) {
            Faculty::create($faculty);
        }
    }
}
