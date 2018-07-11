<?php

use Illuminate\Database\Seeder;

class EvaluationForm extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = \App\Model\Student::all();
        $arrEvaluationForm = array();
        foreach($students as $student){
            $arrEvaluationForm[] = [
                'student_id' => $student->id
            ];
        }
        $semesters = \App\Model\Semester::all();
        foreach ($semesters as $semester){
            $semester->EvaluationForm()->createMany($arrEvaluationForm);
        }
    }
}
