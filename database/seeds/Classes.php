<?php

use Illuminate\Database\Seeder;

class Classes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('classes')->insert([
            ['name' => 'D14-TH01','faculty_id' => '1', 'staff_id' => '1'],
            ['name' => 'D14-TH02','faculty_id' => '1', 'staff_id' => '1'],
            ['name' => 'D14-TH03','faculty_id' => '1', 'staff_id' => '2'],
            ['name' => 'D14-QT01','faculty_id' => '2', 'staff_id' => '7'],
            ['name' => 'D14-QT02','faculty_id' => '2', 'staff_id' => '8'],
        ]);
    }
}
