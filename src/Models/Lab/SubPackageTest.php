<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class SubPackageTest extends Model
{
    use HasFactory;
    protected $table ='sub_package_tests';
    protected $fillable = [
        'sub_package_id',
        'lab_test_id',
    ];
    public function labTest(): BelongsTo
    {
        return $this->belongsTo(LabTest::class, 'lab_test_id');
    }
    public function categoryTest(): BelongsTo
    {
        return $this->belongsTo(TestCategory::class);
    }

}
