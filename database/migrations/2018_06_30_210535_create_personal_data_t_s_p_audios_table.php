<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalDataTSPAudiosTable extends Migration
{
    public function up()
    {
        Schema::create('personal_data_t_s_p_audios', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->string('name', 100);
            $table->string('description', 1000)->nullable();
            $table->string('file_type', 10)->nullable();
            $table->integer('views_quantity')->default(0);
            $table->integer('updates_quantity')->default(0);
            $table->string('status')->default('on');
            $table->integer('p_d_t_s_p_id')->unsigned();
            $table->foreign('p_d_t_s_p_id')->references('id')->on('personal_data_t_s_playlists')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('personal_data_t_s_p_audios');
    }
}