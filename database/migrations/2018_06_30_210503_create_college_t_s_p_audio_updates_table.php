<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollegeTSPAudioUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('college_t_s_p_audio_updates', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->string('actual_name', 100);
            $table->string('past_name', 100);
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('c_t_s_p_a_id')->unsigned();
            $table->foreign('c_t_s_p_a_id')->references('id')->on('college_t_s_p_audios')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('college_t_s_p_audio_updates');
    }
}