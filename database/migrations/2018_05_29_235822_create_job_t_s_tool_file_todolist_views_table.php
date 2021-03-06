<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTSToolFileTodolistViewsTable extends Migration
{
    public function up()
    {
        Schema::create('job_t_s_tool_file_todolist_views', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('j_t_s_t_f_t_id')->unsigned();
            $table->foreign('j_t_s_t_f_t_id')->references('id')->on('job_t_s_tool_file_todolists')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_t_s_tool_file_todolist_views');
    }
}