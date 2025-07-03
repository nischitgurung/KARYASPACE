<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('space_user', function (Blueprint $table) {
            $table->foreignId('space_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            $table->primary(['space_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('space_user');
    }
};
