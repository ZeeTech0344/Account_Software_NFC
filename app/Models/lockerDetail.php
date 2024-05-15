<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lockerDetail extends Model
{
    use HasFactory;


    protected $fillable = [
        "id",
        "employee_id",
        "purpose",
        "paid_for_month_date",
        "status",
        "amount",
        "remarks",
        "created_at"
    ];
    


    public function getEmployee()
    {
    return $this->belongsTo(Employee::class , "employee_id");
    }

    public function testClosingAmount()
    {
    return $this->hasMany(Closing::class , "created_at","created_at");
    }

}
