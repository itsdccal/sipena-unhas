<?php

namespace Database\Seeders;

use App\Models\Degree;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $degrees = [
            ['degree_code' => 'S-1', 'degree_name' => 'Sarjana'],
            ['degree_code' => 'S-2', 'degree_name' => 'Magister'],
            ['degree_code' => 'S-3', 'degree_name' => 'Doktor'],
        ];

        foreach ($degrees as $degree) {
            Degree::create($degree);
        }
    }
}
