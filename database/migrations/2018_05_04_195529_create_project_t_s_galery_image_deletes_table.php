<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTSGaleryImageDeletesTable extends Migration
{
    public function up()
    {
        Schema::create('project_t_s_galery_image_deletes', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('project_t_s_g_image_id')->unsigned();
            $table->foreign('project_t_s_g_image_id')->references('id')->on('project_t_s_galery_images')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_t_s_galery_image_deletes');
    }
}