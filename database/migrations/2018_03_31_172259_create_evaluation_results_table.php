<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_results', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('score');
            $table->integer('evaluation_criteria_id')->unsigned()->nullable();
            $table->integer('student_id')->unsigned()->nullable();
            $table->integer('staff_id')->unsigned()->nullable();
            $table->integer('evaluation_form_id')->unsigned()->nullable();
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
        Schema::dropIfExists('evaluation_results');
    }
}
