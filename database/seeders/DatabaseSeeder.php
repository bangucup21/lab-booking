<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Urutan penting — sesuai dependency antar tabel:
     *   organizations → resources, classes, users
     *   resources + users → resource_user (di dalam UserSeeder)
     *   teachers + time_slots + classes + users → schedules
     *   teachers + time_slots + resources → bookings
     */
    public function run(): void
    {
        $this->call([
            OrganizationSeeder::class,
            ResourceSeeder::class,
            ClassSeeder::class,
            TimeSlotSeeder::class,
            TeacherSeeder::class,
            UserSeeder::class,     // juga seed resource_user pivot
            SettingSeeder::class,
            ScheduleSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
