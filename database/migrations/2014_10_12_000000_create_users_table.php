<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('login')->unique();
            $table->string('password');
            $table->boolean('confirm')->default(0)->unsigned();
            $table->tinyInteger('user_type_id')->unsigned();
            $table->foreign('user_type_id')->references('id')->on('user_types');
            $table->tinyInteger('rights_id')->default(1)->unsigned();
            $table->foreign('rights_id')->references('id')->on('rights');
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
