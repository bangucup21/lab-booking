<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('resources')->insert([
            [
                'id'              => 1,
                'name'            => 'Lab Komputer 7',
                'type'            => 'lab',
                'parent_id'       => null,
                'organization_id' => 1,
                'building'        => 'Gedung A',
                'floor'           => 2,
                'room_number'     => '207',
                'capacity'        => 36,
                'status'          => 'active',
                'metadata'        => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => 2,
                'name'            => 'Lab Komputer 8',
                'type'            => 'lab',
                'parent_id'       => null,
                'organization_id' => 1,
                'building'        => 'Gedung A',
                'floor'           => 2,
                'room_number'     => '208',
                'capacity'        => 36,
                'status'          => 'active',
                'metadata'        => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => 3,
                'name'            => 'Lab Komputer 9',
                'type'            => 'lab',
                'parent_id'       => null,
                'organization_id' => 1,
                'building'        => 'Gedung B',
                'floor'           => 1,
                'room_number'     => '101',
                'capacity'        => 30,
                'status'          => 'active',
                'metadata'        => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}
