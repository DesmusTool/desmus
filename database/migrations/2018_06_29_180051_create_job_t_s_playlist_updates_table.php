<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTSPlaylistUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('job_t_s_playlist_updates', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('j_t_s_p_id')->unsigned();
            $table->foreign('j_t_s_p_id')->references('id')->on('job_t_s_playlists')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_t_s_playlist_updates');
    }
}