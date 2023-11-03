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
            $table->text('requirements')->nullable();
            $table->string('salary', 50)->nullable();
            $table->text("application_process")->nullable();
            $table->text('benefits')->nullable();
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

/** 

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    //  Run the migrations.
      public function up(): void {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('location', 255);
            $table->text('description');
            $table->text('requirements');
            $table->string('salary', 50);
            $table->timestamps();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');;
        });
    }

    // Reverse the migrations.
     
    public function down(): void {
        Schema::dropIfExists('jobs');
    }
};

 */
