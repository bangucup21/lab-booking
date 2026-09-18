<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $adminId  = DB::table('users')->where('username', 'admin')->value('id');
        $teachers = DB::table('teachers')->get()->keyBy('id');
        $slots    = DB::table('time_slots')
                        ->where('is_break', false)
                        ->where('is_after_hours', false)
                        ->orderBy('slot_order')
                        ->pluck('id')
                        ->toArray();

        $subjects = ['Administrasi Jaringan', 'Pemrograman Web', 'Basis Data', 'Sistem Operasi'];
        $classes  = ['X TKJ 1', 'XI TKJ 1', 'XII TKJ 1', 'X RPL 1', 'XI RPL 1'];

        $now = Carbon::now();

        // ── Booking approved (masa lalu) ─────────────────────────
        for ($i = 1; $i <= 10; $i++) {
            $teacher   = $teachers->random();
            $sessionId = (string) Str::uuid();
            $date      = Carbon::now()->subDays(rand(3, 30))->format('Y-m-d');
            $slotId    = $slots[array_rand($slots)];
            $resourceId = rand(1, 2);

            DB::table('bookings')->insert([
                'session_id'        => $sessionId,
                'teacher_id'        => $teacher->id,
                'resource_id'       => $resourceId,
                'time_slot_id'      => $slotId,
                'user_id'           => null,
                'organization_id'   => 1,
                'booking_date'      => $date,
                'teacher_name'      => $teacher->name,
                'teacher_phone'     => $teacher->phone,
                'class_name'        => $classes[array_rand($classes)],
                'subject_name'      => $subjects[array_rand($subjects)],
                'title'             => 'Praktikum ' . $subjects[array_rand($subjects)],
                'description'       => 'Kegiatan praktikum rutin semester ganjil.',
                'participant_count' => rand(28, 36),
                'status'            => 'approved',
                'approved_by'       => $adminId,
                'approved_at'       => $now->copy()->subDays(rand(1, 3)),
                'notes'             => null,
                'created_at'        => $now->copy()->subDays(rand(4, 35)),
                'updated_at'        => $now->copy()->subDays(rand(1, 3)),
            ]);
        }

        // ── Booking pending (masa depan dekat) ───────────────────
        for ($i = 1; $i <= 5; $i++) {
            $teacher    = $teachers->random();
            $sessionId  = (string) Str::uuid();
            $date       = Carbon::now()->addDays(rand(1, 14))->format('Y-m-d');
            $slotId     = $slots[array_rand($slots)];
            $resourceId = rand(1, 2);

            DB::table('bookings')->insert([
                'session_id'        => $sessionId,
                'teacher_id'        => $teacher->id,
                'resource_id'       => $resourceId,
                'time_slot_id'      => $slotId,
                'user_id'           => null,
                'organization_id'   => 1,
                'booking_date'      => $date,
                'teacher_name'      => $teacher->name,
                'teacher_phone'     => $teacher->phone,
                'class_name'        => $classes[array_rand($classes)],
                'subject_name'      => $subjects[array_rand($subjects)],
                'title'             => 'Praktikum ' . $subjects[array_rand($subjects)],
                'description'       => 'Permohonan booking lab untuk kegiatan praktikum.',
                'participant_count' => rand(28, 36),
                'status'            => 'pending',
                'approved_by'       => null,
                'approved_at'       => null,
                'notes'             => null,
                'created_at'        => $now->copy()->subDays(rand(0, 2)),
                'updated_at'        => $now->copy()->subDays(rand(0, 2)),
            ]);
        }

        // ── Booking rejected (masa lalu) ─────────────────────────
        for ($i = 1; $i <= 3; $i++) {
            $teacher    = $teachers->random();
            $sessionId  = (string) Str::uuid();
            $date       = Carbon::now()->subDays(rand(5, 20))->format('Y-m-d');
            $slotId     = $slots[array_rand($slots)];
            $resourceId = rand(1, 2);

            DB::table('bookings')->insert([
                'session_id'        => $sessionId,
                'teacher_id'        => $teacher->id,
                'resource_id'       => $resourceId,
                'time_slot_id'      => $slotId,
                'user_id'           => null,
                'organization_id'   => 1,
                'booking_date'      => $date,
                'teacher_name'      => $teacher->name,
                'teacher_phone'     => $teacher->phone,
                'class_name'        => $classes[array_rand($classes)],
                'subject_name'      => $subjects[array_rand($subjects)],
                'title'             => 'Praktikum ' . $subjects[array_rand($subjects)],
                'description'       => null,
                'participant_count' => rand(28, 36),
                'status'            => 'rejected',
                'approved_by'       => $adminId,
                'approved_at'       => $now->copy()->subDays(rand(2, 5)),
                'notes'             => 'Lab sedang digunakan untuk kegiatan lain.',
                'created_at'        => $now->copy()->subDays(rand(6, 21)),
                'updated_at'        => $now->copy()->subDays(rand(2, 5)),
            ]);
        }
    }
}
