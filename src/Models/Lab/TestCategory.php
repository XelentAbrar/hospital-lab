<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_group_id',
        'name',
        'status',
    ];

    public function testGroup(): BelongsTo
    {
        return $this->belongsTo(TestGroup::class);
    }
    public function labTests(): HasMany
    {
        return $this->hasMany(LabTest::class, 'test_category_id');
    }
}
