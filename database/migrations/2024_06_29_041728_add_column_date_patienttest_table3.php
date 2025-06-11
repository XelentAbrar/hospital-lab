<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patient_tests', function (Blueprint $table) {
            $table->dateTime('date_sample')->nullable();
            $table->dateTime('date_report_ready')->nullable();
            $table->dateTime('date_report_collection')->nullable();
            $table->bigInteger('doctor_id')->nullable();
            $table->bigInteger('package_id')->nullable();
            $table->string('lab_incharge')->nullable();
        });


        Schema::table('test_cross_match_details', function (Blueprint $table) {
            $table->string('blood_bag_no')->nullable();
            $table->string('blood_component')->nullable();
            $table->string('blood_volumn')->nullable();
            $table->date('bleeding_date')->nullable();
            $table->date('bag_expiry_date')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('patient_tests', function (Blueprint $table) {
            $table->dropColumn('date_sample');
            $table->dropColumn('date_report_ready');
            $table->dropColumn('date_report_collection');
            $table->dropColumn('doctor_id');
            $table->dropColumn('package_id');
            $table->dropColumn('lab_incharge');
        });

        Schema::table('test_cross_match_details', function (Blueprint $table) {
            $table->dropColumn('blood_bag_no');
            $table->dropColumn('blood_component');
            $table->dropColumn('blood_volumn');
            $table->dropColumn('bleeding_date');
            $table->dropColumn('bag_expiry_date');
        });
    }
};

