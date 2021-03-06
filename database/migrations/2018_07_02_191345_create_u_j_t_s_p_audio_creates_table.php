<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUJTSPAudioCreatesTable extends Migration
{
    public function up()
    {
        Schema::create('u_j_t_s_p_audio_creates', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('u_j_t_s_p_a_id')->unsigned();
            $table->foreign('u_j_t_s_p_a_id')->references('id')->on('user_job_t_s_p_audios')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('u_j_t_s_p_audio_creates');
    }
}