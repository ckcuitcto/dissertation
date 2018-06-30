<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('users_id',10)->unique();
            $table->string('name',200);
            $table->string('email',191)->unique()->nullable();
            $table->string('password');
            $table->tinyInteger('gender')->nullable();
            $table->string('address',200)->nullable();
            $table->string('phone_number',20)->nullable();
            $table->date('birthday')->nullable();
            $table->string('avatar',200)->nullable();

            $table->integer('faculty_id')->unsigned()->nullable();

            $table->integer('role_id')->unsigned()->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');

            $table->tinyInteger('status')->default(1)->nullable();
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
        Schema::dropIfExists('users');
    }
}
