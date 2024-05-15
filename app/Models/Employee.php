<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "employee_no",
        "employee_name",
        "employee_post",
        "employee_type",
        "cnic",
        "basic_sallary",
        "employee_branch",
        "employee_status",
        "operator",
    ];

    public function getEmployeeBranch()
    {
    return $this->belongsTo(HeadLocation::class , "employee_branch");
    }

    
    public function salary()
    {
        return $this->hasMany(salary::class, "employee_id", "id");
    }


    public function easypaisa()
    {
        return $this->hasMany(EasypaisaPaidAmount::class, "employee_id", "id");
    }

    public function pendings()
    {
        return $this->hasMany(pending::class, "employee_id", "id");
    }


    public function hbl()
    {
        return $this->hasMany(HblAmounts::class, "employee_id", "id");
    }

    public function locker()
    {
        return $this->hasMany(lockerDetail::class, "employee_id", "id");
    }


    public function getVendorsReserveAmount()
    {
        return $this->hasMany(Vendor::class, "employee_id", "id");
    }

    public function getVendorsPayAmount()
    {
        return $this->hasMany(PayVendorAmount::class, "employee_id", "id");
    }


    



    

  

    // public function test(){
    //    return $this->hasMany(ReturnAmount::class , "employees_id","id");
    // }

    // public function test(){
    //     return $this->hasMany(ReturnAmount::class,"employees_id","id");
    //  }
}
