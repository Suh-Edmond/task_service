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
            $table->uuid('id')->primary();
            $table->string('title');
            $table->longText('description');
            $table->date('due_date');
            $table->enum('status', [\App\Constants\TaskStatus::PENDING, \App\Constants\TaskStatus::COMPLETE])->default(\App\Constants\TaskStatus::PENDING);
            $table->timestamps();

            $table->uuid('user_id');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

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
