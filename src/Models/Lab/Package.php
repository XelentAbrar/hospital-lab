<?php

namespace App\Models\Lab;

use XelentAbrar\HospitalLab\Models\Lab\SubPackage;
use XelentAbrar\HospitalLab\Models\Lab\PackageTest;
use XelentAbrar\HospitalLab\Models\Lab\TestCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'price','test_category_id'
    ];

    public function packageTests(): HasMany
    {
        return $this->hasMany(PackageTest::class);
    }
    public function testCategory(): BelongsTo
    {
        return $this->belongsTo(TestCategory::class);
    }
    public function patientTestDetails(): HasMany
    {
        return $this->hasMany(PatientTestDetail::class);
    }
    public function testCategories()
    {
        return $this->hasManyThrough(TestCategory::class, PackageTest::class, 'package_id', 'id', 'id', 'lab_test_id');
    }
    public function subPackages()
    {
        return $this->hasManyThrough(SubPackage::class, PackageTest::class, 'package_id', 'id', 'id', 'lab_test_id');
    }
}
