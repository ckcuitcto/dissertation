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
            ['name' => 'D14-TH01', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D14-TH02', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D14-TH03', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D14-TH04', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D14-TH05', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D14-TH06', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D15-TH01', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D15-TH02', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D15-TH03', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D15-TH04', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D15-TH05', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D15-TH06', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D15-TH07', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D15-TH08', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D15-TH09', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D15-TH10', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D16-TH01', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D16-TH02', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D16-TH03', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D16-TH04', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D16-TH05', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D16-TH06', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D16-TH07', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D16-TH08', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D16-TH09', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D16-TH10', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D17-TH01', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D17-TH02', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D17-TH03', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D17-TH04', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D17-TH05', 'faculty_id' => '1', 'staff_id' => '6'],
            ['name' => 'D17-TH06', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D17-TH07', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D17-TH08', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D17-TH09', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D17-TH10', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'C17-TH01', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'C16-TH01', 'faculty_id' => '1', 'staff_id' => '7'],
            ['name' => 'D14-QT01', 'faculty_id' => '2', 'staff_id' => '8'],
            ['name' => 'D14-QT02', 'faculty_id' => '2', 'staff_id' => '8'],
            ['name' => 'D14-CK01', 'faculty_id' => '6', 'staff_id' => '9'],
            ['name' => 'D14-CK02', 'faculty_id' => '6', 'staff_id' => '9']
        ]);
    }
}
