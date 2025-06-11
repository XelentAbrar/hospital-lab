<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestWidalDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_test_id',
        'widal_test_id',
        '1_40',
        '1_80',
        '1_160',
        '1_320',
        '1_640',
    ];

    public function widalTest(): BelongsTo
    {
        return $this->belongsTo(WidalTest::class);
    }
}
