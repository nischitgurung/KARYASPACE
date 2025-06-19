<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpaceIdToProjectsTable extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Add space_id as a foreign key
            $table->unsignedBigInteger('space_id')->after('id');

            // Optional: add foreign key constraint if spaces table exists
            $table->foreign('space_id')->references('id')->on('spaces')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop foreign key first if exists
            $table->dropForeign(['space_id']);
            $table->dropColumn('space_id');
        });
    }
}
