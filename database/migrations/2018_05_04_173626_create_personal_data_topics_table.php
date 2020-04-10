<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalDataTopicsTable extends Migration
{
    public function up()
    {
        Schema::create('personal_data_topics', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->string('name', 100);
            $table->text('specific_info');
            $table->integer('views_quantity')->default(0);
            $table->integer('updates_quantity')->default(0);
            $table->string('status')->default('on');
            $table->integer('personal_data_id')->unsigned();
            $table->foreign('personal_data_id')->references('id')->on('personal_datas')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('personal_data_topics');
    }
}