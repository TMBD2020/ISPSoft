<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreProducts extends Model
{
    protected $guarded = [];
    public static function scopeCompany($query)
    {
        return $query->where('company_id', \Settings::company_id());
    }
    public function brands()
    {
        return $this->belongsTo(ProductBrands::class,"brand_id","id");
    }
}
