<?php

namespace App\Models\Lab;

use XelentAbrar\HospitalLab\Models\Lab\Package;
use XelentAbrar\HospitalLab\Models\Lab\PatientTest;
use XelentAbrar\HospitalLab\Models\Lab\TestGeneralDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PatientTestDetail extends Model
{
    use HasFactory;
    protected $table ='patient_test_details';
    protected $fillable =['patient_test_id','remarks','package_id'];

    public function patinet_test(): HasMany
    {
        return $this->hasMany(PatientTest::class, 'patient_test_id', 'id');
    }
    public function patientTest(): BelongsTo
    {
        return $this->belongsTo(PatientTest::class ,'patient_test_id');
    }
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
    // public function testGeneralDetails(): HasMany
    // {
    //     return $this->hasMany(TestGeneralDetail::class);
    // }
    public function testGeneralDetails(): HasMany
    {
        return $this->hasMany(TestGeneralDetail::class, 'patient_test_detail_id', 'id');
    }
}
