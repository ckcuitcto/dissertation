<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentListEachSemestersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_list_each_semesters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('class_id')->unsigned();
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');

            $table->integer('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            $table->integer('monitor_id')->unsigned()->nullable();
            $table->foreign('monitor_id')->references('id')->on('students')->onDelete('set null');

            $table->integer('semester_id')->unsigned()->nullable();
//            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');

            $table->integer('staff_id')->unsigned()->nullable();
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_list_each_semesters');
    }
}
