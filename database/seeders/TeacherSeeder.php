<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            ['name' => 'Budi Santoso, S.Kom',     'phone' => '6281234567801', 'token' => 'GRU-001'],
            ['name' => 'Siti Rahayu, S.Pd',        'phone' => '6281234567802', 'token' => 'GRU-002'],
            ['name' => 'Ahmad Fauzi, S.T',         'phone' => '6281234567803', 'token' => 'GRU-003'],
            ['name' => 'Dewi Lestari, S.Kom',      'phone' => '6281234567804', 'token' => 'GRU-004'],
            ['name' => 'Hendra Gunawan, S.Pd',     'phone' => '6281234567805', 'token' => 'GRU-005'],
            ['name' => 'Rina Marlina, S.T',        'phone' => '6281234567806', 'token' => 'GRU-006'],
            ['name' => 'Wahyu Prasetyo, S.Kom',    'phone' => '6281234567807', 'token' => 'GRU-007'],
            ['name' => 'Nurul Hidayah, S.Pd',      'phone' => '6281234567808', 'token' => 'GRU-008'],
            ['name' => 'Agus Setiawan, S.T',       'phone' => '6281234567809', 'token' => 'GRU-009'],
            ['name' => 'Fitri Handayani, S.Kom',   'phone' => '6281234567810', 'token' => 'GRU-010'],
        ];

        foreach ($teachers as $teacher) {
            DB::table('teachers')->insert([
                'name'       => $teacher['name'],
                'phone'      => $teacher['phone'],
                'token'      => $teacher['token'],
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
