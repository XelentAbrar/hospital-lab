<?php

namespace App\Models\Lab;

use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherAuditDetail;
use App\Models\User;
use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use App\Models\OPD\Patient;
use App\Models\HRMS\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_date',
        'lab_no',
        'test_nature',
        'test_type',
        'status',
        'referred_by',
        'date_sample',
        'date_report_ready',
        'date_report_verification',
        'date_report_collection',
        'lab_incharge',
        'doctor_id',
        'patient_id',
        "is_printed",
        "patient_name",
        "patient_cnic",
        "patient_dob",
        "patient_age",
        "patient_gender",
        "patient_relation_name",
        "patient_relation_type",
        "patient_address",
        "patient_city_id",
        "patient_state_id",
        "patient_country_id",
        "patient_phone",
        "patient_email",
        "patient_blood_group",
        "patient_rh_factor",
        "specimen",
        "free_test",
        "discount_type",
        "discount_value",
        "total_amount",
        "test_category_id",
        "careoff_id",
        "donor_fee",
        "zf_id",
        "file_no",
        "welfare",
        "zf_fee",
        "package_id",
        "remarks",
        "chart_no",
        'cancel',
        'lab_serial','test_time','created_by','updated_by'
    ];
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function testGeneralDetails(): BelongsTo
    {
        return $this->belongsTo(TestGeneralDetail::class , 'id','patient_test_id');
    }
    public function patientTestDetails(): HasMany
    {
        return $this->hasMany(PatientTestDetail::class);
    }

    public function testWidalDetails(): HasMany
    {
        return $this->hasMany(TestWidalDetail::class);
    }

    public function testCrossMatchDetails(): HasOne
    {
        return $this->hasOne(TestCrossMatchDetail::class);
    }

    public function careoff(): BelongsTo
    {
        return $this->belongsTo(Donor::class, 'careoff_id');
    }

    public function zf(): BelongsTo
    {
        return $this->belongsTo(Donor::class, 'zf_id');
    }

    public function testCategory(): BelongsTo
    {
        return $this->belongsTo(TestCategory::class, 'test_category_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'doctor_id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
    public function voucherAuditDetails()
    {
        return $this->hasMany(VoucherAuditDetail::class, 'transaction_no', 'id');
    }

}
