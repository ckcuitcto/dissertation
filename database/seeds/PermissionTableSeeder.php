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
                'name' => 'proofs-list',
                'display_name' => 'Xem minh chứng',
                'description' => 'Sinh viên xem lại danh sách minh chứng của mình'
            ],
            [
                'name' => 'proofs-update',
                'display_name' => 'Sửa trạng thái minh chứng',
                'description' => 'Xem minh chứng có hợp lệ hay không và sửa trạng thía minh chứng đó'
            ], [
                'name' => 'proofs-add',
                'display_name' => 'Thêm minh chứng',
                'description' => 'Thêm minh chứng mới'
            ], [
                'name' => 'proofs-delete',
                'display_name' => 'Xóa minh chứng',
                'description' => 'Xóa minh chứng nếu trang trong thời gian chấm của sinh viên'
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
                'name' => 'semester-change',
                'display_name' => 'Thêm xóa sửa học kỳ',
                'description' => 'Chỉ nhân viên mới được thêm xóa sửa học kỳ'
            ],[
                'name' => 'student-list',
                'display_name' => 'Xem danh sách sinh viên',
                'description' => 'Có thể xem danh sách sinh viên'
            ],[
                'name' => 'manage-user',
                'display_name' => 'Quản lí tài khoản',
                'description' => 'Thêm xóa sửa tài khoản'
            ],[
                'name' => 'manage-remaking',
                'display_name' => 'Quản lí phúc khảo',
                'description' => 'Xem danh sách, trả lời yêu cầu phúc khảo'
            ],[
                'name' => 'manage-class',
                'display_name' => 'Quản lí lớp',
                'description' => 'Quản lí lớp'
            ],[
                'name' => 'export-file',
                'display_name' => 'Xuất file',
                'description' => 'Xuất file báo cáo'
            ],[
                'name' => 'export-users',
                'display_name' => 'Xuất file bảng tổng hợp',
                'description' => 'Xuất file bảng tổng hợp đánh giá RL của SV'
            ],
        ]);
    }
}
