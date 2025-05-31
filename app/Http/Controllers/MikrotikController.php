<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

use \RouterOS\Client;
use \RouterOS\Query;
use App\Models\Microtiks;

class MikrotikController extends Controller
{


    public function index()
    {
        return view("back.network.list");
    }

    public function save_network(Request $request)
    {
        if ($request->action == 1) {
            $result = Microtiks::create(
                [
                    "company_id" => \Settings::company_id(),
                    "network_name" => $request->network_name,
                    "server_ip" => $request->server_ip,
                    "username" => $request->username,
                    "password" => $request->password,
                    "port" => $request->port,
                    "note" => $request->note,
                    "is_active" => 1
                ]
            );
        } else {
            $data = array(
                "network_name" => $request->network_name,
                "server_ip" => $request->server_ip,
                "port" => $request->port,
                "username" => $request->username,
                "note" => $request->note,
                "updated_at" => date("Y-m-d H:i:s")
            );
            if ($request->password) {
                $data = array_merge($data, array("password" => $request->password));
            }
            $result = Microtiks::query()->whereId($request->id)->update($data);
        }

        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function networkList(Request $request)
    {

        $columns = array(
            0 => 'id',
            1 => 'network_name',
            2 => 'server_ip',
            3 => 'username',
            4 => 'port',
            5 => 'is_active'
        );

        $totalData = Microtiks::query()
            ->where(["company_id" => \Settings::company_id()])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = Microtiks::query()
                ->where(["company_id" => \Settings::company_id()])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts = Microtiks::query()->where('id', 'LIKE', "%{$search}%")
                ->where(["company_id" => \Settings::company_id()])
                ->orWhere('network_name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Microtiks::query()->where('id', 'LIKE', "%{$search}%")
                ->orWhere('network_name', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            $i = $start;
            $j = $start + ($limit > count($posts) ? count($posts) : $limit);
            foreach ($posts as $post) {
                if ($dir == 'desc') {
                    $i++;
                } else {
                    $i = $j--;
                }
                $nestedData = array();

                $nestedData[] = $i;
                $nestedData[] = $post->network_name;
                $nestedData[] = $post->server_ip;
                $nestedData[] = $post->username;
                $nestedData[] = $post->port;
                $nestedData[] = $post->is_active == 1 ? "Yes" : "No";
                $nestedData[] = '<div class="btn-group align-top" role="group">
                    <button id="' . $post->id . '" type="Queue" class="client btn btn-info btn-sm badge">
                    Queue</button>
                    <button id="' . $post->id . '" type="PPPOE" class="client btn btn-success btn-sm badge">
                    PPPOE</button>
                    <button id="' . $post->id . '" class="update btn btn-primary btn-sm badge">
                    Edit</button>
            </div>';
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);

    }


    public function networkUpdate(Request $request)
    {
        $result = Microtiks::query()->where("id", $request->id)->first();
        if ($result) {
            echo json_encode($result);
        } else {
            echo 0;
        }
    }

    public function networkDelete(Request $request)
    {
        $result = DB::table($this->table)->where("id", $request->id)->delete();
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function connect_network(Request $request)
    {
        $server = Microtiks::find($request->id);
        if ($server) {
            if ($request->type == 'Queue') {
                try {
                    $client = new Client([
                        'host' => $server->server_ip,
                        'user' => $server->username,
                        'pass' => $server->password,
                        'port' => (int) $server->port,
                    ]);

                    $query = (new Query('/queue/simple/print'));

                    $response = $client->query($query)->read();

                    if ($response) {
                        $dataTable = [];
                        foreach ($response as $key => $data) {
                            $dataTable[] = [
                                ($key + 1),
                                ($data['name']),
                                ($data['max-limit']),
                                ($data['target'])
                            ];
                        }
                        return response()->json($dataTable);
                    } else {
                        return response()->json([]);
                    }
                } catch (Exception $e) {
                    return response()->json(["message" => $e->getMessage()]);
                }
            } else {
                return response()->json([]);
            }
        } else {
            return response()->json([]);
        }
    }

    public function connect_network_test()
    {
        //ini_set('max_execution_time', 180);
// exit();


        //dd($result);
        $client = new Client([
            'host' => "103.111.227.153",
            'user' => "BillingSoft",
            'pass' => "Billing@Soft@2024",
            'port' => 234,//tsl
            // 'port'  =>  786,//ssl
        ]);

        //dd($client);

        // $query = new Query('/queue/simple/print');
        $query = new Query('/ppp/secret/print');
        $query = new Query('/interface/print');

       

        $response = $client->query($query)->read();

        return response()->json($response);

    }

    public function network_disabled()
    {
        //ini_set('max_execution_time', 180);
// exit();

        //dd($result);
        $client = new Client([
            'host' => "103.111.227.153",
            'user' => "BillingSoft",
            'pass' => "Billing@Soft@2024",
            'port' => 234,//tsl
            // 'port'  =>  786,//ssl
        ]);

        //dd($client);

        /*no=enable,yes=disabled*/

       // $query1 = new Query("/ppp/secret/disable");
        // $query->equal('name', '232423423@akbar');
        // $query->equal('remote-address', '172.3.3.12');
        // $query->equal('service', 'pptp');
       // $query1->where('.id', '*398');
        // $query->where('.id', '*6');
        // $query->where('disabled', 'no');
        // $query->where('.id', '*AAF0');
        // $query->tag(4);
        //  $query = new Query('/ppp/profile/print');
        //  $query = new Query('/ppp/secret/print');
        $query2 = new Query("/ppp/secret/print");
        $query2->where('.id', '*398');

       // $input = $client->query($query1)->read();
        $output = $client->query($query2)->read();

        return response()->json($output);

    }

}
