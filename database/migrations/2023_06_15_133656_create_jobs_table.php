<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title', 255)->index();
            $table->string('location', 255)->nullable()->index();
            $table->text('description')->nullable();
            $table->text('qualifications')->nullable();
            $table->text('responsibilities')->nullable();
            $table->string('salary', 50)->nullable();
            $table->text('benefits')->nullable();
            $table->string('experience_level', 50)->nullable();
            $table->string('category', 50)->nullable();
            $table->timestamp('application_deadline_at')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('jobs');
    }
};
