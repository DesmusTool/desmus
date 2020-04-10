<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProjectTSToolFilesTable extends Migration
{
    public function up()
    {
        Schema::create('user_project_t_s_tool_files', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->datetime('datetime')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->default('on');
            $table->string('permissions')->default('read');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('project_t_s_t_file_id')->unsigned();
            $table->foreign('project_t_s_t_file_id')->references('id')->on('project_t_s_tool_files')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_project_t_s_tool_files');
    }
}