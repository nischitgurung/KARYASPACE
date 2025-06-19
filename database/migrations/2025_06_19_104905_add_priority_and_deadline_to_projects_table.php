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
    Schema::table('projects', function (Blueprint $table) {
        $table->date('deadline')->nullable();
        $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
    });
}

public function down()
{
    Schema::table('projects', function (Blueprint $table) {
        $table->dropColumn(['deadline', 'priority']);
    });
}

};
