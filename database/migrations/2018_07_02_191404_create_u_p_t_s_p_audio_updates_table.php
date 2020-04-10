<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUPTSPAudioUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('u_p_t_s_p_audio_updates', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('u_p_t_s_p_a_id')->unsigned();
            $table->foreign('u_p_t_s_p_a_id')->references('id')->on('user_project_t_s_p_audios')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('u_p_t_s_p_audio_updates');
    }
}