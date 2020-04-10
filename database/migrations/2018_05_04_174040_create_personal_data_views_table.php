<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalDataViewsTable extends Migration
{
    public function up()
    {
        Schema::create('personal_data_views', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('personal_data_id')->unsigned();
            $table->foreign('personal_data_id')->references('id')->on('personal_datas')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('personal_data_views');
    }
}