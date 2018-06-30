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
                'date_start_to_mark' => '2017-12-30',
                'date_end_to_mark' => '2018-02-28',
            ],[
                'year_from' => '2017',
                'year_to' => '2018',
                'term' => '2',
                'date_start_to_mark' => '2018-04-30',
                'date_end_to_mark' => '2018-06-30',
            ]
        ]);
    }
}
