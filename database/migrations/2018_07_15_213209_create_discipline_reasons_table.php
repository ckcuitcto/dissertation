<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisciplineReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discipline_reasons', function (Blueprint $table) {
            $table->increments('id');

//            $table->string('user_id',10)->nullable();
//            $table->foreign('user_id')->references('user_id')->on('students')->onUpdate('cascade')->onDelete('cascade');
//
//            $table->integer('semester_id')->unsigned()->nullable();
//            $table->foreign('semester_id')->references('id')->on('semesters')->onUpdate('cascade')->onDelete('cascade');
//
//            $table->integer('discipline_reason_id')->unsigned()->nullable();
//            $table->foreign('discipline_reason_id')->references('id')->on('discipline_reasons')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('evaluation_criteria_id')->unsigned()->nullable();
            $table->foreign('evaluation_criteria_id')->references('id')->on('evaluation_criterias')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('score_minus')->unsigned()->nullable();
            $table->text('reason')->nullable();

            $table->timestamps();
        });

        Schema::table('disciplines', function (Blueprint $table) {
            $table->foreign('discipline_reason_id')->references('id')->on('discipline_reasons')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discipline_reasons');
    }
}
