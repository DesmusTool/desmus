<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProjectTSToolFileCTable extends Migration
{
    public function up()
    {
        Schema::create('user_project_t_s_tool_file_c', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('user_p_t_s_t_f_id')->unsigned();
            $table->foreign('user_p_t_s_t_f_id')->references('id')->on('user_project_t_s_tool_files')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_project_t_s_tool_file_c');
    }
}