<?php

use Illuminate\Database\Seeder;

class Semester extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('semesters')->insert([
            [
                'year_from' => '2017',
                'year_to' => '2018',
                'term' => '1',
            ],[
                'year_from' => '2017',
                'year_to' => '2018',
                'term' => '2',
            ]
        ]);
    }
}
