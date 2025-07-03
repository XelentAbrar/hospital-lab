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
        Schema::table('test_general_details', function (Blueprint $table) {
            $table->unsignedBigInteger('test_category_id')->nullable();
            $table->foreign('test_category_id')->references('id')->on('test_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_general_details', function (Blueprint $table) {
            $table->dropColumn('test_category_id');
        });
    }
};
