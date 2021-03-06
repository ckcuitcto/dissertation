<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProofsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proofs', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name',255);
            $table->integer('created_by')->unsigned();;
            $table->integer('semester_id')->unsigned()->nullable();
            $table->string('evaluation_criteria_id')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('valid')->default('1');
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
        Schema::dropIfExists('proofs');
    }
}
