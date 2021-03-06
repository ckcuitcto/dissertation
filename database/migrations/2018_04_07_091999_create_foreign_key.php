<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('staff')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('notification_users', function (Blueprint $table) {
            $table->foreign('users_id')->references('users_id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('students')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('set null');
        });

        Schema::table('proofs', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('students')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('semester_id')->references('id')->on('semesters')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('evaluation_forms', function (Blueprint $table) {
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('evaluation_criterias', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('evaluation_criterias');
        });

//        Schema::table('topics', function (Blueprint $table) {
//            $table->foreign('parent_id')->references('id')->on('topics')->onDelete('cascade');
//        });

        Schema::table('evaluation_results', function (Blueprint $table) {
            $table->foreign('evaluation_criteria_id')->references('id')->on('evaluation_criterias');
            $table->foreign('evaluation_form_id')->references('id')->on('evaluation_forms')->onDelete('cascade');

//            $table->foreign('marker_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
