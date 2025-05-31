@extends('layouts.app')

@section('title', 'Upcoming Clients')

@section('content')
<style>
    h4 span{
        padding-right: 10px;
    }
</style>
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title"><span id="tabOption">@yield("title")</span></h3>
            </div>
            <div class="content-header-right col-md-8 col-12">

                <ul class="nav nav-tabs float-md-right">
                    <li class="nav-item">
                        <a onclick="document.getElementById('tabOption').innerHTML='@yield("title")'" class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="DataList" href="#DataList" aria-expanded="true">Show @yield("title")</a>
                    </li>
                    <li class="nav-item">
                        <a onclick="document.getElementById('tabOption').innerHTML='Add Upcoming Client'" class="nav-link addnew" id="base-tab2" data-toggle="tab" aria-controls="operation" href="#operation" aria-expanded="false">Add New</a>
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
                                                        <th>Name/Cell</th>
                                                        <th>Zone/Address</th>
                                                        <th>Package</th>
                                                        <th>Setup Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="operation" aria-labelledby="base-tab2">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="col-lg-8 col-xs-12  col-md-6 col-sm-12">

                                                        <form id="DataForm" method="post" class="form-horizontal">
                                                            {{ csrf_field() }}

                                                            <input type="hidden" id="action" name="action">
                                                            <input type="hidden" id="id" name="id">

                                                            <div class="row">
                                                                <label for="ref_zone_id" class="col-sm-4 control-label">Zone Name <span class="text-danger">*</span></label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control select2" name="ref_zone_id" id="ref_zone_id" required>
                                                                        @foreach($zones as $row)
                                                                            <option value="{{ $row->id }}" >{{ $row->zone_name_en }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <label for="ref_package_id" class="col-sm-4 control-label">Package <span class="text-danger">*</span></label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control select2" name="ref_package_id" id="ref_package_id" required>
                                                                        @foreach($packages as $row)
                                                                        <option value="{{ $row->id }}" data="{{ $row->package_price }}">{{ $row->package_name }} [D: {{ $row->download }}, U: {{ $row->upload }},Y: {{ $row->youtube }}] [৳ {{ $row->package_price }}]</option>
                                                                            @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <label for="client_name" class="col-sm-4 control-label">Client Name <span class="text-danger">*</span></label>
                                                                <div class="col-sm-8">
                                                                   <input type="text" autocomplete="off" name="client_name" id="client_name" class="form-control" required>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <label for="client_mobile" class="col-sm-4 control-label">Cell No <span class="text-danger">*</span></label>
                                                                <div class="col-sm-8">
                                                                   <input type="text" name="client_mobile" id="client_mobile" class="form-control" required>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <label for="otc" class="col-sm-4 control-label">OTC <span class="text-danger">*</span></label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" name="otc" id="otc" class="form-control text-right" required>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <label for="client_address" class="col-sm-4 control-label">Address <span class="text-danger">*</span></label>
                                                                <div class="col-sm-8">
                                                                    <textarea name="client_address" id="client_address" class="form-control" required></textarea>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <label for="setup_date" class="col-sm-4 control-label">Setup Date <span class="text-danger">*</span></label>
                                                                <div class="col-sm-8">
                                                                    <input type="date" autocomplete="off" name="setup_date" id="setup_date" class="form-control" required>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <label for="previous_isp" class="col-sm-4 control-label">Previous ISP </label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" name="previous_isp" id="previous_isp" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <label for="note" class="col-sm-4 control-label">Note </label>
                                                                <div class="col-sm-8">
                                                                    <textarea name="note" id="note" class="form-control"></textarea>
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

<style>
    #DataForm .row{
        margin-top: 10px;
    }
</style>
@endsection
@section("page_script")
    <script type="text/javascript">

        $(document).ready(function () {

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
                    "targets":[5],
                    "orderable": false
                },{
                    "targets":[5],
                    className: "text-center"
                } ],
                "ajax": {
                    url: "{{ url('upcoming_client_datalist') }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {
                            jsonData.data[i][5] = '<div class="btn-group align-top" role="group">' +
                                    '<button id="' + jsonData.data[i][0] + '" class="update edit'+jsonData.data[i][0]+' btn btn-primary btn-sm badge">' +
                                    '<span class="ft-edit"></span></button>' +
                                    '<button  id=' + jsonData.data[i][0] + ' class="deleteData btn btn-danger btn-sm badge">' +
                                    '<span class="ft-delete"></span></button>' +
                                    '</div>';
                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                },
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    //debugger;
                    var index = iDisplayIndexFull + 1;
                    $("td:first", nRow).html(index);
                    return nRow;
                }
            });

            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ url('save_upcoming_client') }}",
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
                $("#id").val("");
                $(".operation_type").text("Add New");
                $("#DataForm").trigger("reset");
            });

            $(document).on('click', '.update', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var info = 'id=' + del_id +"&_token={{csrf_token()}}";
                $(".edit"+del_id).html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{url('upcoming_client_update')}}",
                    data: info,
                    success: function (response) {
                        $(".edit"+del_id).html('<i class="ft-edit"></i>').prop("disabled",false);
                        if(response!=0){
                            $(".operation_type").text("Update");
                            $("[href='#operation']").tab("show");
                            var json = JSON.parse(response);
                            $("#action").val(2);
                            $("#id").val(json.id);
                            $("#client_name").val(json.client_name);
                            $("#ref_zone_id").val(json.ref_zone_id).trigger("change");
                            $("#ref_package_id").val(json.ref_package_id).trigger("change");
                            $("#client_mobile").val(json.client_mobile);
                            $("#otc").val(json.otc);
                            $("#client_address").val(json.client_address);
                            $("#setup_date").val(json.setup_date);
                            $("#previous_isp").val(json.previous_isp);
                            $("#note").val(json.note);
                        }else{
                            toastr.warning( 'Server Error. Try aging!', 'Warning');
                        }
                    }
                });
            });

            $(document).on('click', '.deleteData', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{csrf_token()}}';

                if (confirm("Are you sure you want to delete this?")) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('upcoming_client_delete') }}",
                        data: data,
                        success: function (response) {
                            console.log(response);
                            if (response == 1) {
                                toastr.success('Data removed Successfully!','Success');
                                element.parents("tr").animate({backgroundColor: "#003"}, "slow").animate({opacity: "hide"}, "slow");
                            }
                            else {
                                toastr.warning( 'Data Cannot Removed. Try aging!', 'Warning');
                            }
                        },
                        error: function (request, status, error) {
                            console.log(request.responseText);
                            toastr.warning( 'Server Error. Try aging!', 'Warning');
                        }
                    });
                }
                return false;

            });
        });

    </script>

@endsection
