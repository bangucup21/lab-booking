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
        Schema::table('time_slots', function (Blueprint $table) {
            $table->boolean('is_after_hours')->default(false)->after('is_break');
        });

        // slot_order dihitung dinamis (max existing + 1) supaya slot ini selalu
        // urut paling akhir di grid, apapun jumlah/urutan slot reguler saat ini.
        $nextOrder = (int) (DB::table('time_slots')->max('slot_order') ?? 0) + 1;

        DB::table('time_slots')->insert([
            'name'           => self::SLOT_NAME,
            'start_time'     => '13:00:00',
            'end_time'       => '17:00:00',
            'day_of_week'    => null, // null = berlaku semua hari; Minggu sudah diblokir di level controller
            'slot_order'     => $nextOrder,
            'is_break'       => false,
            'is_after_hours' => true,
            'is_active'      => true,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // getActiveTimeSlots() di-cache 1 jam (ScheduleQueryService) — invalidate
        // supaya slot baru langsung muncul tanpa nunggu TTL habis.
        Cache::forget('active_time_slots');
    }

    public function down(): void
    {
        DB::table('time_slots')->where('name', self::SLOT_NAME)->delete();

        Schema::table('time_slots', function (Blueprint $table) {
            $table->dropColumn('is_after_hours');
        });

        Cache::forget('active_time_slots');
    }
};