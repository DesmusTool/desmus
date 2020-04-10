<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->string('subject', 200);
            $table->text('content');
            $table->integer('views_quantity')->default(0);
            $table->boolean('status')->default(false);
            $table->timestamp('datetime')->useCurrent();
            $table->string('importance', 50);
            $table->integer('s_user_id')->unsigned();
            $table->foreign('s_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('d_user_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}