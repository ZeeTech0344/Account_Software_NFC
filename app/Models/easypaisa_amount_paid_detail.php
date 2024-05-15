<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class easypaisa_amount_paid_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        "easypaisa_amount_date",
        "employee_type",
        "locations_id",
        "employee_others",
        "purpose",
        "advance_payment_month",
        "paid_amount",
        "remarks",
        "easypaisa_amount_id",
        "return",
        "operator"

    ];

    public function Employees()
    {
    return $this->belongsTo(Employee::class , "employee_others");
    }

    public function EasypaisaDetail()
    {
    return $this->belongsTo(EasypaisaAmount::class , "easypaisa_amount_id");
    }

    public function branches()
    {
    return $this->belongsTo(HeadLocation::class , "locations_id");
    }

   
}
