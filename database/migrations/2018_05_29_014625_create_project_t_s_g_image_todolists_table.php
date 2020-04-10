<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTSGImageTodolistsTable extends Migration
{
    public function up()
    {
        Schema::create('project_t_s_g_image_todolists', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->string('name', 200);
            $table->text('description');
            $table->integer('views_quantity')->default(0);
            $table->integer('updates_quantity')->default(0);
            $table->string('status')->default('on');
            $table->timestamp('datetime')->useCurrent();
            $table->integer('p_t_s_g_i_id')->unsigned();
            $table->foreign('p_t_s_g_i_id')->references('id')->on('project_t_s_galery_images')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_t_s_g_image_todolists');
    }
}