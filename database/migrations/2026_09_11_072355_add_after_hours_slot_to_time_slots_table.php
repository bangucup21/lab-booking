<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Menambahkan slot booking "Luar Jam Sekolah" (13:00–17:00, Senin–Sabtu).
 *
 * Catatan desain (lihat percakapan implementasi):
 * - Tidak butuh tabel/endpoint baru. bookings.time_slot_id sudah generik,
 *   jadi cukup 1 baris baru di time_slots dan seluruh flow existing
 *   (ConflictCheckerService, BookingSubmissionService, ScheduleAvailabilityService,
 *   weekly-table.blade.php, schedule/index.blade.php) otomatis ikut menangani slot ini.
 * - Larangan booking hari Minggu untuk slot ini SUDAH otomatis ter-cover oleh guard
 *   `Carbon::parse($request->booking_date)->isSunday()` di ScheduleController::storeBooking(),
 *   dan kolom Minggu di grid dirender sebagai satu rowspan terpisah (lihat $sunRowspan),
 *   jadi slot ini tidak akan pernah tampil sebagai opsi di kolom Minggu.
 * - is_after_hours ditambahkan sebagai flag murni untuk keperluan UI/reporting
 *   (mis. styling beda di grid, atau filter laporan) — tidak dipakai di logic booking manapun.
 */
return new class extends Migration
{
    private const SLOT_NAME = 'Luar Jam Sekolah';

    public function up(): void
    {
        // is_after_hours column already exists from create_base_tables migration
        // Only insert the slot data

        // slot_order di-hardcode ke 10 karena slot reguler (Jam ke-1 s.d Jam ke-7 + 2 istirahat)
        // berjumlah 9 slot dengan order 1-9. Nilai ini aman selama jumlah slot reguler < 10.
        $nextOrder = 10;

        DB::table('time_slots')->insert([
            'name'           => self::SLOT_NAME,
            'start_time'     => '13:00:00',
            'end_time'       => '17:00:00',
            'day_of_week'    => null,
            'slot_order'     => $nextOrder,
            'is_break'       => false,
            'is_after_hours' => true,
            'is_active'      => true,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        Cache::forget('active_time_slots');
    }

    public function down(): void
    {
        DB::table('time_slots')->where('name', self::SLOT_NAME)->delete();

        Cache::forget('active_time_slots');
    }
};
