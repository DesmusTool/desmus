<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserJobTSNotesTable extends Migration
{
    public function up()
    {
        Schema::create('user_job_t_s_notes', function (Blueprint $table) {
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
            $table->integer('job_t_s_note_id')->unsigned();
            $table->foreign('job_t_s_note_id')->references('id')->on('job_t_s_notes')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_job_t_s_notes');
    }
}