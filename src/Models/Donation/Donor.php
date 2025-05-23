<?php

namespace XelentAbrar\HospitalDonation\Models\Donation;

use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donor extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'address',
        'type',
        'coa_id',
    ];

    public function funds(): HasMany
    {
        return $this->hasMany(DonorFund::class);
    }

    public function coa(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_id');
    }
}
