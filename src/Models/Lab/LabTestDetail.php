<?php

namespace App\Models\Lab;

use XelentAbrar\HospitalLab\Models\Lab\LabTest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabTestDetail extends Model
{
    use HasFactory;
    protected $table ='lab_test_details';
    protected $fillable = ['test_report', 'lab_test_id'];

    public function labTest(): BelongsTo
    {
        return $this->belongsTo(LabTest::class);
    }
}
