<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        $semesters = [
            ['semester_code' => 'sem1', 'semester_name' => 'semester 1'],
            ['semester_code' => 'sem2', 'semester_name' => 'semester 2'],
            ['semester_code' => 'sem3', 'semester_name' => 'semester 3'],
            ['semester_code' => 'sem4', 'semester_name' => 'semester 4'],
            ['semester_code' => 'sem5', 'semester_name' => 'semester 5'],
            ['semester_code' => 'sem6', 'semester_name' => 'semester 6'],
            ['semester_code' => 'sem7', 'semester_name' => 'semester 7'],
        ];

        foreach ($semesters as $semester) {
            Semester::create($semester);
        }
    }
}
