<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTSToolTodolistsTable extends Migration
{
    public function up()
    {
        Schema::create('job_t_s_tool_todolists', function (Blueprint $table) {
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
            $table->integer('j_t_s_t_id')->unsigned();
            $table->foreign('j_t_s_t_id')->references('id')->on('job_t_s_tools')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_t_s_tool_todolists');
    }
}