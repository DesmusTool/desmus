<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNamesToJobTopicSectionUpdates extends Migration
{
    public function up()
    {
        Schema::table('job_topic_section_updates', function($table) {
            $table->string('actual_name', 100);
            $table->string('past_name', 100);
        });
    }

    public function down()
    {
        Schema::table('job_topic_section_updates', function($table) {
            $table->dropColumn('actual_name');
            $table->dropColumn('past_name');
        });
    }
}