<?php

use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
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
                'id' => 'GVIT000001',
                'name' => 'CVHT Khoa IT 1',
                'email' => 'GVIT000001@gmail.com',
                'password' => bcrypt('GVIT000001'),
                'role_id' => 3,
                'faculty_id' => 1
            ],
            [
                'id' => 'GVIT000002',
                'name' => 'CVHT Khoa IT 2',
                'email' => 'GVIT000002@gmail1.com',
                'password' => bcrypt('GVIT000002'),
                'role_id' => 3,
                'faculty_id' => 1
            ],[
                'id' => 'GVIT000003',
                'name' => 'Ban chủ nhiệm Khoa IT 3',
                'email' => 'GVIT000003@gmail1.com',
                'password' => bcrypt('GVIT000003'),
                'role_id' => 3,
                'faculty_id' => 1
            ],
            [
                'id' => 'CTSV000004',
                'name' => 'Phòng CTSV 4',
                'email' => 'CTSV000004@gmail1.com',
                'password' => bcrypt('CTSV000004'),
                'role_id' => 5,
                'faculty_id' => null
            ],[
                'id' => 'CTSV000005',
                'name' => 'Phòng CTSV 5',
                'email' => 'CTSV000005@gmail1.com',
                'password' => bcrypt('CTSV000005'),
                'role_id' => 5,
                'faculty_id' => null
            ],
            [
                'id' => 'GVQT000006',
                'name' => 'Ban chủ nhiệm Khoa QTKD 6',
                'email' => 'GVQT000006@gmail1.com',
                'password' => bcrypt('GVQT000006'),
                'role_id' => 4,
                'faculty_id' => 2
            ],
            [
                'id' => 'GVQT000007',
                'name' => 'CVHT Khoa QTKD 7',
                'email' => 'GVQT000007@gmail1.com',
                'password' => bcrypt('GVQT000007'),
                'role_id' => 3,
                'faculty_id' => 2
            ],[
                'id' => 'GVQT000008',
                'name' => 'CVHT Khoa QTKD 8',
                'email' => 'GVQT000008@gmail1.com',
                'password' => bcrypt('GVQT000008'),
                'role_id' => 3,
                'faculty_id' => 2
            ]
        ]);
    }
}
