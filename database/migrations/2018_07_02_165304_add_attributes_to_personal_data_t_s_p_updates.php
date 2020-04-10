<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttributesToPersonalDataTSPUpdates extends Migration
{
    public function up()
    {
        Schema::table('personal_data_t_s_p_updates', function($table) {
            $table->string('actual_name', 200);
            $table->string('past_name', 200);
        });
    }

    public function down()
    {
        Schema::table('personal_data_t_s_p_updates', function($table) {
            $table->dropColumn('actual_name');
            $table->dropColumn('past_name');
        });
    }
}