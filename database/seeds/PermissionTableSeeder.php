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
            [
                'name' => 'can-mark',
                'display_name' => 'Có thể chấm điểm',
                'description' => 'Chấm điểm'
            ],
            [
                'name' => 'can-change-news',
                'display_name' => 'Thêm xóa sửa tin tức',
                'description' => 'Chỉ nhân viên mới thêm, xóa, sửa đc tin tức'
            ],
            [
                'name' => 'can-list-student-transcript',
                'display_name' => 'Xem danh sách sinh viên',
                'description' => 'Xem danh sách sinh viên để xem bảng điểm'
            ],[
                'name' => 'personal-information-list',
                'display_name' => 'Xem danh sách sinh viên',
                'description' => 'Xem danh sách sinh viên để thông tin cá nhân'
            ],
            [
                'name' => 'can-list-student',
                'display_name' => 'Xem danh sách sinh viên',
                'description' => 'Phòng CTSV, Ban chấp hành Khoa, CVHT có thể xem danh sách sinh viên'
            ],
            [
                'name' => 'can-change-student',
                'display_name' => 'Thêm, sửa, xóa sinh viên',
                'description' => 'Chỉ nhân viên mới được thêm, sửa, xóa sinh viên'
            ],
            [
                'name' => 'proofs-change',
                'display_name' => 'Thêm, xóa, sửa minh chứng',
                'description' => 'Chỉ sinh viên mới được thêm, xóa, sửa minh chứng'
            ],
            [
                'name' => 'proofs-list',
                'display_name' => 'Xem minh chứng',
                'description' => 'Ai cũng có thể xem minh chứng'
            ],
            [
                'name' => 'comment-add',
                'display_name' => 'Thêm ý kiến',
                'description' => 'Sinh viên và ban cán sự lớp có quyền thêm và sửa ý kiến'
            ],
            [
                'name' => 'comment-delete',
                'display_name' => 'Xóa ý kiến',
                'description' => 'Chỉ admin mới được xóa ý kiến'
            ],
            [
                'name' => 'comment-reply',
                'display_name' => 'Trả lời ý kiến',
                'description' => 'Trả lời ý kiến sinh viên'
            ],
            [
                'name' => 'count-point',
                'display_name' => 'Tổng hợp điểm rèn luyện',
                'description' => 'Chỉ CVHT'
            ],
            [
                'name' => 'user-rights',
                'display_name' => 'Phân quyền cho user',
                'description' => 'Chỉ admin mới được cấp quyền'
            ],
            [
                'name' => 'semester-change',
                'display_name' => 'Thêm xóa sửa học kỳ',
                'description' => 'Chỉ nhân viên mới được thêm xóa sửa học kỳ'
            ]
            // [
            //     'name' => 'information-technology',
            //     'display_name' => 'Công nghệ thông tin',
            //     'description' => 'Ban chấp hành khoa, giáo viên'
            // ],
            // [
            //     'name' => 'business-administration',
            //     'display_name' => 'Quản trị kinh doanh',
            //     'description' => 'Ban chấp hành khoa, giáo viên'
            // ],
            // [
            //     'name' => 'design',
            //     'display_name' => 'Design',
            //     'description' => 'Ban chấp hành khoa, giáo viên'
            // ],
            // [
            //     'name' => 'Faculty-of-Electrical-Electronics-Engineering',
            //     'display_name' => 'Khoa Điện & Điện tử',
            //     'description' => 'Ban chấp hành khoa, giáo viên'
            // ]
        ]);
    }
}
