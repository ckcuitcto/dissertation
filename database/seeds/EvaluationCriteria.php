<?php

use Illuminate\Database\Seeder;

class EvaluationCriteria extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('evaluation_criterias')->insert([

            //I. Ý thức tham gia học tập (0 – 20 điểm)  id = 1

            //a.	Ý thức học tập id = 6
            [
                'content' => '- Đi học đầy đủ, đúng giờ, nghiêm túc, không bỏ tiết…',
                'mark_range_display' => '0 - 5 điểm',
                'detail' => null,
                'mark_range_from' => 0, 'mark_range_to' => 5, 'topic_id' => 6
            ],
            [
                'content' => '- Không vi phạm quy chế về thi, kiểm tra',
                'mark_range_display' => '5 điểm',
                'detail' => null,
                'mark_range_from' => 5, 'mark_range_to' => null, 'topic_id' => 6
            ],
            [
                'content' => '- Bị cấm thi kết thúc học phần',
                'mark_range_display' => '-5 điểm',
                'detail' => null,
                'mark_range_from' => '-5', 'mark_range_to' => null, 'topic_id' => 6
            ],
            [
                'content' => '- Vi phạm qui chế thi bị lập biên bản',
                'mark_range_display' => '-2 điểm',
                'detail' => null,
                'mark_range_from' => '-2', 'mark_range_to' => null, 'topic_id' => 6
            ],


            //b.	Kết quả học tập id = 7
            [
                'content' => null,
                'mark_range_display' => '0 – 10 điểm',
                'detail' => 'Yếu, kém:0 điểm ; Trung bình: 2 điểm ; Trung bình khá: 4 điểm ; Khá: 6 điểm ; Giỏi: 8 điểm ; Xuất sắc: 10 điểm',
                'mark_range_from' => 0, 'mark_range_to' => 10, 'topic_id' => 7
            ],

            //c.	Nghiên cứu khoa học, tham gia các hoạt động học thuật id =8
            [
                'content' => '- Tham gia các buổi hội thảo học thuật/ Tham gia các hội thi học thuật do Đoàn – Hội, Khoa, Trường tổ chức',
                'mark_range_display' => '1 điểm/lần',
                'detail' => null,
                'mark_range_from' => 0, 'mark_range_to' => 20, 'topic_id' => 8
            ],

            [
                'content' => '- Tham gia nghiên cứu khoa học (đạt yêu cầu, được giảng viên hướng dẫn xác nhận; không tính các bài tập, tiểu luận, đồ án môn học, luận văn)/ Có bằng khen, giấy khen về nghiên cứu khoa học/ Có bài nghiên cứu khoa học được đăng trên tạp chí, nội san (Nộp minh chứng)',
                'mark_range_display' => '2 – 6 điểm/lần',
                'detail' => 'Cấp Khoa: 2 điểm/lần ; Cấp Trường: 4 điểm/lần ; Trung bình khá: 6 điểm/lần',
                'mark_range_from' => 0, 'mark_range_to' => 20, 'topic_id' => 8
            ],

            [
                'content' => '- Có hành vi gây ảnh hưởng xấu đến công tác tổ chức các hoạt động',
                'mark_range_display' => '-3 điểm/lần',
                'detail' => null,
                'mark_range_from' => -3, 'mark_range_to' => null, 'topic_id' => 8
            ],


//            II. Ý thức chấp hành nội quy, quy chế, quy định trong nhà trường (0 – 25 điểm) id = 2
            [
                'content' => '- Tham gia học tập Tuần sinh hoạt công dân hàng năm:',
                'mark_range_display' => '0 – 5 điểm',
                'detail' => 'Không tham gia: 0 điểm ; Không đầy đủ: 2 điểm ; Đầy đủ: 5 điểm ',
                'mark_range_from' => 0, 'mark_range_to' => 5, 'topic_id' => 2
            ],

            [
                'content' => '- Không vi phạm các nội quy, quy chế, quy định trong Nhà Trường',
                'mark_range_display' => '12 điểm',
                'detail' => null,
                'mark_range_from' => 0, 'mark_range_to' => 12, 'topic_id' => 2
            ],

            [
                'content' => '- Tuyên truyền và tham gia các hoạt động nâng cao ý thức của sinh viên trong việc chấp hành nội quy, quy chế, quy định trong Nhà Trường, các hoạt động giữ gìn môi trường, bảo vệ tài sản, thực hành tiết kiệm, bảo vệ an ninh trật tự…',
                'mark_range_display' => '0 - 8 điểm',
                'detail' => null,
                'mark_range_from' => 0, 'mark_range_to' => 8, 'topic_id' => 2
            ],

            ///	Bị xử lý kỷ luật về công tác sinh viên: id = 9
            [
                'content' => '+ Mức khiển trách',
                'mark_range_display' => '-5 điểm',
                'detail' => null,
                'mark_range_from' => '-5', 'mark_range_to' => null, 'topic_id' => 9
            ],

            [
                'content' => '+ Mức cảnh cáo',
                'mark_range_display' => '-10 điểm',
                'detail' => null,
                'mark_range_from' => '-10', 'mark_range_to' => null, 'topic_id' => 9
            ],

            //III. Ý thức tham gia các hoạt động chính trị - xã hội, văn hóa, văn nghệ, thể thao, phòng chống các tệ nạn xã hội (0 – 20 điểm)
            //id = 3
            [
                'content' => '- Tham gia các hoạt động chính trị - xã hội, văn hóa, văn nghệ, thể thao, phòng chống tệ nạn xã hội do Lớp, Khoa, Trường tổ chức',
                'mark_range_display' => '0 -10 điểm',
                'detail' => null,
                'mark_range_from' => 0, 'mark_range_to' => 10, 'topic_id' => 3
            ],

            //	Thành viên tích cực các đội hình văn nghệ, thể thao, công tác xã hội…: id = 10
            [
                'content' => '+ Cấp Khoa (Đội trưởng xác nhận)',
                'mark_range_display' => '2 điểm',
                'detail' => null,
                'mark_range_from' => 2, 'mark_range_to' => null, 'topic_id' => 10
            ],

            [
                'content' => '+ Cấp Trường (Phòng CTSV xác nhận)',
                'mark_range_display' => '5 điểm',
                'detail' => null,
                'mark_range_from' => 5, 'mark_range_to' => null, 'topic_id' => 10
            ],

            [
                'content' => '- Tham gia chiến dịch Mùa hè xanh, Xuân tình nguyện, tiếp sức mùa thi… do Trường, Đoàn – Hội tổ chức',
                'mark_range_display' => '5 điểm',
                'detail' => null,
                'mark_range_from' => 5, 'mark_range_to' => null, 'topic_id' => 3
            ],

            [
                'content' => '-	Được kết nạp Đảng Cộng sản Việt Nam',
                'mark_range_display' => '15 điểm',
                'detail' => null,
                'mark_range_from' => 15, 'mark_range_to' => null, 'topic_id' => 3
            ],

            [
                'content' => '-	Tham gia các hoạt động rèn luyện về chính trị, xã hội, văn nghệ, thể thao và đạt giải thưởng:',
                'mark_range_display' => '3 –7 điểm/lần',
                'detail' => 'Cấp Trường: 3 điểm/lần ; Cấp Thành: 5 điểm/lần ; Cấp Bộ: 7 điểm/lần',
                'mark_range_from' => 0, 'mark_range_to' => 20, 'topic_id' => 3, 'unit' => '\lần'
            ],

            //IV. Ý thức công dân trong quan hệ cộng đồng (0 – 25 điểm) id = 4
            [
                'content' => '-	Không bị cơ quan an ninh hoặc cơ quan Nhà nước có thẩm quyền gửi thông báo vi phạm công tác giữ gìn an ninh trật tự, an toàn xã hội, an toàn giao thông tại địa phương về Trường',
                'mark_range_display' => '10 điểm',
                'detail' => null,
                'mark_range_from' => 10, 'mark_range_to' => null, 'topic_id' => 4
            ],

            [
                'content' => '- Có tinh thần giúp đỡ bạn bè gặp khó khăn trong học tập, trong cuộc sống (được tập thể lớp và GVCN xác nhận)',
                'mark_range_display' => '0 – 5 điểm',
                'detail' => null,
                'mark_range_from' => 0, 'mark_range_to' => 5, 'topic_id' => 4
            ],

            [
                'content' => '-	Không chia rẽ bè phái, gây bất hòa, xích mích trong nội bộ, làm ảnh hưởng đến tinh thần đoàn kết của tập thể',
                'mark_range_display' => '5 điểm',
                'detail' => null,
                'mark_range_from' => 5, 'mark_range_to' => null, 'topic_id' => 4
            ],

            [
                'content' => '-	Được biểu dương, khen thưởng (từ cấp trường trở lên) về tham gia giữ gìn trật tự an toàn xã hội, về thành tích đấu tranh bảo vệ pháp luật, về hành vi giúp người, cứu người ((Nộp minh chứng)',
                'mark_range_display' => '10 điểm',
                'detail' => null,
                'mark_range_from' => 10, 'mark_range_to' => null, 'topic_id' => 4
            ],

            [
                'content' => '-	Vi phạm pháp luật, hành chánh ở địa phương cư trú, Ký túc xá, có công văn gửi về Trường',
                'mark_range_display' => '-10 điểm',
                'detail' => null,
                'mark_range_from' => '-10', 'mark_range_to' => null, 'topic_id' => 4
            ],

            [
                'content' => '- Có các hành vi không đúng mực trong lớp, trong Trường, gây chia rẽ bè phái làm mất đoàn kết trong tập thể; bản thân gây ảnh hưởng không tốt đối với tập thể',
                'mark_range_display' => '-10 điểm',
                'detail' => null,
                'mark_range_from' => '-10', 'mark_range_to' => null, 'topic_id' => 4
            ],

            //V. Ý thức và kết quả tham gia công tác cán bộ lớp, các đoàn thể, tổ chức khác trong nhà trường hoặc sinh viên đạt được thành tích đặc biệt trong học tập, rèn luyện (0 – 10 điểm)
            // id =5
            [
                'content' => '-	Ban Cán sự lớp; Ban Chấp hành Đoàn – Hội các cấp; Ban Chủ nhiệm các câu lạc bộ, đội, nhóm các cấp hoàn thành nhiệm vụ (theo đề nghị của GVCN/ Cố vấn học tập, các đoàn thể):',
                'mark_range_display' => '0 – 10 điểm',
                'detail' => 'Không hoàn thành: 0 điểm ; Trung bình: 4 điểm ; Khá: 6 điểm ; Tốt: 8 điểm ; Xuất sắc: 10 điểm ',
                'mark_range_from' => 0, 'mark_range_to' => 10, 'topic_id' => 5
            ],

            [
                'content' => '- Đạt được thành tích đặc biệt trong học tập, rèn luyện (được khen thưởng cấp: Trường, Thành phố, Quốc gia, Quốc tế) (Nộp minh chứng)',
                'mark_range_display' => '6 – 10 điểm',
                'detail' => null,
                'mark_range_from' => 6, 'mark_range_to' => 10, 'topic_id' => 5
            ],

            [
                'content' => '-	Tích cực tham gia công tác cán bộ lớp, công tác Đoàn TN, Hội SV',
                'mark_range_display' => '0 – 5 điểm',
                'detail' => null,
                'mark_range_from' => 0, 'mark_range_to' => 5, 'topic_id' => 5
            ],
        ]);
    }
}
