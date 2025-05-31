@extends('layouts.app')

@section('title', 'Network Station')

@section('content')


        <!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title">@yield("title")</h3>
            </div>
            <div class="content-header-right col-md-8 col-12">

                <ul class="nav nav-tabs float-md-right">
                    <li class="nav-item">
                        <a class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="DataList" href="#DataList" aria-expanded="true">Show @yield("title")</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link addnew" id="base-tab2" data-toggle="tab" aria-controls="operation" href="#operation" aria-expanded="false">Add New</a>
                    </li>
                    <li class="nav-item" style="display: none;">
                        <a class="nav-link" id="base-tab3" data-toggle="tab" aria-controls="Queue" href="#Queue" aria-expanded="false">Queue</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="content-body"><!-- Zero configuration table -->
            <section id="configuration">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">

                                    <div class="tab-content pt-1">
                                        <div role="tabpanel" class="tab-pane active" id="DataList" aria-expanded="true" aria-labelledby="base-tab1">

                                            <div class="table-responsive">
                                                <table id="datalist" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Station</th>
                                                        <th>IP</th>
                                                        <th>Username</th>
                                                        <th>Port</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="Queue" aria-labelledby="base-tab3">

                                            <div class="card">
                                                    <div class="card-header" style="padding: 0;">
                                                        <h4>Queue List</h4>

                                                        <div class="heading-elements" style="top: 0;">
                                                            <ul class="list-inline mb-0">
                                                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <div >
                                                    <table class="queueList table table-bordered" style="table-layout: fixed;
    width: 100%;">
                                                        <thead>
                                                        <tr>
                                                            <th>SL</th>
                                                            <th >Name</th>
                                                            <th>Max-Limit</th>
                                                            <th>Target</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="operation" aria-labelledby="base-tab2">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4><span class="operation_type">Add New</span> Network Station</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
                                                        <form id="DataForm" method="post">
                                                            {{ csrf_field() }}
                                                            <div class="">
                                                                <input type="hidden" id="action" name="action">
                                                                <input type="hidden" id="id" name="id">
                                                                <div class="form-group">
                                                                    <label for="">Station Name <code>*</code></label>
                                                                    <input type="text" class="form-control"  name="network_name" id="network_name" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Server IP <code>*</code></label>
                                                                    <input type="text" class="form-control"  name="server_ip" id="server_ip" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Port <code>*</code></label>
                                                                    <input type="number" class="form-control"  name="port" id="port" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Useranme <code>*</code></label>
                                                                    <input type="text" class="form-control"  name="username" id="username" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Password <code>*</code></label>
                                                                    <input type="password" class="form-control"  name="password" id="password" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Note <code>*</code></label>
                                                                    <input type="text" class="form-control"  name="note" id="note" >
                                                                </div>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary mt-1 mb-0 save">Save</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

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
            var que=$('.queueList').DataTable({
                "columnDefs": [
                    { "width": "5%", "targets": [0] },
                    { "width": "20%", "targets": [1,2,3] }
                ]
            })
            //datatable
            var table = $('#datalist').DataTable
            ({

                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [[0, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[3,5,4,5,6],
                    "orderable": false
                },{
                    "targets":[0,3,5,4,6],
                    className: "text-center"
                } ],
                "ajax": {
                    url: "{{ route("network_datalist") }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                }
            });

            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route("save_network") }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        //console.log(response);
                        if (response == 1) {
                            $("#DataForm").trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
                            $("[href='#DataList']").tab("show");
                            table.ajax.reload();
                        }
                        else {
                            toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(".save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(".save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('click', '.addnew', function () {
                $("#action").val(1);
                $("#role_id").val("");
                $(".operation_type").text("Add New");
                $("#password").attr('required');
                $("#DataForm").trigger("reset");
            });

            $(document).on('click', '.update', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var info = 'id=' + del_id +"&_token={{csrf_token()}}";
                $(element).html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{route("network_update")}}",
                    data: info,
                    success: function (response) {
                        $(element).html('Edit').prop("disabled",false);
                        if(response!=0){
                            $(".operation_type").text("Update");
                            $("[href='#operation']").tab("show");
                            var json = JSON.parse(response);
                            $("#password").removeAttr('required');
                            $("#action").val(2);
                            $("#id").val(json.id);
                            $("#network_name").val(json.network_name);
                            $("#server_ip").val(json.server_ip);
                            $("#username").val(json.username);
                            $("#port").val(json.port);
                            $("#note").val(json.note);
                        }else{
                            toastr.warning( 'Server Error. Try aging!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(element).html('<i class="ft-loader"></i>').prop("disabled",true);
                    }
                });
            });

            $(document).on('click', '.client', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var type=element.attr("type");
                $(element).html('Connecting...').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{ route("connect_network") }}",
                    data: {
                        type:type,
                        id:del_id,
                        _token:"{{csrf_token()}}",
                    },
                    success: function (response) {
                        console.log(response)
                        que.clear()
                        $("[href='#"+type+"']").tab("show");
                        $(element).html(type).prop("disabled",false);
//                        $(".queueList tbody").html(response);
                        que.rows.add(response);
                        que.draw()
                       console.log(response)
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        $(element).html(type).prop("disabled",false);
                    }
                });
            });

            {{--$(document).on('click', '.deleteData', function () {--}}
                {{--var element = $(this);--}}
                {{--var del_id = element.attr("id");--}}
                {{--var data = 'id=' + del_id + '&_token={{csrf_token()}}';--}}

                {{--if (confirm("Are you sure you want to delete this @yield("title")?")) {--}}
                    {{--$.ajax({--}}
                        {{--type: "POST",--}}
                        {{--url: "{{ route("network_delete") }}",--}}
                        {{--data: data,--}}
                        {{--success: function (response) {--}}
                            {{--if (response == 1) {--}}
                                {{--toastr.success('Data removed Successfully!','Success');--}}
                                {{--element.parents("tr").animate({backgroundColor: "#003"}, "slow").animate({opacity: "hide"}, "slow");--}}
                            {{--}--}}
                            {{--else {--}}
                                {{--toastr.warning( 'Data Cannot Removed. Try aging!', 'Warning');--}}
                            {{--}--}}
                        {{--},--}}
                        {{--error: function (request, status, error) {--}}
                            {{--//console.log(request.responseText);--}}
                            {{--toastr.warning( 'Server Error. Try aging!', 'Warning');--}}
                        {{--}--}}
                    {{--});--}}
                {{--}--}}
                {{--return false;--}}

            {{--});--}}

        });

    </script>


@endsection