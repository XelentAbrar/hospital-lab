<?php

namespace App\Models\Lab;

use App\Models\User;
use XelentAbrar\HospitalLab\Models\Lab\LabTestDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_category_id',
        'name',
        'description',
        'lab_test_type',
        'lab_type',
        'price',
        'sample_required',
        'reporting_time',
        'min_value',
        'max_value',
        'value_range',
        'unit_id',
        'coa_id',
        'status','created_by','updated_by'
    ];
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function testCategory(): BelongsTo
    {
        return $this->belongsTo(TestCategory::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
    public function details(): HasMany
    {
        return $this->hasMany(LabTestDetail::class);
    }
    public function testGeneralDetails()
    {
        return $this->hasMany(TestGeneralDetail::class);
    }

}
