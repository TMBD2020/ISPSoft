@extends('layouts.app')

@section('title', 'Connection Details')

@section('content')

        <!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title" id="tabOption">@yield("title")</h3>
            </div>
            <div class="content-header-right col-md-8 col-12">


            </div>
        </div>
        <div class="content-body"><!-- Zero configuration table -->
            <section id="configuration">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">

                                    <div class="table-responsive">
                                        <table id="datalist" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>Client ID</th>
                                                <th>Client Name</th>
                                                <th>Zone</th>
                                                <th>IP</th>
                                                <th>EPON</th>
                                                <th>ONU MAC</th>
                                                <th>Client MAC</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($clients as $client)
                                                <tr>
                                                    <td>{{ $client->client_id }}</td>
                                                    <td>{{ $client->client_name }}</td>
                                                    <td>{{ $client->zone_name_en }}</td>
                                                    <td>{{ $client->ip_address }}</td>
                                                    <td>{{ $client->gpon_mac_address }}</td>
                                                    <td></td>
                                                    <td>{{ $client->mac_address }}</td>
                                                    <td>
                                                        @if($client->connection_mode==1)
                                                        Active
                                                        @elseif($client->connection_mode==0)
                                                        Inactive
                                                        @elseif($client->connection_mode==2)
                                                        Locked
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default "> <i class="ft-cloud-off"></i> Disconnect</button>
                                                            <button type="button" class="btn btn-info ">Info</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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


<style>
    form .row{
        margin-top:10px;
    }
</style>
@endsection
@section("page_script")
    <script type="text/javascript">
        $(document).ready(function () {

            $('#datalist').DataTable
            ({

                "bProcessing": true,
                "responsive": true,
                "aaSorting": [[0, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
//                    "targets":[2,3,4,5],
//                    "orderable": false
                },{
//                    "targets":[0,2,3,4,5],
//                    className: "text-center"
                } ]
            });


        });
    </script>

@endsection
