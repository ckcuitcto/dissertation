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
                'users_id' => 'CTSV000001',
                'name' => 'Phòng CTSV 1',
                'email' => 'CTSV000001@gmail1.com',
                'password' => bcrypt('CTSV000001'),
                'role_id' => 5,
                'faculty_id' => null
            ],[
                'users_id' => 'CTSV000002',
                'name' => 'Phòng CTSV 2',
                'email' => 'CTSV000002@gmail1.com',
                'password' => bcrypt('CTSV000002'),
                'role_id' => 5,
                'faculty_id' => null
            ],[
                'users_id' => 'BCMK000001',
                'name' => 'Ban chủ nhiệm Khoa IT 1',
                'email' => 'BCMK000001@gmail1.com',
                'password' => bcrypt('BCMK000001'),
                'role_id' => 4,
                'faculty_id' => 1
            ],          
            [
                'users_id' => 'BCMK000002',
                'name' => 'Ban chủ nhiệm Khoa QTKD 1',
                'email' => 'BCMK000002@gmail1.com',
                'password' => bcrypt('BCMK000002'),
                'role_id' => 4,
                'faculty_id' => 2
            ],          
            [
                'users_id' => 'BCMK000003',
                'name' => 'Ban chủ nhiệm Khoa Cơ Khí 1',
                'email' => 'BCMK000003@gmail1.com',
                'password' => bcrypt('BCMK000003'),
                'role_id' => 4,
                'faculty_id' => 6
            ],
            [
                'users_id' => 'GVIT000001',
                'name' => 'CVHT Khoa IT 1',
                'email' => 'GVIT000001@gmail.com',
                'password' => bcrypt('GVIT000001'),
                'role_id' => 3,
                'faculty_id' => 1
            ],
            [
                'users_id' => 'GVIT000002',
                'name' => 'CVHT Khoa IT 2',
                'email' => 'GVIT000002@gmail1.com',
                'password' => bcrypt('GVIT000002'),
                'role_id' => 3,
                'faculty_id' => 1
            ],
            [
                'users_id' => 'GVQT000001',
                'name' => 'CVHT Khoa QTKD 1',
                'email' => 'GVQT000001@gmail1.com',
                'password' => bcrypt('GVQT000001'),
                'role_id' => 3,
                'faculty_id' => 2
            ],[
                'users_id' => 'GVCK000001',
                'name' => 'CVHT Khoa Cơ Khí 1',
                'email' => 'GVCK000001@gmail1.com',
                'password' => bcrypt('GVCK000001'),
                'role_id' => 3,
                'faculty_id' => 6
            ],[
                'users_id' => 'ADMINDUCCC',
                'name' => 'A D M I N',
                'email' => 'thducit@gmail.com@gmail1.com',
                'password' => bcrypt('ADMINDUCCC'),
                'role_id' => 6,
                'faculty_id' => null
            ],[
                'users_id' => 'ADMINHANNN',
                'name' => 'A D M I N',
                'email' => 'tnghanit@gmail.com@gmail1.com',
                'password' => bcrypt('ADMINHANNN'),
                'role_id' => 6,
                'faculty_id' => null
            ],
        ]);
    }
}
