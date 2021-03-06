<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserJobTSGalerieCTable extends Migration
{
    public function up()
    {
        Schema::create('user_job_t_s_galerie_c', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('user_j_t_s_g_id')->unsigned();
            $table->foreign('user_j_t_s_g_id')->references('id')->on('user_job_t_s_galeries')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_job_t_s_galerie_c');
    }
}