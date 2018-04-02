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
        DB::table('staff')->insert([
            [
                'id' => '123123',
                'name' => 'Giao vien 1',
                'email' => 'GV123123@gmail.com',
                'password' => bcrypt('GV123123'),
            ],
            [
                'id' => '124124',
                'name' => 'Giao vien 2 1',
                'email' => 'GV123123@gmail1.com',
                'password' => bcrypt('GV124124'),
            ],
            [
                'id' => '124125',
                'name' => 'Giao vien han',
                'email' => 'GV124125@gmail1.com',
                'password' => bcrypt('GV124125'),
            ]
        ]);
    }
}
