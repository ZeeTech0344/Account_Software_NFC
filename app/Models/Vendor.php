<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "employee_id",
        "product_id",
        "branch_id",
        "weight",
        "measurement",
        "rate",
        "total_amount",
        "status",
        "paid_date",
        "account_id",
        "account_name",
    ];

    public function getEmployee()
    {
    return $this->belongsTo(Employee::class ,"employee_id");
    }

    public function getVendors()
    {
    return $this->belongsTo(Employee::class , "product_id");
    }

    public function getBranch()
    {
    return $this->belongsTo(HeadLocation::class , "branch_id");
    }

    

    
}
