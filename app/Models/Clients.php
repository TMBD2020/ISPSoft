<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Clients extends Model
{
    protected $guarded = [];

    public static function scopeCompany($query)
    {
        return $query->where('company_id', \Settings::company_id());
    }
    public function package()
    {
        return $this->belongsTo(Packages::class,"package_id");
    }
    public function zone()
    {
        return $this->belongsTo(Zones::class,"zone_id","id");
    }
    public function pop()
    {
        return $this->belongsTo(Pops::class,"pop_id","id");
    }
    public function node()
    {
        return $this->belongsTo(Nodes::class,"node_id","id");
    }
    public function box()
    {
        return $this->belongsTo(Boxes::class,"box_id","id");
    }

}
