<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pending extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "employee_id",
        "branch_id",
        "amount",
        "status",
        "paid_date",
        "account_id",
        "account_name"
    ];

    public function getEmployee()
    {
    return $this->belongsTo(Employee::class , "employee_id");
    }

    public function getBranch()
    {
    return $this->belongsTo(HeadLocation::class , "branch_id");
    }


}
