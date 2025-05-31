<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayableBills extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function scopeCompany($query)
    {
        return $query->where('company_id', \Settings::company_id());
    }
    public function account()
    {
        return $this->belongsTo(AccountHeads::class,"account_id","id");
    }
}
