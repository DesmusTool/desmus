<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalDataTSNTodolistUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('personal_data_t_s_n_todolist_updates', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->string('actual_name', 200);
            $table->string('past_name', 200);
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('p_d_t_s_n_t_id')->unsigned();
            $table->foreign('p_d_t_s_n_t_id')->references('id')->on('personal_data_t_s_n_todolists')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('personal_data_t_s_n_todolist_updates');
    }
}