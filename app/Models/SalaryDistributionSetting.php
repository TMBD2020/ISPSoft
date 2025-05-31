<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryDistributionSetting extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "salary_distribution_setting";
    public static function scopeCompany($query)
    {
        return $query->where('company_id', \Settings::company_id());
    }
}
