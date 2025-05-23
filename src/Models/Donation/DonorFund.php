<?php

namespace XelentAbrar\HospitalDonation\Models\Donation;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DonorFund extends Model
{
    use HasFactory;
    protected $fillable = [
        'donor_id',
        'charges',
        'account_on','donor_serial','donor_time','created_by','updated_by','is_printed','narration','donor_date'
    ];
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }
}
