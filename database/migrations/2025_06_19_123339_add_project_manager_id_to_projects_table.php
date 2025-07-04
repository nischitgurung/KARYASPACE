<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('projects', function (Blueprint $table) {
        if (!Schema::hasColumn('projects', 'project_manager_id')) {
            $table->foreignId('project_manager_id')->nullable()->constrained('users')->onDelete('set null');
        }
    });
}


public function down(): void
{
    Schema::table('projects', function (Blueprint $table) {
        $table->dropForeign(['project_manager_id']);
        $table->dropColumn('project_manager_id');
    });
}

};
