<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        $semesters = [
            ['semester_code' => 'SEM1', 'semester_name' => 'SEMESTER 1'],
            ['semester_code' => 'SEM2', 'semester_name' => 'SEMESTER 2'],
            ['semester_code' => 'SEM3', 'semester_name' => 'SEMESTER 3'],
            ['semester_code' => 'SEM4', 'semester_name' => 'SEMESTER 4'],
            ['semester_code' => 'SEM5', 'semester_name' => 'SEMESTER 5'],
            ['semester_code' => 'SEM6', 'semester_name' => 'SEMESTER 6'],
            ['semester_code' => 'SEM7', 'semester_name' => 'SEMESTER 7'],
        ];

        foreach ($semesters as $semester) {
            Semester::create($semester);
        }
    }
}
