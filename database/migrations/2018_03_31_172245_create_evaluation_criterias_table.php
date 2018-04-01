<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_criterias', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->text('content');
            $table->text('detail');
            $table->integer('mark_range_from');
            $table->integer('mark_range_to');
            $table->integer('topic_id')->unsigned()->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
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
        Schema::dropIfExists('evaluation_criterias');
    }
}
