<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTSGaleryImagesTable extends Migration
{
    public function up()
    {
        Schema::create('project_t_s_galery_images', function (Blueprint $table) {
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
            $table->integer('project_t_s_g_id')->unsigned();
            $table->foreign('project_t_s_g_id')->references('id')->on('project_t_s_galeries')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_t_s_galery_images');
    }
}