<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IspResellerBills extends Model
{
    protected $guarded = [];
    public static function scopeCompany($query)
    {
        return $query->where('company_id', \Settings::company_id());
    }

    public function client()
    {
        return $this->belongsTo(IspResellers::class,"client_id","auth_id");
    }
}
