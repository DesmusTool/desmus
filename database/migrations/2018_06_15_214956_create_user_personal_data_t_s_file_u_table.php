<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPersonalDataTSFileUTable extends Migration
{
    public function up()
    {
        Schema::create('user_personal_data_t_s_file_u', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('u_p_d_t_s_f_id')->unsigned();
            $table->foreign('u_p_d_t_s_f_id')->references('id')->on('user_personal_data_t_s_files')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_personal_data_t_s_file_u');
    }
}