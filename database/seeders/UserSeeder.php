<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $users = [
            // ── Super Admin ──────────────────────────────────────
            [
                'name'            => 'superadmin',
                'username'        => 'superadmin',
                'full_name'       => 'Super Administrator',
                'email'           => 'superadmin@smkn1contoh.sch.id',
                'password'        => Hash::make('password'),
                'password_hash'   => Hash::make('password'),
                'role'            => 'super_admin',
                'phone'           => null,
                'organization_id' => 1,
                'is_active'       => true,
            ],
            // ── Admin ────────────────────────────────────────────
            [
                'name'            => 'admin',
                'username'        => 'admin',
                'full_name'       => 'Administrator Lab',
                'email'           => 'admin@smkn1contoh.sch.id',
                'password'        => Hash::make('password'),
                'password_hash'   => Hash::make('password'),
                'role'            => 'admin',
                'phone'           => null,
                'organization_id' => 1,
                'is_active'       => true,
            ],
            // ── Operator Lab 7 ───────────────────────────────────
            [
                'name'            => 'operator_lab7',
                'username'        => 'operator_lab7',
                'full_name'       => 'Operator Lab 7',
                'email'           => 'operator7@smkn1contoh.sch.id',
                'password'        => Hash::make('password'),
                'password_hash'   => Hash::make('password'),
                'role'            => 'operator',
                'phone'           => '6281234560001',
                'organization_id' => 1,
                'is_active'       => true,
            ],
            // ── Operator Lab 8 ───────────────────────────────────
            [
                'name'            => 'operator_lab8',
                'username'        => 'operator_lab8',
                'full_name'       => 'Operator Lab 8',
                'email'           => 'operator8@smkn1contoh.sch.id',
                'password'        => Hash::make('password'),
                'password_hash'   => Hash::make('password'),
                'role'            => 'operator',
                'phone'           => '6281234560002',
                'organization_id' => 1,
                'is_active'       => true,
            ],
            // ── Teknisi ──────────────────────────────────────────
            [
                'name'            => 'teknisi1',
                'username'        => 'teknisi1',
                'full_name'       => 'Teknisi Lab Komputer',
                'email'           => 'teknisi@smkn1contoh.sch.id',
                'password'        => Hash::make('password'),
                'password_hash'   => Hash::make('password'),
                'role'            => 'teknisi',
                'phone'           => '6281234560003',
                'organization_id' => 1,
                'is_active'       => true,
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert(array_merge($user, [
                'metadata'       => null,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]));
        }

        // ── resource_user pivot: assign operator & teknisi ke lab ──
        $operatorLab7Id = DB::table('users')->where('username', 'operator_lab7')->value('id');
        $operatorLab8Id = DB::table('users')->where('username', 'operator_lab8')->value('id');
        $teknisiId      = DB::table('users')->where('username', 'teknisi1')->value('id');

        DB::table('resource_user')->insert([
            ['resource_id' => 1, 'user_id' => $operatorLab7Id, 'created_at' => $now, 'updated_at' => $now],
            ['resource_id' => 2, 'user_id' => $operatorLab8Id, 'created_at' => $now, 'updated_at' => $now],
            ['resource_id' => 1, 'user_id' => $teknisiId,      'created_at' => $now, 'updated_at' => $now],
            ['resource_id' => 2, 'user_id' => $teknisiId,      'created_at' => $now, 'updated_at' => $now],
            ['resource_id' => 3, 'user_id' => $teknisiId,      'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
