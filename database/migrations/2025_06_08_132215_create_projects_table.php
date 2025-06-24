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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Foreign key to the spaces table
            $table->foreignId('space_id')->constrained()->onDelete('cascade');

            // Optional project manager (references users table)
            $table->foreignId('project_manager_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
            $table->string('name');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};


