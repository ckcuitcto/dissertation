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
                'id' => 'GV123123',
                'name' => 'Cố vấn học tập',
                'email' => 'GV123123@gmail.com',
                'password' => bcrypt('GV123123'),
                'role_id' => 3
            ],
            [
                'id' => 'GV124124',
                'name' => 'Ban chủ nhiệm khoa',
                'email' => 'GV122223@gmail1.com',
                'password' => bcrypt('GV124124'),
                'role_id' => 4
            ],
            [
                'id' => 'CT123123',
                'name' => 'Giao vien han',
                'email' => 'GV1241325@gmail1.com',
                'password' => bcrypt('GV124125'),
                'role_id' => 5
            ],
            [
                'id' => 'GV124125',
                'name' => 'Cố vấn học tập',
                'email' => 'GV1241225@gmail1.com',
                'password' => bcrypt('GV124125'),
                'role_id' => 3
            ],

            [
                'id' => 'AD123122',
                'name' => 'V . I . P',
                'email' => 'huynhjduc248@gmail1.com',
                'password' => bcrypt('admin123'),
                'role_id' => 6
            ]
        ]);
    }
}
