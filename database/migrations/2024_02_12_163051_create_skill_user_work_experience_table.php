<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('skill_user_work_experience', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('skill_id');
            $table->unsignedBigInteger('user_work_experience_id');
            // Any additional columns can be added here
            $table->timestamps();

            // Foreign keys
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
            $table->foreign('user_work_experience_id')->references('id')->on('user_work_experiences')->onDelete('cascade');

            // Composite unique key to prevent duplicates
            $table->unique(['skill_id', 'user_work_experience_id'], 'unique_skill_user_work_experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('skill_user_work_experience');
    }
};
