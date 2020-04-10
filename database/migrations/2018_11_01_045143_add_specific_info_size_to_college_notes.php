<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpecificInfoSizeToCollegeNotes extends Migration
{
    public function up()
    {
        Schema::table('college_t_s_notes', function($table) { $table->integer('specific_info_size')->nullable(); });
    }

    public function down()
    {
        Schema::table('college_t_s_notes', function($table) { $table->dropColumn('specific_info_size'); });
    }
}