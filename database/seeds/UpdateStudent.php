<?php

use Illuminate\Database\Seeder;

class UpdateStudent extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('students')->update([
            [
                'id' => 2,
                'class_id' => '1',
                'academic_year_from' => '2014',
                'academic_year_to' => '2018',
            ],[
                'id' => 3,
                'class_id' => '1',
                'academic_year_from' => '2014',
                'academic_year_to' => '2018',
            ],[
                'id' => 4,
                'class_id' => '1',
                'academic_year_from' => '2014',
                'academic_year_to' => '2018',
            ],[
                'id' => 5,
                'class_id' => '4',
                'academic_year_from' => '2014',
                'academic_year_to' => '2018',
            ],[
                'id' => 6,
                'class_id' => '4',
                'academic_year_from' => '2014',
                'academic_year_to' => '2018',
            ],[
                'id' => 7,
                'class_id' => '5',
                'academic_year_from' => '2014',
                'academic_year_to' => '2018',
            ],
        ]);
    }
}
