<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_space_id_to_projects_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up()
{
    Schema::table('projects', function (Blueprint $table) {
        if (!Schema::hasColumn('projects', 'space_id')) {
            $table->foreignId('space_id')->constrained()->onDelete('cascade');
        }
    });
}

public function down()
{
    Schema::table('projects', function (Blueprint $table) {
        $table->dropForeign(['space_id']);
        $table->dropColumn('space_id');
    });
}

  };


