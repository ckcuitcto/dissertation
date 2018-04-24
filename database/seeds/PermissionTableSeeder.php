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
                'name' => 'manager-role',
                'display_name' => 'Quản lí Vai trò',
                'description' => 'Thêm, Sửa, Xóa, Xem danh sách '
            ],
            [
                'name' => 'permission-role',
                'display_name' => 'Quản lí Quyền',
                'description' => 'Thêm, Sửa, Xóa, Xem danh sách'
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
