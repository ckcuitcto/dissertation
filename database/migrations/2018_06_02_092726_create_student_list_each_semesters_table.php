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
            $table->integer('class_id')->unsigned()->nullable();
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade')->onUpdate('cascade');

            $table->string('user_id', 10)->nullable();
            $table->foreign('user_id')->references('user_id')->on('students')->onDelete('cascade')->onUpdate('cascade');

            $table->string('monitor_id', 10)->nullable();
            $table->foreign('monitor_id')->references('user_id')->on('students')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('semester_id')->unsigned();
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade')->onDelete('cascade');

            $table->integer('staff_id')->unsigned()->nullable();
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade')->onUpdate('cascade');

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
