<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            // Kelas X TKJ
            ['name' => 'X TKJ 1',   'grade_level' => 'X',   'major' => 'Teknik Komputer dan Jaringan', 'student_count' => 36],
            ['name' => 'X TKJ 2',   'grade_level' => 'X',   'major' => 'Teknik Komputer dan Jaringan', 'student_count' => 36],
            // Kelas XI TKJ
            ['name' => 'XI TKJ 1',  'grade_level' => 'XI',  'major' => 'Teknik Komputer dan Jaringan', 'student_count' => 36],
            ['name' => 'XI TKJ 2',  'grade_level' => 'XI',  'major' => 'Teknik Komputer dan Jaringan', 'student_count' => 34],
            // Kelas XII TKJ
            ['name' => 'XII TKJ 1', 'grade_level' => 'XII', 'major' => 'Teknik Komputer dan Jaringan', 'student_count' => 34],
            ['name' => 'XII TKJ 2', 'grade_level' => 'XII', 'major' => 'Teknik Komputer dan Jaringan', 'student_count' => 33],
            // Kelas X RPL
            ['name' => 'X RPL 1',   'grade_level' => 'X',   'major' => 'Rekayasa Perangkat Lunak',     'student_count' => 36],
            ['name' => 'X RPL 2',   'grade_level' => 'X',   'major' => 'Rekayasa Perangkat Lunak',     'student_count' => 36],
            // Kelas XI RPL
            ['name' => 'XI RPL 1',  'grade_level' => 'XI',  'major' => 'Rekayasa Perangkat Lunak',     'student_count' => 35],
            // Kelas XII RPL
            ['name' => 'XII RPL 1', 'grade_level' => 'XII', 'major' => 'Rekayasa Perangkat Lunak',     'student_count' => 33],
        ];

        foreach ($classes as $class) {
            DB::table('classes')->insert([
                'name'          => $class['name'],
                'pin'           => null,
                'organization_id' => 1,
                'grade_level'   => $class['grade_level'],
                'major'         => $class['major'],
                'student_count' => $class['student_count'],
                'academic_year' => '2025/2026',
                'semester'      => 'ganjil',
                'metadata'      => null,
                'is_active'     => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
