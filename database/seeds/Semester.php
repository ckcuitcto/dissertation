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
                'date_start_to_mark' => '09/12/2017',
                'date_end_to_mark' => '09/02/2018',
            ],[
                'year_from' => '2017',
                'year_to' => '2018',
                'term' => '2',
                'date_start_to_mark' => '09/04/2017',
                'date_end_to_mark' => '09/06/2017',
            ]
        ]);
    }
}
