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
            $table->string('id',10)->unique();
            $table->string('name',200);
            $table->string('class',200);
            $table->string('specialized',200);
            $table->string('faculty',200);
            $table->string('scholastic',200);
            $table->string('email',200)->unique();
            $table->string('password');
            $table->tinyInteger('gender')->nullable();
            $table->string('address',200)->nullable();
            $table->string('phone_number',20)->nullable();
            $table->date('birthday')->nullable();
            $table->string('avatar',200)->nullable();

            $table->integer('faculty_id')->unsigned()->nullable();

            $table->integer('role_id')->unsigned()->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
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
