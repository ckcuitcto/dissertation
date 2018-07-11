<?php

use Illuminate\Database\Seeder;

class MarkTime extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mark_times')->insert([
            [
                'mark_time_start' => '2017-12-31',
                'mark_time_end' => '2018-01-31',
                'semester_id' => '1',
                'role_id' => '1',
            ],[
                'mark_time_start' => '2018-02-01',
                'mark_time_end' => '2018-02-15',
                'semester_id' => '1',
                'role_id' => '2',
            ],[
                'mark_time_start' => '2018-02-16',
                'mark_time_end' => '2018-02-28',
                'semester_id' => '1',
                'role_id' => '3',
            ],[
                'mark_time_start' => '2018-04-30',
                'mark_time_end' => '2018-05-30',
                'semester_id' => '2',
                'role_id' => '1',
            ],[
                'mark_time_start' => '2018-05-30',
                'mark_time_end' => '2018-06-15',
                'semester_id' => '2',
                'role_id' => '2',
            ],[
                'mark_time_start' => '2018-06-15',
                'mark_time_end' => '2018-06-30',
                'semester_id' => '2',
                'role_id' => '3',
            ]
        ]);
    }
}
