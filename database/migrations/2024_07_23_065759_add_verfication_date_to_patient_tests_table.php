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
        Schema::table('patient_tests', function (Blueprint $table) {
            $table->dateTime('date_report_verification')->nullable();
            $table->text('remarks')->nullable();
            $table->string('chart_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_tests', function (Blueprint $table) {
            $table->dropColumn('date_report_verification');
            $table->dropColumn('remarks');
            $table->dropColumn('chart_no');
        });
    }
};
