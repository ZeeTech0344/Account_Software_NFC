<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayVendorAmount extends Model
{
    use HasFactory;

    use HasFactory;


    protected $fillable = [
        "id",
        "employee_id",
        "paid_amount",
        "account_id",
        "account_name"
    ];
    


    public function getEmployee()
    {
    return $this->belongsTo(Employee::class , "employee_id");
    }
}
