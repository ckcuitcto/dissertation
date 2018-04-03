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
            $table->integer('evaluation_form_id')->unsigned()->nullable();

            $table->string('monitor_id',10)->nullable();
            $table->integer('motinor_score')->nullable();

            $table->string('education_adviser_id',10)->nullable();
            $table->integer('education_adviser_score')->nullable();

            $table->string('faculty_id',10)->nullable();
            $table->integer('faculty_score')->nullable();

            $table->string('custom_id',10)->nullable();
            $table->integer('custom_score')->nullable();

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
