<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use DB;
class Bills extends Model
{
    protected $guarded = [];
    public static function scopeCompany($query)
    {
        return $query->where('company_id', \Settings::company_id());
    }

    public function client()
    {
        return $this->belongsTo(Clients::class,"client_id","auth_id");
    }

    public function client_cat()
    {
        return $this->belongsTo(CatbClients::class,"client_id","auth_id");
    }

    public function employee()
    {
        return $this->belongsTo(Employees::class,"receive_by","auth_id");
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethods::class,"payment_method_id","id");
    }
    
    public function admin()
    {
        return $this->belongsTo(User::class,"receive_by","id");
    }
     public function package()
    {
        return $this->belongsTo(Packages::class,"package_id","id");
    }


}
