<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSemestersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('year_from');
            $table->integer('year_to');
            $table->integer('term');
            $table->date('date_start_to_mark');
            $table->date('date_end_to_mark');
            $table->date('date_start_to_re_mark')->nullable();
            $table->date('date_end_to_re_mark')->nullable();
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
        Schema::dropIfExists('semesters');
    }
}
