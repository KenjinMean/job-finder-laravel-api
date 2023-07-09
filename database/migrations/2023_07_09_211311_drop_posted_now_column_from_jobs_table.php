<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('posted_at');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down() {
        Schema::table('jobs', function (Blueprint $table) {
            $table->boolean('posted_at')->default(false);
        });
    }
};