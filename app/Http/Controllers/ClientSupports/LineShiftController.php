<?php

namespace App\Http\Controllers\ClientSupports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\Boxes;
use App\Models\Clients;
use App\Models\Nodes;
use App\Models\Tickets;
use App\Models\Zones;

class LineShiftController extends Controller
{
    protected $line_shifts = "line_shifts";

    public function index()
    {
         $zones = Zones::where(["company_id"=>\Settings::company_id(),"zone_type"=>1])->get();
        $nodes = Nodes::where(["company_id"=>\Settings::company_id()])->get();
        $boxes = Boxes::where(["company_id"=>\Settings::company_id()])->get();
        $clients = Clients::where(["company_id"=>\Settings::company_id()])->get();
        return view("back.client_support.line_shift", compact("clients","zones","nodes","boxes"));
    }


    public function save_line_shift(Request $request)
    {
        $data = $ticket = array();
        $data["company_id"]     = \Settings::company_id();
        $ticket["ticket_no"]    = date("d").$request->ref_client_id.date("is");
        $data["ref_client_id"]  = $request->ref_client_id;
        $data["old_address"]    = $request->old_address;
        $data["old_zone_id"]    = $request->old_zone_id;
        $data["old_node_id"]    = $request->old_node_id;
        $data["old_box_id"]     = $request->old_box_id;
        $data["new_address"]    = $request->new_address;
        $data["new_zone_id"]    = $request->new_zone_id;
        $data["new_node_id"]    = $request->new_node_id;
        $data["new_box_id"]     = $request->new_box_id;
        $data["contact_no"]     = $request->contact_no;
        $data["shift_charge"]   = $request->shift_charge>0 ? $request->shift_charge : 0;
        $data["shift_status"]   = 0;
        $data["note"]           = $request->note;
        $data["ref_ticket_no"]  = $ticket["ticket_no"];
        $data["created_by"]     = Auth::user()->id;
        $data["shift_date"]     = date("Y-m-d", strtotime($request->shift_date));
        $data["created_at"]     = date("Y-m-d H:i:s");
        $data["updated_at"]     = date("Y-m-d H:i:s");

        $result = DB::table($this->line_shifts)->insert($data);
        $line_shift_id = DB::getPdo()->lastInsertId();

        $new_zone = Zones::find($data["new_zone_id"]);
        $new_node = Nodes::find($data["new_zone_id"]);
        $new_box  = Boxes::find($data["new_zone_id"]);

        $complain  = "";
        if($new_zone){
            $complain .= "New zone: ".$new_zone->zone_name_en."<br>";
        }
        if($new_node){
            $complain .= "New node: ".$new_node->node_name."<br>";
        }
        if($new_box){
            $complain .= "New box: ".$new_box->box_name."<br>";
        }
        $complain .= "New address: ".$data["new_address"];

        $ticket["company_id"]          = \Settings::company_id();
        $ticket["ref_client_id"]          = $request->ref_client_id;
        $ticket["ref_department_id"]      = 0;
        $ticket["subject"]                = "Line Shift at ". date("d/m/Y", strtotime($request->shift_date));
        $ticket["complain"]               = $complain;
        $ticket["opened_by"]              = Auth::user()->id;
        $ticket["ticket_type"]            = "line_shift";
        $ticket["ticket_datetime"]        = date("Y-m-d H:i:s");
        $ticket["created_at"]             = date("Y-m-d H:i:s");

        if($result){
           $ticket_insert =Tickets::create($ticket);
            if(!$ticket_insert){
                DB::table($this->line_shifts)->whereId($line_shift_id)->delete();
                echo 0;
            }else{
                echo $ticket["ticket_no"];
            }
        }else{
            echo 0;
        }
    }
}
