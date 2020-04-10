<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpecificInfoSizeToProjectSections extends Migration
{
    public function up()
    {
        Schema::table('project_topic_sections', function($table) { $table->integer('specific_info_size')->nullable(); });
    }

    public function down()
    {
        Schema::table('project_topic_sections', function($table) { $table->dropColumn('specific_info_size'); });
    }
}