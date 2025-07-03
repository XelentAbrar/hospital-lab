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
            $table->unsignedBigInteger('test_category_id')->nullable();
            if(file_exists(base_path('config/donation.php'))) {
                $table->unsignedBigInteger('careoff_id')->nullable();
                $table->unsignedBigInteger('zf_id')->nullable();
                $table->string('donor_fee')->nullable();
            }
            $table->string('file_no')->nullable();
            $table->string('welfare')->nullable();

            $table->foreign('test_category_id')->references('id')->on('test_categories')->onDelete('set null');
            if(file_exists(base_path('config/donation.php'))) {
                $table->foreign('careoff_id')->references('id')->on('donors')->onDelete('set null');
                $table->foreign('zf_id')->references('id')->on('donors')->onDelete('set null');
            }
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_tests', function (Blueprint $table) {
            $table->dropColumn('test_category_id');
            if(file_exists(base_path('config/donation.php'))) {
                $table->dropColumn('careoff_id');
                $table->dropColumn('zf_id');
                $table->dropColumn('donor_fee');
            }
            $table->dropColumn('file_no');
            $table->dropColumn('welfare');
        });
    }
};
