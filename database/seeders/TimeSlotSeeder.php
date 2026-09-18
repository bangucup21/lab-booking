<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeSlotSeeder extends Seeder
{
    public function run(): void
    {
        // Catatan: slot "Luar Jam Sekolah" (is_after_hours=true) disisipkan
        // secara otomatis oleh migration add_after_hours_slot_to_time_slots_table,
        // jadi tidak perlu di-seed di sini.

        $slots = [
            ['name' => 'Jam ke-1',    'start_time' => '07:00:00', 'end_time' => '07:45:00', 'slot_order' => 1,  'is_break' => false],
            ['name' => 'Jam ke-2',    'start_time' => '07:45:00', 'end_time' => '08:30:00', 'slot_order' => 2,  'is_break' => false],
            ['name' => 'Jam ke-3',    'start_time' => '08:30:00', 'end_time' => '09:15:00', 'slot_order' => 3,  'is_break' => false],
            ['name' => 'Istirahat 1', 'start_time' => '09:15:00', 'end_time' => '09:30:00', 'slot_order' => 4,  'is_break' => true],
            ['name' => 'Jam ke-4',    'start_time' => '09:30:00', 'end_time' => '10:15:00', 'slot_order' => 5,  'is_break' => false],
            ['name' => 'Jam ke-5',    'start_time' => '10:15:00', 'end_time' => '11:00:00', 'slot_order' => 6,  'is_break' => false],
            ['name' => 'Jam ke-6',    'start_time' => '11:00:00', 'end_time' => '11:45:00', 'slot_order' => 7,  'is_break' => false],
            ['name' => 'Istirahat 2', 'start_time' => '11:45:00', 'end_time' => '12:30:00', 'slot_order' => 8,  'is_break' => true],
            ['name' => 'Jam ke-7',    'start_time' => '12:30:00', 'end_time' => '13:00:00', 'slot_order' => 9,  'is_break' => false],
        ];

        foreach ($slots as $slot) {
            DB::table('time_slots')->insert([
                'name'           => $slot['name'],
                'start_time'     => $slot['start_time'],
                'end_time'       => $slot['end_time'],
                'day_of_week'    => null, // null = berlaku semua hari
                'slot_order'     => $slot['slot_order'],
                'is_break'       => $slot['is_break'],
                'is_after_hours' => false,
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
