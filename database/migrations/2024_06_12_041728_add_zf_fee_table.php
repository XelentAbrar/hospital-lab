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
        if (file_exists(base_path('vendor/xelent-abrar/hospital-lab'))) {
            Schema::table('patient_tests', function (Blueprint $table) {
                $table->string('zf_fee')->nullable();
            });
        }

        if (file_exists(base_path('vendor/xelent-abrar/hospital-opd'))) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->string('zf_fee')->nullable();
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (file_exists(base_path('vendor/xelent-abrar/hospital-lab'))) {
            Schema::table('patient_tests', function (Blueprint $table) {
                $table->dropColumn('zf_fee');
            });
        }
        if (file_exists(base_path('vendor/xelent-abrar/hospital-opd'))) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('zf_fee');
            });
        }
    }
};
