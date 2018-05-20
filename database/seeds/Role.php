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
                'description' => 'Sinh viên',
                'weight' => 1
            ],
            ['name' => 'bancansulop',
                'display_name' => 'Ban các sự lớp',
                'description' => 'Lớp trưởng, bí thư',
                'weight' => 2
            ],
            ['name' => 'covanhoctap',
                'display_name' => 'Cố vấn học tập',
                'description' => 'Cố vấn học tập',
                'weight' => 3
            ],
            ['name' => 'banchunhiemkhoa',
                'display_name' => 'Ban chủ nhiệm khoa',
                'description' => 'Khoa',
                'weight' => 4
            ],
            ['name' => 'phongcongtacsinhvien',
                'display_name' => 'Phòng CTSV',
                'description' => 'Nhân viên phòng CTSV',
                'weight' => 5
            ],
            ['name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Cấp cao nhất',
                'weight' => 6
            ],
        ]);
    }
}
