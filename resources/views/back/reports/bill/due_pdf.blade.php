<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>

    <style>

        @page {padding:0; margin: 10px 0; }
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
            padding: 2px;
            border: 1px solid #000;
font-size: 12px;
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
                <div class="text-center" style="clear:both; overflow: hidden;">
                    <h4 class="m0">Due Bill</h4>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%;" class="text-center">#</th>
                            <th style="width: 10%;" class="text-center">ID<br>Client</th>
                            <th style="width: 5%;" class="text-center">Mobile</th>
                            <th style="width: 15%;" class="text-center">Address</th>
                            <th style="width: 5%;" class="text-center">Package</th>
                            <th style="width: 10%;" class="text-center">Zone</th>
                            <th style="width: 5%;" class="text-center">Commitment Date</th>
                            <th style="width: 5%;" class="text-center">Amount</th>
                            <th style="width: 15%;" class="text-center">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                    $total_payable = 0;
                    @endphp
                    @foreach($due_bills as $key=>$data)
                        <tr>
                            <td style="font-size: 12px;" class="text-center">{{ $key+1 }}</td>
                            <td style="font-size: 12px;">{{ $data->client->client_id }}<br>{{ $data->client->client_name }}</td>
                            <td style="font-size: 12px;">{{ $data->client->cell_no }}</td>
                            <td style="font-size: 12px;" class="text-left">{{ $data->client->address }}</td>
                            <td style="font-size: 12px;" class="text-center">{{ $data->client->package->package_name }}</td>
                            <td style="font-size: 12px;" class="text-left">{{ $data->client->zone->zone_name_en }}</td>
                            <td style="font-size: 12px;" class="text-center">{{ $data->client->termination_date=='0000-00-00'?'':$data->client->termination_date }}</td>
                            <td style="font-size: 12px;" class="text-right"> {{ number_format( $data->payable ,2) }}</td>
                            <td style="font-size: 12px;" class="text-left">{{ $data->client->note }}</td>
                        </tr>
                        @php
                        $total_payable += $data->payable;
                        @endphp
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="7" class="text-center">Total</th>
                        <th class="text-right">{{ number_format( $total_payable,2) }}</th>
                        <th class="text-right"></th>
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