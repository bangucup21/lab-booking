<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('organizations')->insert([
            [
                'id'         => 1,
                'name'       => 'SMK Negeri 1 Contoh',
                'type'       => 'school',
                'parent_id'  => null,
                'address'    => 'Jl. Pendidikan No. 1, Kota Contoh',
                'phone'      => '0215551001',
                'email'      => 'info@smkn1contoh.sch.id',
                'metadata'   => null,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 2,
                'name'       => 'Jurusan Teknik Komputer dan Jaringan',
                'type'       => 'department',
                'parent_id'  => 1,
                'address'    => null,
                'phone'      => null,
                'email'      => null,
                'metadata'   => null,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 3,
                'name'       => 'Jurusan Rekayasa Perangkat Lunak',
                'type'       => 'department',
                'parent_id'  => 1,
                'address'    => null,
                'phone'      => null,
                'email'      => null,
                'metadata'   => null,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
