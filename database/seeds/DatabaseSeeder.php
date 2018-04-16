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
        $this->call(Role::class);
        $this->call(Semester::class);

        $this->call(StaffSeeder::class);

        $this->call(Faculty::class);
        $this->call(Classes::class);

        $this->call(StudentSeeder::class);


        $this->call(Topic::class);
        $this->call(TopicChild::class);
        $this->call(EvaluationCriteria::class);
    }
}
