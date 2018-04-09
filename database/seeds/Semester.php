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
                'year' => '2017',
                'term' => '1',
            ],[
                'year' => '2017',
                'term' => '2',
            ],[
                'year' => '2018',
                'term' => '1',
            ],[
                'year' => '2018',
                'term' => '2',
            ],
        ]);
    }
}
