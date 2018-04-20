<?php

use Illuminate\Database\Seeder;

class Faculty extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('faculties')->insert([
            ['name' => 'Công nghệ thông tin'],
            ['name' => 'Quản trị kinh doanh'],
            ['name' => 'Công nghệ thực phẩm'],
            ['name' => 'Điện - Điện tử'],
            ['name' => 'Kỹ thuật công trình'],
            ['name' => 'Cơ khí'],
            ['name' => 'Design']
        ]);
    }
}
