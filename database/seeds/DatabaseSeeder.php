<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionTableSeeder::class);
        $this->call(Role::class);

        // không chạy cái này. vì code là ghi tạo semester thì sẽ tạo form cho các sinh viên có sẵn.
        // nếu chạy ở đây thì sv k có form
//        $this->call(Semester::class);

        $this->call(Faculty::class);

        $this->call(StaffSeeder::class);

        $this->call(Classes::class);

        //giờ đã có dữ liệu, k cần chạy
//        $this->call(StudentSeeder::class);

        $this->call(EvaluationCriteria::class);

        // k dc gi ca. vi k co semester
//        $this->call(EvaluationForm::class);

        //giờ đã có dữ liệu, k cần chạy
//        $this->call(CreateEvaluationFormForStudents::class);

        // thời gian chấm thì yêu cầu học kì. nên cũng bỏ qua. cái này tạo học kì xong r gọi riêng hoặc tự tạo = tay
//        $this->call(MarkTime::class);

        //giờ đã có dữ liệu, k cần chạy
//        $this->call(UpdateStudent::class);

    }
}
