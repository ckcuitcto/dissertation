<?php

use Illuminate\Database\Seeder;

class CreateEvaluationFormForStudents extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('evaluation_forms')->insert([
            ['semester_id' => 1, 'student_id' => 2],
            ['semester_id' => 2, 'student_id' => 2],
            ['semester_id' => 1, 'student_id' => 3],
            ['semester_id' => 2, 'student_id' => 3],
            ['semester_id' => 1, 'student_id' => 4],
            ['semester_id' => 2, 'student_id' => 4],
            ['semester_id' => 1, 'student_id' => 5],
            ['semester_id' => 2, 'student_id' => 5],
            ['semester_id' => 1, 'student_id' => 6],
            ['semester_id' => 2, 'student_id' => 6]
        ]);
    }
}
