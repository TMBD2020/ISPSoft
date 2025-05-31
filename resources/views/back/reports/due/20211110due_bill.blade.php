@extends('layouts.app')

@section('title', 'Due Bill')

@section('content')
    <style>
        h4 span{
            padding-right: 10px;
        }
        .table td,.table th, .table{
            color: #000;
            vertical-align: middle;
        }

    </style>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    <h3 class="content-header-title">@yield("title")</h3>
                </div>
                <div class="content-header-right col-md-8 col-12">

                </div>
            </div>
            <div class="content-body">
                <section id="configuration">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content collapse show">
                                    <div class="card-body card-dashboard">

                                        <div class="card inner-card income_show">
                                            <div class="card-body" style="padding: 6px 10px;">
                                               <div class="pull-right" style="clear:both; overflow: hidden;">
                                                   <form id="OPform" action="{{ route("download-due-pdf") }}" method="post" target="_blank">
                                                       @csrf
                                                       <input type="hidden" name="to_date" class="to_date" />
                                                       <input type="hidden" name="from_date" class="from_date" />
                                                       <input type="hidden" name="creditor_id" class="creditor_id" />
                                                       <input type="submit" class="btn grey btn-primary" name="operation" value="Download PDF">
                                                       <input type="submit" class="btn grey btn-success" name="operation" value="Print">
                                                   </form>
                                               </div>
                                                <div class="col-md-12 text-center"style="clear:both; overflow: hidden;">
                                                    <h2>Due Bill</h2>
                                                </div>

                                                <style>
                                                    .rptTbl td,.rptTbl th{
                                                        border:1px solid #000;
                                                        padding: 2px;
                                                        color: #000;
                                                    }
                                                </style>
                                                <table class="rptTbl">

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
                                                        <th colspan="8" class="text-right">Total</th>
                                                        <th class="text-right">{{ number_format( $total_payable,2) }}</th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Zero configuration table -->
            </div>
        </div>
    </div>
    <!-- END: Content-->

@endsection
@section("page_script")

    <script type="text/javascript">
        $(document).ready(function () {
            $('#datalist').DataTable({
                "lengthChange": false,
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": false,
                columnDefs: [ { orderable: false, targets: [1,2,3] } ]
            });

        });
    </script>

    <style>
        .card.inner-card {
            box-shadow: none !important;
            border: 1px solid  rgba(222, 223, 241,0.3)  !important;
        }
    </style>
@endsection