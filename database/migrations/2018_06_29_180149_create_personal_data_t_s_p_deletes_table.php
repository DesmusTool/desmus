<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalDataTSPDeletesTable extends Migration
{
    public function up()
    {
        Schema::create('personal_data_t_s_p_deletes', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('p_d_t_s_p_id')->unsigned();
            $table->foreign('p_d_t_s_p_id')->references('id')->on('personal_data_t_s_playlists')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('personal_data_t_s_p_deletes');
    }
}