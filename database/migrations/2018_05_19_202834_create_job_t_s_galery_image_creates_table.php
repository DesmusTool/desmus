<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTSGaleryImageCreatesTable extends Migration
{
    public function up()
    {
        Schema::create('job_t_s_galery_image_creates', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('job_t_s_g_image_id')->unsigned();
            $table->foreign('job_t_s_g_image_id')->references('id')->on('job_t_s_galery_images')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_t_s_galery_image_creates');
    }
}