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
            $table->unsignedBigInteger('sub_package_id')->nullable();
            $table->foreign('sub_package_id')->references('id')->on('sub_packages')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_general_details', function (Blueprint $table) {

            $table->dropColumn('sub_package_id');

        });
    }
};
