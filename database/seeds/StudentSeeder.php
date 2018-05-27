<?php

use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 'DH99999999',
                'name' => 'ADMIN',
                'email' => 'DH99999999@gmail.com',
                'password' => bcrypt('DH99999999'),
                'role_id' => 6,
                'faculty_id' => null
            ], [
                'id' => 'DH88888888',
                'name' => 'SV Khoa QTKD 1',
                'email' => 'DH88888888@gmail.com',
                'password' => bcrypt('DH88888888'),
                'role_id' => 1,
                'faculty_id' => 2
            ], [
                'id' => 'DH88888887',
                'name' => 'SV Khoa QTKD 2',
                'email' => 'DH88888887@gmail.com',
                'password' => bcrypt('DH88888887'),
                'role_id' => 1,
                'faculty_id' => 2
            ], [
                'id' => 'DH88888884',
                'name' => 'BCSL Khoa QTKD 1',
                'email' => 'DH88888884@gmail.com',
                'password' => bcrypt('DH88888884'),
                'role_id' => 2,
                'faculty_id' => 2
            ], [
                'id' => 'DH88888886',
                'name' => 'SV Khoa CoKhi 1',
                'email' => 'DH88888886@gmail.com',
                'password' => bcrypt('DH88888886'),
                'role_id' => 1,
                'faculty_id' => 6
            ], [
                'id' => 'DH88888885',
                'name' => 'BCSL Khoa CoKhi 1',
                'email' => 'DH88888885@gmail.com',
                'password' => bcrypt('DH88888885'),
                'role_id' => 2,
                'faculty_id' => 6
            ]
        ]);
    }
}
