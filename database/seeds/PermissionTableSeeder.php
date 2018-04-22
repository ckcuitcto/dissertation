<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'name' => 'role-list',
                'display_name' => 'Danh sách vai trò',
                'description' => 'Chỉ xem danh sách vai trò'
            ],
            [
                'name' => 'role-change',
                'display_name' => 'Thay đổi vai trò',
                'description' => 'Thêm sửa xóa vai trò'
            ],
            [
                'name' => 'faculty-list',
                'display_name' => 'Danh sách khoa',
                'description' => 'Chỉ xem danh sách khoa'
            ],
            [
                'name' => 'faculty-change',
                'display_name' => 'Thay đổi Khoa',
                'description' => 'Thêm, sửa, xóa khoa'
            ],
        ]);
    }
}
