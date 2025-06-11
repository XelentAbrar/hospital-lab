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
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId("test_category_id")->nullable()->index()->references('id')->on('test_categories')->onDelete('set null');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->float('price')->default(0);
            $table->text('sample_required', 500)->nullable();
            $table->text('reporting_time', 500)->nullable();
            $table->string('min_value')->nullable();
            $table->string('max_value')->nullable();
            $table->string('value_range')->nullable();
            $table->foreignId("unit_id")->nullable()->index()->references('id')->on('units')->onDelete('set null');
            $table->enum("status", ['active', 'inactive'])->nullable();
            $table->timestamps();
        });

        Schema::create('package_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId("package_id")->nullable()->index()->references('id')->on('packages')->onDelete('set null');
            $table->foreignId("lab_test_id")->nullable()->index()->references('id')->on('lab_tests')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('patient_tests', function (Blueprint $table) {
            $table->id();
            $table->dateTime('test_date')->nullable();
            $table->integer('lab_no')->nullable();
            $table->enum('test_nature', ['General', 'Widal', 'Cross_Match','Other_Receipt'])->nullable();
            $table->foreignId("lab_test_id")->nullable()->index()->references('id')->on('lab_tests')->onDelete('set null');
            $table->enum('test_type', ['IPD', 'OPD', 'Private'])->nullable();
            $table->enum('status', ['Ordered', 'In_Progress', 'Completed', 'Cancelled'])->nullable();
            $table->string('referred_by')->nullable();
            $table->foreignId("patient_id")->nullable()->index()->references('id')->on('patients')->onDelete('set null');
            $table->string("patient_name")->nullable();
            $table->string("patient_cnic")->nullable();
            $table->date("patient_dob")->nullable();
            $table->enum("patient_gender", ["Male", "Female", "Prefer_not_to_say","Children"])->nullable();
            $table->string("patient_relation_name")->nullable();
            $table->enum("patient_relation_type", ['Guardian', 'Spouse', 'Parent', 'Sibling', 'Child', 'Friend', 'Relative'])->nullable();
            $table->string("patient_address")->nullable();
            $table->string("patient_city_id")->nullable();
            $table->string("patient_state_id")->nullable();
            $table->string("patient_country_id")->nullable();
            $table->string("patient_phone")->nullable();
            $table->string("patient_email")->nullable();
            $table->string("patient_blood_group")->nullable();
            $table->string("patient_rh_factor")->nullable();
            $table->string("specimen")->nullable();
            $table->boolean("free_test")->default(0);
            $table->enum("discount_type", ['Fixed', 'Percentage'])->nullable();
            $table->decimal("discount_value")->default(0)->nullable();
            $table->decimal("total_amount")->default(0);
            $table->timestamps();
        });

        Schema::create('test_general_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("patient_test_id")->nullable()->index()->references('id')->on('patient_tests')->onDelete('set null');
            $table->foreignId("lab_test_id")->nullable()->index()->references('id')->on('lab_tests')->onDelete('set null');
            $table->string('result')->nullable();
            $table->string('result_2')->nullable();
            $table->text('description', 500)->nullable();
            $table->timestamps();
        });

        Schema::create('widal_tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum("status", ['active', 'inactive'])->nullable();
            $table->timestamps();
        });

        Schema::create('test_widal_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("patient_test_id")->nullable()->index()->references('id')->on('patient_tests')->onDelete('set null');
            $table->foreignId("widal_test_id")->nullable()->index()->references('id')->on('widal_tests')->onDelete('set null');
            $table->string('1_40')->nullable();
            $table->string('1_80')->nullable();
            $table->string('1_160')->nullable();
            $table->string('1_320')->nullable();
            $table->string('1_640')->nullable();
            $table->timestamps();
        });

        Schema::create('test_cross_match_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("patient_test_id")->nullable()->index()->references('id')->on('patient_tests')->onDelete('set null');
            $table->string('donor_name')->nullable();
            $table->string('donor_blood_group')->nullable();
            $table->string('donor_rh_factor')->nullable();
            $table->string('donor_age')->nullable();
            $table->string('donor_blood_pressure')->nullable();
            $table->string('donor_anti_hcv')->nullable();
            $table->string('donor_hbsag')->nullable();
            $table->string('donor_hiv_antibodies')->nullable();
            $table->string('donor_cross_match')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('test_cross_match_details');
        Schema::dropIfExists('test_widal_details');
        Schema::dropIfExists('widal_tests');
        Schema::dropIfExists('test_general_details');
        Schema::dropIfExists('patient_tests');
        Schema::dropIfExists('package_tests');
        Schema::dropIfExists('lab_tests');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('test_categories');
        Schema::dropIfExists('units');
        Schema::dropIfExists('test_groups');
        Schema::enableForeignKeyConstraints();
    }
};
