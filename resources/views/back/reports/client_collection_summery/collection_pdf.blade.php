<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>

    <style>
        table{
            width: 100%;
            clear: both;
        }
        .table{
            width: 100%;
            border-collapse: collapse;
            clear: both;
        }
        .table td,.table th{
            padding: 5px;
            border: 1px solid #000;
            font-size: 16px;
        }
        .table th{
            font-weight: bold;
        }
        .table thead, .table tfoot{
            background: #ddd;
        }
        .text-center{
            text-align: center;
        }
        .text-left{
            text-align: left;
        }
        .text-right{
            text-align: right;
        }
        .m0{
            margin: 0;
        }
        #company {
            overflow: hidden;
            margin-bottom: 15px;
        }
        .w-33 {
            width: 33%; float: left;
        }
        h2,h5{
            margin:0;
        }
        @page{
            margin:0
        }
    </style>
    @if($operation=="Print")
    <script>
        function PrintWindow() {
            window.print();
            CheckWindowState();
        }

        function CheckWindowState()    {
            if(document.readyState=="complete") {
                window.close();
            } else {
                setTimeout("CheckWindowState()", 1000)
            }
        }
        PrintWindow();

    </script>
    @endif
</head>
<body>
    <table>
        <tbody>
        <tr>
            <td colspan="2">

               {{--<table>--}}
                   {{--<tr>--}}
                       {{--<td >--}}
                           {{--<div style="margin-bottom: 15px">--}}
                               {{--<div  class="text-center w-33">--}}
                                   {{--<img src="{{ Settings::settings()['company_logo'] }}" width="60">--}}
                               {{--</div>--}}
                               {{--<div class="text-center w-33">--}}
                                   {{--<h2 class="m0">{{ Settings::settings()['company_name']  }}</h2>--}}
                                   {{--<h5 class="m0">{{ Settings::settings()["contact_number"]  }}</h5>--}}
                                   {{--<h5 class="m0">{{ Settings::settings()["company_email"]  }}</h5>--}}
                                   {{--<h5 class="m0">{{ Settings::settings()["contact_address"]  }}</h5>--}}
                               {{--</div>--}}
                           {{--</div>--}}
                       {{--</td>--}}
                   {{--</tr>--}}
               {{--</table>--}}
                <div class="text-center" style="clear: both; overflow: hidden;">
                    <h2>Client Collection Summery</h2>
                    <h5>For the period from <big class="date_from">{{ $all_collection["dates"]["date_from"] }}</big> to <big class="date_to">{{ $all_collection["dates"]["date_to"] }}</big></h5>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%" class="text-center">Sl</th>
                            <th style="width: 30%" class="text-center">Zone</th>
                            <th style="width: 20%" class="text-center">Total Collection</th>
                            <th style="width: 20%" class="text-center">Total Signup Charge</th>
                            <th style="width: 20%" class="text-center">Net Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                    $total_collection=0;
                    $total_signup=0;
                    $total_net=0;
                    @endphp
                    @foreach($all_collection["collection"] as $key=>$collection)
                        @php
                        $total_collection+=$collection->collections;
                        $total_signup += $collection->conn_fee;
                        $total = $collection->collections+ $collection->conn_fee;
                        $total_net+=$total;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            @foreach($zones as $zone)
                                @if($zone->id == $collection->zone_id)
                                    <td class="text-left">{{ $zone->zone_name_en }}</td>
                                @endif
                            @endforeach
                            <td class="text-right">{{ number_format($collection->collections,2) }}</td>
                            <td class="text-right">{{ number_format($collection->conn_fee,2) }}</td>
                            <td class="text-right">{{ number_format($total,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th class="text-right" colspan="2">Total</th>
                        <th class="text-right total_discount">{{ number_format($total_collection,2) }}</th>
                        <th class="text-right total_collection">{{ number_format($total_signup,2) }}</th>
                        <th class="text-right total_collection">{{ number_format($total_net,2) }}</th>
                    </tr>
                    </tfoot>

                </table>
            </td>
        </tr>
        </tbody>
        <tfoot>
            <tr>
                <td style="font-size: 11px;">Printed By: {{ Auth::user()->name }}</td>
                <td style="font-size: 11px;" class="text-right">Date: {{ date("d/m/Y h:i a") }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>