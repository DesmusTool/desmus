<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamePublicVideoCommentColumn extends Migration
{
    public function up()
    {
        Schema::table('public_video_comment', function(Blueprint $table) {
            $table->renameColumn('public_video_comment_id', 'public_video_id');
        });
    }

    public function down()
    {
        Schema::table('public_video_comment', function(Blueprint $table) {
            $table->renameColumn('public_video_comment_id', 'public_video_id');
        });
    }
}