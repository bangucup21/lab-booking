<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ── Identitas Sekolah ────────────────────────────────
            ['key' => 'site_name',       'value' => 'Lab Komputer SMKN 1 Contoh'],
            ['key' => 'site_logo',       'value' => null],
            ['key' => 'site_head_name',  'value' => 'Drs. Kepala Sekolah, M.Pd'],
            ['key' => 'site_address',    'value' => 'Jl. Pendidikan No. 1, Kota Contoh'],
            ['key' => 'site_phone',      'value' => '0215551001'],

            // ── Notifikasi WhatsApp ──────────────────────────────
            ['key' => 'wa_notify_booking', 'value' => '1'],
            ['key' => 'wa_notify_lab',     'value' => '1'],
            ['key' => 'wa_admin_number',   'value' => ''],

            // ── Booking ──────────────────────────────────────────
            ['key' => 'booking_open',     'value' => '1'],
            ['key' => 'booking_max_days', 'value' => '30'],

            // ── Lab Control / Sesi ───────────────────────────────
            ['key' => 'lab_session_auto', 'value' => '1'],
            ['key' => 'lab_token_grace',  'value' => '10'],

            // ── Laporan ──────────────────────────────────────────
            ['key' => 'report_footer',    'value' => 'Dokumen ini dicetak secara otomatis oleh sistem.'],
            ['key' => 'kop_name_size',    'value' => '20'],
            ['key' => 'kop_address_size', 'value' => '13'],
            ['key' => 'kop_phone_size',   'value' => '12'],
        ];

        $now = now();
        foreach ($settings as $setting) {
            DB::table('settings')->insert(array_merge($setting, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
