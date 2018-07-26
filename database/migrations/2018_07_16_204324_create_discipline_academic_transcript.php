<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisciplineAcademicTranscript extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('discipline_academic_transcript', function (Blueprint $table) {
//            $table->increments('id');
//
//            $table->integer('discipline_id')->unsigned();
//            $table->foreign('discipline_id')->references('id')->on('disciplines')->onDelete('cascade')->onUpdate('cascade');
//
//            $table->integer('academic_transcript_id')->unsigned();
//            $table->foreign('academic_transcript_id')->references('id')->on('academic_transcripts')->onDelete('cascade')->onUpdate('cascade');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('discipline_academic_transcript');
    }
}
