<?php

use Illuminate\Database\Seeder;

class Role extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'Sinh viên'],
            ['name' => 'Ban các sự lớp'],
            ['name' => 'Cố vấn học tập'],
            ['name' => 'Ban chủ nhiệm khoa'],
            ['name' => 'Phòng CTSV'],
            ['name' => 'ADMIN']
        ]);
    }
}
