<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatbClients extends Model
{
    protected $guarded = [];

    public static function scopeCompany($query)
    {
        return $query->where('company_id', \Settings::company_id());
    }
    public function package()
    {
        return $this->belongsTo(CatvPackages::class,"package_id","id");
    }
    public function zone()
    {
        return $this->belongsTo(Zones::class,"zone_id1","id");
    }
}
