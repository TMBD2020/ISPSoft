<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientTypes extends Model
{
    public static function scopeCompany($query)
    {
        return $query->where('company_id', \Settings::company_id());
    }
}
