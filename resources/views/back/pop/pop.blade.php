@extends('layouts.app')

@section('title', 'PoP')

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
                                                        <th class="wd-15p">ID</th>
                                                        <th class="wd-15p">Name</th>
                                                        <th class="wd-15p">Address</th>
                                                        <th class="wd-15p">Category</th>
                                                        <th class="wd-15p">Station</th>
                                                        <th class="wd-15p">Power Token</th>
                                                        <th class="wd-15p">Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="operation" aria-labelledby="base-tab2">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4><span class="operation_type">Add New</span> PoP</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
                                                        <form id="DataForm" method="post">
                                                            {{csrf_field()}}
                                                            <div class="">
                                                                <input type="hidden" id="action" name="action">
                                                                <input type="hidden" id="id" name="id">
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">Network Station</label>
                                                                    <div class="col-sm-8">
                                                                        <select name="ref_network_id" id="ref_network_id" class="form-control select2">
                                                                            @foreach($stations as $row)
                                                                                <option value="{{ $row->id }}">{{ $row->network_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">PoP Name <code>*</code></label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"  name="pop_name" id="pop_name" required autofocus>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">PoP Address </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"  name="pop_address" id="pop_address" >
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">PoP Category</label>
                                                                    <div class="col-sm-8">
                                                                        <select name="ref_cat_id" id="ref_cat_id" class="form-control select2">
                                                                            @foreach($pop_categories as $row)
                                                                                <option value="{{ $row->id }}">{{ $row->pop_category_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">Power Token </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"  name="power_token" id="power_token" >
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">PoP Device Address </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"  name="pop_device_details" id="pop_device_details" >
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="ref_emp_id" class="col-sm-4 control-label">Responsible Person </label>
                                                                    <div class="col-sm-8">
                                                                        <select name="ref_emp_id" id="ref_emp_id" class="form-control select2">
                                                                            @foreach($employees as $row)
                                                                                <option value="{{ $row->id }}">{{ $row->emp_id }} :: {{ $row->emp_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">Client PoP</label>
                                                                    <div class="col-sm-8">
                                                                        <select name="client_pop" id="client_pop" class="form-control">
                                                                        </select>
                                                                    </div>
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
            form .row{
                margin-top: 10px;
            }
        </style>
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
                    "targets":[6],
                    "orderable": false
                },{
                    "targets":[6],
                    className: "text-center"
                },
//                    {
//                        "targets": [ 0 ],
//                        "visible": false
//                    }
                ],
                "ajax": {
                    url: "{{ route('pop-datalist') }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {

                            jsonData.data[i][6] = '<div class="btn-group align-top" role="group">' +
                                    '<button id="' + jsonData.data[i][0] + '" class="update edit'+jsonData.data[i][0]+' btn btn-primary btn-sm badge">' +
                                    '<span class="ft-edit"></span></button>' +
                                    '<button  id=' + jsonData.data[i][0] + ' class="deleteData btn btn-danger btn-sm badge">' +
                                    '<span class="ft-delete"></span></button>' +
                                    '</div>';                        }
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
                    url: "{{ route('save_pop') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {console.log(response);
                        if (response == 1) {
                            $("#DataForm").trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
                            $("[href='#DataList']").tab("show");
                            table.ajax.reload();
                            if($("#action").val()==1){

                            }
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
                $("#DataForm").trigger("reset");
            });

            $(document).on('click', '.update', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var info = 'id=' + del_id +"&_token={{csrf_token()}}";
                $(".edit"+del_id).html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{route('pop_update')}}",
                    data: info,
                    success: function (response) {
                        $(".edit"+del_id).html('<i class="ft-edit"></i>').prop("disabled",false);
                        if(response!=0){
                            $("[href='#operation']").tab("show");
                            $(".operation_type").text("Update");
                            var json = JSON.parse(response);
                            $("#action").val(2);
                            $("#id").val(json.id);
                            $("#pop_name").val(json.pop_name);
                            $("#power_token").val(json.power_token);
                            $("#pop_address").val(json.pop_address);
                            $("#pop_device_details").val(json.pop_device_details);
                            $("#ref_emp_id").val(json.ref_emp_id);
                            $("#client_pop").val(json.client_pop).trigger('change');;
                            $("#ref_cat_id").val(json.ref_cat_id).trigger('change');
                            $('#ref_network_id').val(json.ref_network_id).trigger('change');
                            $('#is_active').val(json.is_active).trigger('change');
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
                        url: "{{ route('pop_delete') }}",
                        data: data,
                        success: function (response) {
                            if (response == 1) {
                                toastr.success('Data removed Successfully!','Success');
                                element.parents("tr").animate({backgroundColor: "#003"}, "slow").animate({opacity: "hide"}, "slow");
                            }
                            else {
                                toastr.warning( 'Data Cannot Removed. Try aging!', 'Warning');
                            }
                        },
                        error: function (request, status, error) {
                            //console.log(request.responseText);
                            toastr.warning( 'Server Error. Try aging!', 'Warning');
                        }
                    });
                }
                return false;

            });

        });

    </script>

@endsection
@endsection
