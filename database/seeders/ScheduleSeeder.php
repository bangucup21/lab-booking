<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID referensi
        $slots     = DB::table('time_slots')->where('is_break', false)->where('is_after_hours', false)->orderBy('slot_order')->pluck('id')->toArray();
        $teachers  = DB::table('teachers')->pluck('name', 'id')->toArray();
        $teacherIds = array_keys($teachers);
        $classes   = DB::table('classes')->pluck('name', 'id')->toArray();
        $classIds  = array_keys($classes);
        $adminId   = DB::table('users')->where('username', 'admin')->value('id');

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $subjects = [
            'Administrasi Jaringan',
            'Pemrograman Web',
            'Basis Data',
            'Sistem Operasi',
            'Keamanan Jaringan',
            'Pemrograman Berorientasi Objek',
            'Desain Grafis',
            'Multimedia',
        ];

        $now   = now();
        $start = '2025-07-14'; // awal semester ganjil
        $end   = '2026-01-10'; // akhir semester ganjil

        // Buat jadwal reguler: 2 lab × 6 hari × 3 slot per hari
        foreach ([1, 2] as $resourceId) {
            $slotIndex    = 0;
            $teacherIndex = 0;
            $classIndex   = 0;
            $subjectIndex = 0;

            foreach ($days as $day) {
                // 3 slot per hari per lab
                for ($i = 0; $i < 3; $i++) {
                    $slotId    = $slots[$slotIndex % count($slots)];
                    $teacherId = $teacherIds[$teacherIndex % count($teacherIds)];
                    $classId   = $classIds[$classIndex % count($classIds)];
                    $subject   = $subjects[$subjectIndex % count($subjects)];

                    DB::table('schedules')->insert([
                        'teacher_id'    => $teacherId,
                        'resource_id'   => $resourceId,
                        'time_slot_id'  => $slotId,
                        'class_id'      => $classId,
                        'user_id'       => $adminId,
                        'day_of_week'   => $day,
                        'teacher_name'  => $teachers[$teacherId],
                        'subject_name'  => $subject,
                        'notes'         => null,
                        'academic_year' => '2025/2026',
                        'semester'      => 'ganjil',
                        'start_date'    => $start,
                        'end_date'      => $end,
                        'status'        => 'active',
                        'metadata'      => null,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ]);

                    $slotIndex++;
                    $teacherIndex++;
                    $classIndex++;
                    $subjectIndex++;
                }
            }
        }
    }
}
