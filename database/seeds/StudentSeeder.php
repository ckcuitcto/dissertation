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
                'name' => 'Thai Duc',
                'email' => 'thducit@gmail.com',
                'password' => bcrypt('DH51400250'),
            ],
            [
                'id' => 'DH51400251',
                'name' => 'Thai Duc 1',
                'email' => 'thducit1@gmail.com',
                'password' => bcrypt('DH51400251'),
            ]
        ]);
    }
}
