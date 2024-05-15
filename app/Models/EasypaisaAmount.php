<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EasypaisaAmount extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'current_amount',
        'deducted_amount',
        'remaining_amount',
        'branch',
        'use_for',
        'which_table',
        'status',
        'remarks',
        'operator',

    ];

    public function locations()
{
    return $this->belongsTo(HeadLocation::class , "branch");
}
}
