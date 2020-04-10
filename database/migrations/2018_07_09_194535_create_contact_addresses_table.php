<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('contact_addresses', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->charset='utf8';
            $table->collation='utf8_unicode_ci';
            $table->increments('id');
            $table->string('street', 200);
            $table->string('num_ext', 10);
            $table->string('num_int', 10);
            $table->string('state', 50);
            $table->string('municipality', 50);
            $table->string('postal_code', 10);
            $table->string('location', 1000);
            $table->integer('contact_id')->unsigned();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_addresses');
    }
}