<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBrands extends Model
{
    protected $guarded = [];
    public static function scopeCompany($query)
    {
        return $query->where('company_id', \Settings::company_id());
    }
}
