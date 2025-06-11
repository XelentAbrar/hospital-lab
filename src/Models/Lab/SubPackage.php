<?php

namespace App\Models\Lab;

use XelentAbrar\HospitalLab\Models\Lab\LabTest;
use XelentAbrar\HospitalLab\Models\Lab\SubPackageTest;
use XelentAbrar\HospitalLab\Models\Lab\PatientTestDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubPackage extends Model
{
    use HasFactory;
    protected $table ='sub_packages';
    protected $fillable = [
        'name',
        'status',
        'price',
    ];

    public function packageTests(): HasMany
    {
        return $this->hasMany(SubPackageTest::class,'sub_package_id','id');
    }
    public function patientTestDetails(): HasMany
    {
        return $this->hasMany(PatientTestDetail::class);
    }
    public function labTests()
    {
        return $this->hasManyThrough(LabTest::class, SubPackageTest::class, 'sub_package_id', 'id', 'id', 'lab_test_id');
    }
}
