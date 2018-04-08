<?php

use Illuminate\Database\Seeder;

class TopicChild extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('topics')->insert([
            ['title' => 'a.	Ý thức học tập', 'parent_id' => '1'],
            ['title' => 'b.	Kết quả học tập ', 'parent_id' => '1'],
            ['title' => 'c.	Nghiên cứu khoa học, tham gia các hoạt động học thuật', 'parent_id' => '1'],
            ['title' => '-	Bị xử lý kỷ luật về công tác sinh viên:', 'parent_id' => '2'],
            ['title' => '-  Thành viên tích cực các đội hình văn nghệ, thể thao, công tác xã hội…:', 'parent_id' => '3'],
        ]);
    }
}

