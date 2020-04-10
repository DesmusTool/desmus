<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollegeTSGaleryImageViewsTable extends Migration
{
    public function up()
    {
        Schema::create('college_t_s_galery_image_views', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('college_t_s_g_image_id')->unsigned();
            $table->foreign('college_t_s_g_image_id')->references('id')->on('college_t_s_galery_images')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('college_t_s_galery_image_views');
    }
}