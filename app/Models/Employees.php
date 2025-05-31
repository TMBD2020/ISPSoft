<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    protected $guarded = [];
    public static function scopeCompany($query)
    {
        return $query->where('company_id', \Settings::company_id());
    }
    public function designation()
    {
        return $this->belongsTo(Designations::class,"emp_designation_id","id");
    }
    public function department()
    {
        return $this->belongsTo(Departments::class,"emp_department_id","id");
    }
}
