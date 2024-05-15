<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Closing extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'head',
        'location',
        'amount',
        'created_at',
    ];

    function locations(){
        return $this->belongsTo(HeadLocation::class, "location");
    }

    function heads(){
        return $this->belongsTo(Head::class, "head");
    }
}
