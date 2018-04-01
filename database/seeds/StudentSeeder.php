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
        DB::table('students')->insert(
            [
            'name' => 'Thai Duc',
            'email' => 'thducit@gmail.com',
            'password' => bcrypt('DH51400250'),
            ]
        );
    }
}
