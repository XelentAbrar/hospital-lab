<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCrossMatchDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_test_id',
        'donor_name',
        'donor_blood_group',
        'donor_rh_factor',
        'donor_age',
        'donor_blood_pressure',
        'donor_anti_hcv',
        'donor_hbsag',
        'donor_hiv_antibodies',
        'donor_cross_match',
        'blood_bag_no',
        'blood_component',
        'blood_volumn',
        'bleeding_date',
        'bag_expiry_date',
    ];
}
