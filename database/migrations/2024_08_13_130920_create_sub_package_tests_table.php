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
        Schema::create('sub_package_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId("sub_package_id")->nullable()->index()->references('id')->on('sub_packages')->onDelete('set null');
            $table->foreignId("lab_test_id")->nullable()->index()->references('id')->on('lab_tests')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_package_tests');
    }
};
