<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'study_program_id' => null,
            'name' => 'Admin',
            'nip' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => true,
        ]);

        for ($i = 1; $i <= 9; $i++) {
            User::create([
                'study_program_id' => rand(1, 3),
                'name' => 'Staff ' . $i,
                'nip' => '2000' . str_pad($i, 5, '0', STR_PAD_LEFT), 
                'password' => Hash::make('password'),
                'status' => true,
            ]);
        }
    }
}
