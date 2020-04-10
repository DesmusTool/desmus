<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSizeToProjectImages extends Migration
{
    public function up()
    {
        Schema::table('project_t_s_galery_images', function($table) { $table->integer('file_size')->nullable(); });
    }

    public function down()
    {
        Schema::table('project_t_s_galery_images', function($table) { $table->dropColumn('file_size'); });
    }
}