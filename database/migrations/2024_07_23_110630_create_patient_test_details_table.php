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
        Schema::create('patient_test_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_test_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->foreign('patient_test_id')->references('id')->on('patient_tests')->onDelete('set null');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_test_details');
    }
};
