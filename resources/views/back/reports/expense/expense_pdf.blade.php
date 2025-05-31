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
                    <h2>Expense</h2>
                    <h5>For the period from <big class="date_from">{{ $all_expenses["dates"]["date_from"] }}</big> to <big class="date_to">{{ $all_expenses["dates"]["date_to"] }}</big></h5>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%" class="text-center">SL</th>
                            <th style="width: 5%">Date</th>
                            <th style="width: 20%">Expense Head</th>
                            <th style="width: 15%">Expense By</th>
                            <th style="width: 15%">Approved By</th>
                            <th style="width: 10%">Amount</th>
                            <th style="width: 30%">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                    $total_expense=0;
                    @endphp
                    @foreach($all_expenses["expense"] as $key=>$expense)
                        @php
                        $total_expense+=$expense["expense_amount"];
                        @endphp
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td>{{ $expense["expense_date"] }}</td>
                            <td>{{ $expense["expense_name"] }}</td>
                            <td>{{ $expense["expense_by"] }}</td>
                            <td>{{ $expense["approved_by"] }}</td>
                            <td class="text-right">{{ number_format($expense["expense_amount"],2) }}</td>
                            <td>{{ $expense["expense_note"] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="5" class="text-center">Total</th>
                        <th class="text-right">{{ number_format( $total_expense,2) }}</th>
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