<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCollegeTSGalerieDTable extends Migration
{
    public function up()
    {
        Schema::create('user_college_t_s_galerie_d', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('user_c_t_s_g_id')->unsigned();
            $table->foreign('user_c_t_s_g_id')->references('id')->on('user_college_t_s_galeries')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_college_t_s_galerie_d');
    }
}