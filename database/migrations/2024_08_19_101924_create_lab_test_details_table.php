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
        Schema::create('lab_test_details', function (Blueprint $table) {
            $table->id();
            $table->string('test_report')->nullable();
            $table->foreignId("lab_test_id")->nullable()->index()->references('id')->on('lab_tests')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_test_details');
    }
};
