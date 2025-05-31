@extends('layouts.app')

@section('title', 'Bill Generate Report')

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


                                        <div id="collapseB2" class="" aria-labelledby="headingBTwo">
                                            <div class="card-body" style="padding: 6px 10px;">
                                                <form  method="post" novalidate action="{{ route("bill-generate-report") }}">
                                                    {{csrf_field()}}
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Zone</label>
                                                                <select class="form-control select2" name="zone_id" id="zone_id">
                                                                    <option value="0">All Zone</option>
                                                                    @foreach($zones as $row)
                                                                        <option value="{{ $row->id }}" @if($zone_id==$row->id) selected @endif>{{ $row->zone_name_en }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">

                                                                <label for="month">Month</label>
                                                                <select class="form-control select2" name="month" id="month">

                                                                    <option value="1"  @if(1==$month) selected @endif >Jan</option>
                                                                    <option value="2"  @if(2==$month) selected @endif >Feb</option>
                                                                    <option value="3"  @if(3==$month) selected @endif >Mar</option>
                                                                    <option value="4"  @if(4==$month) selected @endif >Apr</option>
                                                                    <option value="5"  @if(5==$month) selected @endif >May</option>
                                                                    <option value="6"  @if(6==$month) selected @endif >Jun</option>
                                                                    <option value="7"  @if(7==$month) selected @endif >Jul</option>
                                                                    <option value="8"  @if(8==$month) selected @endif >Aug</option>
                                                                    <option value="9"  @if(9==$month) selected @endif >Sep</option>
                                                                    <option value="10"  @if(10==$month) selected @endif >Oct</option>
                                                                    <option value="11"  @if(11==$month) selected @endif >Nov</option>
                                                                    <option value="12"  @if(12==$month) selected @endif >Dec</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">

                                                                <label for="year">Year</label>
                                                                <select class="form-control select2" name="year" id="year">
                                                                    @for($i=2021;$i<=date('Y');$i++)
                                                                        <option value="{{ $i }}"  @if($i==$year) selected @endif >{{ $i }}</option>
                                                                        @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group" >
                                                                <button type="submit" style="margin-top:25px;"  class="btn btn-primary mb-0 search">Search</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>
                                            </div>

                                        </div>




                                        <div class="card inner-card income_show">
                                            <div class="card-body" style="padding: 6px 10px;">
                                               {{--<div class="pull-right" style="clear:both; overflow: hidden;">--}}
                                                   {{--<form id="OPform" action="{{ route("download-due-pdf") }}" method="post" target="_blank">--}}
                                                       {{--@csrf--}}
                                                       {{--<input type="hidden" name="zone_id" value="{{ $zone_id }}" />--}}
                                                       {{--<input type="hidden" name="to_date" class="to_date" />--}}
                                                       {{--<input type="hidden" name="from_date" class="from_date" />--}}
                                                       {{--<input type="hidden" name="creditor_id" class="creditor_id" />--}}
                                                       {{--<input type="submit" class="btn grey btn-primary" name="operation" value="Download PDF">--}}
                                                       {{--<input type="submit" class="btn grey btn-success" name="operation" value="Print">--}}
                                                   {{--</form>--}}
                                               {{--</div>--}}
                                                <div class="col-md-12 text-center"style="clear:both; overflow: hidden;">
                                                    <h2>Bill Generate Report</h2>
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
                                                            <th style="width: 5%;" class="text-center">#</th>
                                                            <th style="width: 10%;" class="text-center">ID/Client/Mobile</th>
                                                            <th style="width: 15%;" class="text-center">Address</th>
                                                            <th style="width: 5%;" class="text-center">Package</th>
                                                            <th style="width: 10%;" class="text-center">Zone</th>
                                                            <th style="width: 5%;" class="text-center">Create Date</th>
                                                            <th style="width: 15%;" class="text-center">Note</th>
                                                            <th style="width: 5%;" class="text-center">Amount</th>
                                                            <th style="width: 5%;" class="text-center">Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                    $total_payable = 0;
                                                    @endphp
                                                    @inject('bills', 'App\Services\BillService')
                                                        @foreach($due_bills as $key=>$data)
                                                            <tr>
                                                                <td class="text-center">{{ $key+1 }}</td>
                                                                <td><span style="font-size: 12px;">{{ $data->client->client_id }}</span> <br>{{ $data->client->client_name }}<br>{{ $data->client->cell_no }}</td>

                                                                <td class="text-left">{{ $data->client->address }}</td>
                                                                <td class="text-center">{{ $data->client->package->package_name }}</td>
                                                                <td class="text-left">{{ $data->client->zone ? $data->client->zone->zone_name_en: "" }}</td>
                                                                <td class="text-center">{{ date('d/m/y',strtotime($data->bill_date)) }}</td>
                                                                <td class="text-left">{{ $bills::maxNote($data->client_id,$month,$year)->note }} </td>
                                                                <td class="text-right"> {{ number_format( $data->payable ,2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            @php
                                                            $total_payable += $data->payable;
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <th colspan="7" class="text-right">Total</th>
                                                        <th class="text-right">{{ number_format( $total_payable,2) }}</th>
                                                        <th class="text-right"></th>
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