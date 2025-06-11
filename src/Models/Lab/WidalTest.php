<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidalTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'charges',
        'status',
    ];
}
