<?php

use Illuminate\Database\Seeder;

class Role extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'sinhvien',
                'display_name' => 'Sinh viên',
                'description' => 'Sinh viên'
            ],
            ['name' => 'bancansulop',
                'display_name' => 'Ban các sự lớp',
                'description' => 'Lớp trưởng, bí thư'
            ],
            ['name' => 'covanhoctap',
                'display_name' => 'Cố vấn học tập',
                'description' => 'Cố vấn học tập'
            ],
            ['name' => 'banchunhiemkhoa',
                'display_name' => 'Ban chủ nhiệm khoa',
                'description' => 'Khoa'
            ],
            ['name' => 'phongcongtacsinhvien',
                'display_name' => 'Phòng CTSV',
                'description' => 'Nhân viên phòng CTSV'
            ],
            ['name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Cấp cao nhất'
            ],
        ]);
    }
}
