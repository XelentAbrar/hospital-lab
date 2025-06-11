<?php

namespace App\Models\Lab;

use XelentAbrar\HospitalLab\Models\Lab\LabTest;
use XelentAbrar\HospitalLab\Models\Lab\LabTestDetail;
use XelentAbrar\HospitalLab\Models\Lab\PatientTest;
use XelentAbrar\HospitalLab\Models\Lab\PatientTestDetail;
use XelentAbrar\HospitalLab\Models\Lab\SubPackage;
use XelentAbrar\HospitalLab\Models\Lab\TestCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestGeneralDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_test_id',
        'lab_test_id',
        'result',
        'result_2',
        'description','test_category_id','sub_package_id','patient_test_detail_id','lab_test_detail_id','lab_serial'
    ];

    public function labTest(): BelongsTo
    {
        return $this->belongsTo(LabTest::class);
    }
    public function labTestDetail(): BelongsTo
    {
        return $this->belongsTo(LabTestDetail::class);
    }
    public function testCategory(): BelongsTo
    {
        return $this->belongsTo(TestCategory::class, 'test_category_id');
    }
    public function subPackage(): BelongsTo
    {
        return $this->belongsTo(SubPackage::class, 'sub_package_id');
    }
    public function patientTestDetails(): BelongsTo
    {
        return $this->belongsTo(PatientTestDetail::class);
    }
    public function patientTest()
    {
        return $this->belongsTo(PatientTest::class);
    }

}
