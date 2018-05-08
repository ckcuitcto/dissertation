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
                'description' => ''
            ],
            ['name' => 'bancansulop',
                'display_name' => 'Ban các sự lớp',
                'description' => 'Lớp trưởng'
            ],
            ['name' => 'covanhoctap',
                'display_name' => 'Cố vấn học tập',
                'description' => ''
            ],
            ['name' => 'banchunhiemkhoa',
                'display_name' => 'Ban chủ nhiệm khoa',
                'description' => ''
            ],
            ['name' => 'phongcongtacsinhvien',
                'display_name' => 'Phòng CTSV',
                'description' => ''
            ],
            ['name' => 'admin',
                'display_name' => 'ADMIN',
                'description' => ''
            ]
        ]);
    }
}
