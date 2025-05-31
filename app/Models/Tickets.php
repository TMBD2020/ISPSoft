<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    protected $guarded = [];

    public static function scopeCompany($query)
    {
        return $query->where('company_id', \Settings::company_id());
    }
    public function client()
    {
        return $this->belongsTo(Clients::class,"ref_client_id","auth_id");
    }
     public function department()
    {
        return $this->belongsTo(Departments::class,"ref_department_id","id");
    }
}
