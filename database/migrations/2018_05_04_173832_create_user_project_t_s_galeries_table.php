<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProjectTSGaleriesTable extends Migration
{
    public function up()
    {
        Schema::create('user_project_t_s_galeries', function (Blueprint $table) {
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
            $table->integer('project_t_s_galery_id')->unsigned();
            $table->foreign('project_t_s_galery_id')->references('id')->on('project_t_s_galeries')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_project_t_s_galeries');
    }
}