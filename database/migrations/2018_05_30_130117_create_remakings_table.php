<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemakingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remakings', function (Blueprint $table) {
            $table->increments('id');
            $table->text('remarking_reason');
            $table->text('remarking_reply')->nullable();
            $table->integer('reply_by')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->longText('old_score')->nullable();
            $table->integer('evaluation_form_id')->unsigned()->nullable();
            $table->foreign('evaluation_form_id')->references('id')->on('evaluation_forms')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('remakings');
    }
}
