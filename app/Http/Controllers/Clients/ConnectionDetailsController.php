<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class ConnectionDetailsController extends Controller
{
    protected $clients = "clients";
    protected $zones = "zones";

    public function index(){
        $clients = DB::table($this->clients)
            ->leftJoin($this->zones,"zones.id","zone_id")
            ->orderBy("clients.id","desc")->get();
        return view("back.client_conn_details.connection_details",
            compact("clients")
        );
    }
}
