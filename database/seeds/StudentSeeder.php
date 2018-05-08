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
                'id' => 'DH51400243',
                'name' => 'SV Khoa QTKD 4',
                'email' => 'DH51400243@gmail.com',
                'password' => bcrypt('DH51400243'),
                'role_id' => 1,
                'faculty_id' => 1
            ],
            [
                'id' => 'DH51400999',
                'name' => 'SV Khoa QTKD 5',
                'email' => 'DH51400999@gmail.com',
                'password' => bcrypt('DH51400999'),
                'role_id' => 1,
                'faculty_id' => 2
            ],
            [
                'id' => 'DH51400838',
                'name' => 'SV Khoa QTKD 6',
                'email' => 'DH51400838@gmail.com',
                'password' => bcrypt('DH51400838'),
                'role_id' => 2,
                'faculty_id' => 2
            ],[
                'id' => 'DH51400777',
                'name' => 'SV Khoa CoKhi 1',
                'email' => 'DH51400777@gmail.com',
                'password' => bcrypt('CD51402222'),
                'role_id' => 2,
                'faculty_id' => 6
            ]
        ]);
    }
}
