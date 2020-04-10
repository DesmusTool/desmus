<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpecificInfoSizeToJobs extends Migration
{
    public function up()
    {
        Schema::table('jobs', function($table) { $table->integer('specific_info_size')->nullable(); });
    }

    public function down()
    {
        Schema::table('jobs', function($table) { $table->dropColumn('specific_info_size'); });
    }
}