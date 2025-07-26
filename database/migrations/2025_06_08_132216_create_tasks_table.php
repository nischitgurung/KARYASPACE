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
    Schema::create('tasks', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->enum('status', ['pending', 'in progress', 'completed'])->default('pending');
    $table->date('due_date')->nullable();
    $table->unsignedBigInteger('project_id')->nullable();
    $table->unsignedBigInteger('assigned_to')->nullable();
    $table->timestamps();
    $table->integer('weightage')->default(1); // default weightage 1
    // Optional: If you have users and projects tables
    $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
    $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};