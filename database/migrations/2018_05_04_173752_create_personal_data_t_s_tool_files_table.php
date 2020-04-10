<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalDataTSToolFilesTable extends Migration
{
    public function up()
    {
        Schema::create('personal_data_t_s_tool_files', function (Blueprint $table) {
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
            $table->integer('personal_data_t_s_t_id')->unsigned();
            $table->foreign('personal_data_t_s_t_id')->references('id')->on('personal_data_t_s_tools')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('personal_data_t_s_tool_files');
    }
}