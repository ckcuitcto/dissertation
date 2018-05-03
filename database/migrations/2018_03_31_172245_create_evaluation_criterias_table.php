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
//            $table->text('title');
            $table->text('content')->nullable();
            $table->text('detail')->nullable();
            $table->string('mark_range_display')->nullable();            
            $table->integer('mark_range_from')->nullable();
            $table->integer('mark_range_to')->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('proof')->nullable();
            $table->integer('level');
//             $table->timestamps();
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
