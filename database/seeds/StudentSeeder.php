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
        DB::table('students')->insert([
            [
                'id' => '51400250',
                'name' => 'Thai Duc',
                'email' => 'thducit@gmail.com',
                'password' => bcrypt('DH51400250'),
            ],
            [
                'id' => '51400251',
                'name' => 'Thai Duc 1',
                'email' => 'thducit1@gmail.com',
                'password' => bcrypt('DH51400251'),
            ]
        ]);
    }
}
