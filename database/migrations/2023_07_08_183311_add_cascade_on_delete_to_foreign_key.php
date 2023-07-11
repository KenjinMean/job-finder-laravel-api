<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropForeign(['company_id']);

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');
        });
    }

    public function down() {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropForeign(['company_id']);

            $table->foreign('company_id')
                ->references('id')
                ->on('companies');
        });
    }
};