<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'lab_test_id',
    ];
    public function categoryTest(): BelongsTo
    {
        return $this->belongsTo(TestCategory::class);
    }
    public function labTest(): BelongsTo
    {
        return $this->belongsTo(LabTest::class);
    }

}
