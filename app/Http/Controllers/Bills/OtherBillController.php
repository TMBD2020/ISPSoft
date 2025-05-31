<?php

namespace App\Http\Controllers\Bills;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OtherBillController extends Controller
{
    public function generate_other_bill(){

        return view("back.bill.generate_other_bill");
    }
}
