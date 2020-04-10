<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpecificInfoSizeToJobTopics extends Migration
{
    public function up()
    {
        Schema::table('job_topics', function($table) { $table->integer('specific_info_size')->nullable(); });
    }

    public function down()
    {
        Schema::table('job_topics', function($table) { $table->dropColumn('specific_info_size'); });
    }
}