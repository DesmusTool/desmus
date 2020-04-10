<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSizeToJobFiles extends Migration
{
    public function up()
    {
        Schema::table('job_t_s_files', function($table) { $table->integer('file_size')->nullable(); });
    }

    public function down()
    {
        Schema::table('job_t_s_files', function($table) { $table->dropColumn('file_size'); });
    }
}