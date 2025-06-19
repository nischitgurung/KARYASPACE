<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('spaces', function (Illuminate\Database\Schema\Blueprint $table) {
        if (!Schema::hasColumn('spaces', 'user_id')) {
            $table->unsignedBigInteger('user_id');
        }
    });
}

public function down()
{
    Schema::table('spaces', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
    });
}

};
