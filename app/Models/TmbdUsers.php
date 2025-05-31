<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
class TmbdUsers extends Model
{
    protected $guarded = [];


    public function admin()
    {
        return $this->belongsTo(User::class,"id","company_id");
    }
}
