<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name',200);
            $table->string('email',200)->unique();
            $table->string('password');
            $table->tinyInteger('gender')->nullable();
            $table->string('address',200)->nullable();
            $table->string('phone_number',20)->nullable();
            $table->date('birthday')->nullable();
            $table->string('avatar',200);
            $table->integer('role_id')->unsigned()->nullable();
            $table->integer('class_id')->unsigned()->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('students');
    }
}
