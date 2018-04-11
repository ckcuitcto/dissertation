<?php

use Illuminate\Database\Seeder;

class Topic extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('topics')->insert([
            ['title' => 'I. Ý thức tham gia học tập (0 – 20 điểm)', 'max_score' => '20'],
            ['title' => 'II. Ý thức chấp hành nội quy, quy chế, quy định trong nhà trường (0 – 25 điểm) ', 'max_score' => '25'],
            ['title' => 'III. Ý thức tham gia các hoạt động chính trị - xã hội, văn hóa, văn nghệ, thể thao, phòng chống các tệ nạn xã hội (0 – 20 điểm) ', 'max_score' => '20'],
            ['title' => 'IV. Ý thức công dân trong quan hệ cộng đồng (0 – 25 điểm) ', 'max_score' => '25'],
            ['title' => 'V. Ý thức và kết quả tham gia công tác cán bộ lớp, các đoàn thể, tổ chức khác trong nhà trường hoặc sinh viên đạt được thành tích đặc biệt trong học tập, rèn luyện (0 – 10 điểm)', 'max_score' => '25'],
        ]);
    }
}
