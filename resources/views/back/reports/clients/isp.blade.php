@extends('layouts.app')

@section('title', 'ISP Client Report')

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
                                                <form  method="post" novalidate action="{{ route("isp-client-list") }}">
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="pdf" value="2" />
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
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select class="form-control select2" name="status" id="status">
                                                                    <option value="0" @if($status==0) selected @endif>All</option>
                                                                    <option value="1" @if($status==1) selected @endif>Active</option>
                                                                    <option value="2" @if($status==2) selected @endif>Inactive</option>
                                                                    <option value="3" @if($status==3) selected @endif>Locked</option>

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
                                                @if(count($clients)>0)
                                               <div class="pull-right" style="clear:both; overflow: hidden;">
                                                   <form id="OPform" action="{{ route("download-isp-client-pdf") }}" method="post" target="_blank">
                                                       @csrf
                                                       <input type="hidden" name="pdf" value="1" />
                                                       <input type="hidden" name="zone_id" value="{{ $zone_id }}" />
                                                        <input type="hidden" name="status" value="{{ $status }}" />
                                                       <input type="hidden" name="to_date" class="to_date" />
                                                       <input type="hidden" name="from_date" class="from_date" />
                                                       <input type="hidden" name="creditor_id" class="creditor_id" />
                                                       <input type="submit" class="btn grey btn-primary btn-sm" name="operation" value="Download PDF">
                                                   </form>
                                               </div>
                                                    <br>
                                                    <br>
                                                @endif

                                                <style>
                                                    .rptTbl td,.rptTbl th{
                                                        border:1px solid #000;
                                                        padding: 2px;
                                                        color: #000;
                                                        font-size: 12px;
                                                    }
                                                </style>
                                                    @if($clients)
                                                <table class="rptTbl">

                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5%;" class="text-center">#</th>
                                                            <th style="width: 8%;" class="text-center">ID</th>
                                                            <th style="width: 10%;" class="text-center">Name</th>
                                                            <th style="width: 10%;" class="text-center">Mobile</th>
                                                            <th style="width: 15%;" class="text-center">Address</th>
                                                            <th style="width: 5%;" class="text-center">IP/MAC/GPON</th>
                                                            <th style="width: 5%;" class="text-center">Package</th>
                                                            <th style="width: 10%;" class="text-center">POP</th>
                                                            <th style="width: 10%;" class="text-center">Zone</th>
                                                            <th style="width: 10%;" class="text-center">Node</th>
                                                            <th style="width: 10%;" class="text-center">Box</th>
                                                            <th style="width: 10%;" class="text-center">Status</th>
                                                            <th style="width: 10%;" class="text-center">Join</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(count($clients)>0)
                                                        @foreach($clients as $key=>$data)
                                                            <tr>
                                                                <td class="text-center">{{ $key+1 }}</td>
                                                                <td class="text-center"> {{ $data->client_id }}</td>
                                                                <td> {{ $data->client_name }}</td>
                                                                <td class="text-center"> {{ $data->cell_no }}</td>
                                                                <td>{{ $data->address }}</td>
                                                                <td><u>IP:</u> {{ $data->ip_address }} <br>
                                                                    @if($data->mac_address)<u>MAC:</u>{{ $data->mac_address }}<br>@endif
                                                                    @if($data->gpon_mac_address)<u>GPON:</u> {{ $data->gpon_mac_address }}@endif</td>
                                                                <td>{{ $data->package->package_name }}</td>
                                                                <td>{{ $data->pop ? $data->pop->pop_name: "" }}</td>
                                                                <td>{{ $data->zone ? $data->zone->zone_name_en: "" }}</td>
                                                                <td>{{ $data->node ? $data->node->node_name: "" }}</td>
                                                                <td>{{ $data->box ? $data->box->box_name: "" }}</td>
                                                                <td>
                                                                @if($data->connection_mode == 1)
                                                                    Active
                                                                    @elseif($data->connection_mode == 2)
                                                                    Locked
                                                                    @else
                                                                    Inactive
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">@if($data->join_date){{ date('d/m/Y',strtotime($data->join_date)) }}@endif</td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="13" class="text-center">No data found!</td>
                                                        </tr>
                                                    @endif
                                                    </tbody>
                                                </table>
                                                @endif
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