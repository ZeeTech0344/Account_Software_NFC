<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnAmount extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'employees_id',
        'from_easypaisa_accounts_id',
        'to_easypaisa_accounts_id',
        'previous_amount',
        'return_amount',
        'amount_after_deduction',
    ];

     public function test(){
       return $this->belongsTo(Employee::class ,"employees_id");
    }
}
