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
            ],
            [
                'id' => 'DH51400250',
                'name' => 'SV Khoa IT 1',
                'email' => 'thducit@gmail.com',
                'password' => bcrypt('DH51400250'),
                'role_id' => 1,
                'faculty_id' => 1
            ],
            [
                'id' => 'DH51400251',
                'name' => 'SV Khoa IT 2',
                'email' => 'thducit1@gmail.com',
                'password' => bcrypt('DH51400251'),
                'role_id' => 1,
                'faculty_id' => 1
            ], [
                'id' => 'DH51400252',
                'name' => 'BCSL Khoa IT 3',
                'email' => 'thducit2@gmail.com',
                'password' => bcrypt('DH51400252'),
                'role_id' => 2,
                'faculty_id' => 1
            ],
            [
                'id' => 'DH51401681',
                'name' => 'SV Khoa QTKD 1',
                'email' => 'DH51401681@gmail.com',
                'password' => bcrypt('DH51401681'),
                'role_id' => 1,
                'faculty_id' => 2
            ],
            [
                'id' => 'DH51401682',
                'name' => 'SV Khoa QTKD 2',
                'email' => 'DH51401682@gmail.com',
                'password' => bcrypt('DH51401682'),
                'role_id' => 1,
                'faculty_id' => 2
            ],
            [
                'id' => 'DH51401683',
                'name' => 'BCSL Khoa QTKD 1',
                'email' => 'DH51401683@gmail.com',
                'password' => bcrypt('DH51401683'),
                'role_id' => 2,
                'faculty_id' => 2
            ],[
                'id' => 'DH51400777',
                'name' => 'SV Khoa CoKhi 1',
                'email' => 'DH51400777@gmail.com',
                'password' => bcrypt('CD51402222'),
                'role_id' => 1,
                'faculty_id' => 6
            ],[
                'id' => 'DH51400778',
                'name' => 'BCSL Khoa CoKhi 1',
                'email' => 'DH51400778@gmail.com',
                'password' => bcrypt('DH51400778'),
                'role_id' => 2,
                'faculty_id' => 6
            ]
        ]);
    }
}
