<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalDataTSNoteUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('personal_data_t_s_note_updates', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->string('actual_name', 100);
            $table->string('past_name', 100);
            $table->datetime('datetime')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('personal_data_t_s_note_id')->unsigned();
            $table->foreign('personal_data_t_s_note_id')->references('id')->on('personal_data_t_s_notes')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('personal_data_t_s_note_updates');
    }
}