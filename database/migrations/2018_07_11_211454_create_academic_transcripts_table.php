<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicTranscriptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_transcripts', function (Blueprint $table) {
            $table->increments('id');

            $table->string('user_id',10)->nullable();
            $table->foreign('user_id')->references('user_id')->on('students')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('semester_id')->unsigned()->nullable();
            $table->foreign('semester_id')->references('id')->on('semesters')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('class_id')->unsigned()->nullable();
            $table->foreign('class_id')->references('id')->on('classes')->onUpdate('cascade')->onDelete('set null');

            $table->integer('score_ia')->unsigned()->nullable();
            $table->integer('score_ib')->unsigned()->nullable();
            $table->integer('score_ic')->unsigned()->nullable();
            $table->integer('score_ii')->unsigned()->nullable();
            $table->integer('score_iii')->unsigned()->nullable();
            $table->integer('score_iv')->unsigned()->nullable();
            $table->integer('score_v')->unsigned()->nullable();
            $table->text('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academic_transcripts');
    }
}
