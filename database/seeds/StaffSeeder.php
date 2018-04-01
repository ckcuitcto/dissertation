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
        DB::table('staff')->insert(
            [
                'id' => '123123',
                'name' => 'Giao vien 1',
                'email' => 'giaovien1@gmail.com',
                'password' => bcrypt('123123'),
            ],
            [
                'id' => '123124',
                'name' => 'Giao vien 2 1',
                'email' => 'giaovien2@gmail1.com',
                'password' => bcrypt('GV123124'),
            ]
        );
    }
}
