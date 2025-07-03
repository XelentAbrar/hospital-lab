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
            $table->foreignId("lab_test_detail_id")->nullable()->index()->references('id')->on('lab_test_details')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_general_details', function (Blueprint $table) {
           $table->dropColumn('lab_test_detail_id');
        });
    }
};
