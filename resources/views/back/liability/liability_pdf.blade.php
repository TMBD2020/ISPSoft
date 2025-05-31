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

                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-left">
                                <b>Creditor Name: </b> <u style="font-size: 20px;">{{ $person->name }}</u>
                                <br>
                                <b>Date: </b> <u  style="font-size: 20px;"><i class="fromDate">{{ date("d/m/Y", strtotime($from_date))  }}</i>&nbsp;to&nbsp;<i class="toDate">{{ date("d/m/Y", strtotime($to_date)) }}</i></u>
                            </th>
                        </tr>
                        <tr>
                            <th class='text-center'>Date</th>
                            <th class='text-right'>Receive</th>
                            <th class='text-right'>Payment</th>
                            <th class='text-right'>Due</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                    $rcv = $pay = $total_due= $due  = $i =0;
                    @endphp
                    @foreach($result as $row)
                        @php
                        $i++;
                        $rcv += $row->rcv_amount;
                        $pay += $row->pay_amount;
                        if(count($result)==$i){
                            $due = $row->current_due;
                        }else{
                        }
                        $total_due += $due;
                        @endphp

                        <tr>
                            <td class='text-center'> {{ date("d/m/Y", strtotime($row->receive_date)) }} </td>
                            <td class='text-right'> {{ $row->rcv_amount }} </td>
                            <td class='text-right'> {{ $row->pay_amount }} </td>
                            <td class='text-right'> {{ number_format($due,2,".",",") }} </td>
                        </tr>

                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class='text-center'>Total</th>
                            <th class='text-right'> {{ number_format($rcv,2,".",",") }}</th>
                            <th class='text-right'> {{ number_format($pay,2,".",",") }}</th>
                            <th class='text-right'> {{ number_format($total_due,2,".",",") }}</th>
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