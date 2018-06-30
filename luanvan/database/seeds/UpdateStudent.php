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
        $arr = [
            [
                'id' => 2,
                'class_id' => 17,
                'academic_year_from' => '2014',
                'academic_year_to' => '2018',
            ],[
                'id' => 3,
                'class_id' => 17,
                'academic_year_from' => '2014',
                'academic_year_to' => '2018',
            ],[
                'id' => 4,
                'class_id' => 17,
                'academic_year_from' => '2014',
                'academic_year_to' => '2018',
            ],[
                'id' => 5,
                'class_id' => 19,
                'academic_year_from' => '2014',
                'academic_year_to' => '2018',
            ],[
                'id' => 6,
                'class_id' => 20,
                'academic_year_from' => '2014',
                'academic_year_to' => '2018',
            ]
        ];

        foreach($arr as $value){
            \App\Model\Student::find($value['id'])->update([
                'class_id' => $value['class_id'],
                'academic_year_from' => $value['academic_year_from'],
                'academic_year_to' => $value['academic_year_to']
            ]);
        }
    }
}
