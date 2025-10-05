<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            [
                'code' => 'SKS-TM',
                'name' => 'SKS TM',
                'suggested_fields' => json_encode(['SKS', 'TM']),
                'description' => 'Satuan Kredit Semester - Tatap Muka (dikalikan)',
                'is_active' => true,
            ],
            [
                'code' => 'OJ',
                'name' => 'OJ',
                'suggested_fields' => json_encode(['Jam', 'Orang']),
                'description' => 'Orang Jam (dikalikan)',
                'is_active' => true,
            ],
            [
                'code' => 'OK',
                'name' => 'OK',
                'suggested_fields' => json_encode(['Orang', 'Kegiatan']),
                'description' => 'Orang Kegiatan (dikalikan)',
                'is_active' => true,
            ],
            [
                'code' => 'KEG',
                'name' => 'KEG',
                'suggested_fields' => json_encode([]),
                'description' => 'Kegiatan (langsung, tidak ada komponen)',
                'is_active' => true,
            ],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
