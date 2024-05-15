<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EasypaisaPaidAmount extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "employee_id",
        "purpose",
        "paid_for_month_date",
        "status",
        "amount",
        "remarks"
    ];

    public function getEmployee()
    {
    return $this->belongsTo(Employee::class , "employee_id");
    }

}
