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
            ],[
                'name' => 'can-mark',
                'display_name' => 'Có thể chấm điểm',
                'description' => 'Chấm điểm'
            ],[
<<<<<<< HEAD
                'name' => 'can-change-news',
                'display_name' => 'Thêm xóa sửa tin tức',
                'description' => 'Chỉ nhân viên mới thêm, xóa, sửa đc tin tức'
=======
                'name' => 'can-list-student-transcript',
                'display_name' => 'Xem danh sách sinh viên',
                'description' => 'Xem danh sách sinh viên để xem bảng điểm'
>>>>>>> 5baf4622a156ae88c2bcec2b53cef3262256e4eb
            ],
            // cái này là tạo. để sau này k dùng db khác thì k phải tạo nữa.
        ]);
    }
}
