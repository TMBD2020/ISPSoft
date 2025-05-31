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

               <table>
                   <tr>
                       <td >
                           <div style="margin-bottom: 15px">
                               <div  class="text-center w-33">
                                   <img src="{{ Settings::settings()['company_logo'] }}" width="60">
                               </div>
                               <div class="text-center w-33">
                                   <h2 class="m0">{{ Settings::settings()['company_name']  }}</h2>
                                   <h5 class="m0">{{ Settings::settings()["contact_number"]  }}</h5>
                                   <h5 class="m0">{{ Settings::settings()["company_email"]  }}</h5>
                                   <h5 class="m0">{{ Settings::settings()["contact_address"]  }}</h5>
                               </div>
                           </div>
                       </td>
                   </tr>
               </table>
                <div class="text-center">
                    <h2>Due Bill</h2>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%;" class="text-center">Sl No</th>
                            <th style="width: 10%;" class="text-center">ID/Client</th>
                            <th style="width: 5%;" class="text-center">Mobile</th>
                            <th style="width: 15%;" class="text-center">Address</th>
                            <th style="width: 5%;" class="text-center">Package</th>
                            <th style="width: 10%;" class="text-center">Zone</th>
                            <th style="width: 5%;" class="text-center">Commitment Date</th>
                            <th style="width: 15%;" class="text-center">Note</th>
                            <th style="width: 5%;" class="text-center">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                    $total_payable = 0;
                    @endphp
                    @foreach($due_bills as $key=>$data)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            @foreach($clients as $client)
                                @if($client->id==$data->client_id)
                                    <td>{{ $client->client_id."/".$client->client_name }}</td>
                                    <td>{{ $client->cell_no }}</td>
                                    <td class="text-left">{{ $client->address }}</td>
                                    <td class="text-center">{{ $client->package->package_name }}-Tk{{ $client->package->package_price }}</td>
                                    @foreach($zones as $zone)
                                        @if($zone->id==$client->zone_id)
                                            <td class="text-left">{{ $zone->zone_name_en }}</td>
                                        @endif
                                    @endforeach
                                    <td class="text-center">{{ $client->termination_date=='0000-00-00'?'':$client->termination_date }}</td>
                                    <td class="text-left">{{ $client->note }}</td>
                                @endif

                            @endforeach
                            <td class="text-right"> {{ number_format( $data->payable ,2) }}</td>
                        </tr>
                        @php
                        $total_payable += $data->payable;
                        @endphp
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="8" class="text-center">Total</th>
                        <th class="text-right">{{ number_format( $total_payable,2) }}</th>
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