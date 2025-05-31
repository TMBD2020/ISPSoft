@extends('layouts.app')

@section('title', 'SMS API')

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
                                                        <th>SL</th>
                                                        <th>API Name</th>
                                                        <th>SMS Sender</th>
                                                        <th>API Username</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="operation" aria-labelledby="base-tab2">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4><span class="operation_type">Add New</span> @yield("title")</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
                                                        <form id="DataForm" method="post">
                                                            {{ csrf_field() }}
                                                            <div class="">
                                                                <input type="hidden" id="action" name="action">
                                                                <input type="hidden" id="id" name="id">

                                                                <div class="form-group">
                                                                    <label for="api_name">API Name<code>*</code></label>
                                                                    <input type="text" class="form-control"  name="api_name" id="api_name" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="api_user_name">API Username</label>
                                                                    <input type="text" class="form-control"  name="api_user_name" autocomplete="off" id="api_user_name" >
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="api_pass_word">API Password</label>
                                                                    <input type="text" class="form-control"  name="api_pass_word" autocomplete="off"  id="api_pass_word" >
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="api_token">API Token</label>
                                                                    <input type="text" class="form-control"  name="api_token" autocomplete="off"  id="api_token" >
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="api_url">API URL<code>*</code></label>
                                                                    <textarea class="form-control"  name="api_url" id="api_url" autocomplete="off"  required></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="api_sender">SMS Sender<code>*</code></label>
                                                                    <input type="text" class="form-control"  name="api_sender" id="api_sender" required>
                                                                </div>
                                                                {{-- <div class="form-group">
                                                                    <label for="sms_rate">SMS Rate<code>*</code></label>
                                                                    <input type="text" class="form-control text-center" min="" name="sms_rate" id="sms_rate" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="api_default">Default API</label>
                                                                    <select class="form-control" name="api_default" id="api_default">
                                                                        <option value="0">No</option>
                                                                        <option value="1">Yes</option>
                                                                    </select>
                                                                </div> --}}
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
                    "targets":[4],
                    "orderable": false
                },{
                    "targets":[0,4],
                    className: "text-center"
                } ],
                "ajax": {
                    url: "{{ route('super.sms_api_list') }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        console.log(jsonData.q)
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try again!', 'Warning');
                    }
                }
            });

            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('super.save_sms_api') }}",
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
                            toastr.warning( 'Data Cannot Saved. Try again!', 'Warning');
                        }
                        $(".save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try again!', 'Warning');
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
                element.html('<i class="ft-loader"></i>').prop("disabled",true);
                $("#DataForm").trigger("reset");
                $.ajax({
                    type: "POST",
                    url: "{{route('super.sms_api_update')}}",
                    data: info,
                    success: function (response) {
                        element.html('<i class="ft-edit"></i>').prop("disabled",false);
                        if(response!=0){
                            $(".operation_type").text("Update");
                            $("[href='#operation']").tab("show");
                            var json = JSON.parse(response);
                            $("#action").val(2);
                            $("#id").val(json.id);
                            $("#api_name").val(json.api_name);
                            $("#api_url").val(json.api_url);
                            $("#api_sender").val(json.api_sender);
                            $("#api_token").val(json.api_token);
                            $("#api_pass_word").val(json.api_password);
                            $("#api_user_name").val(json.api_username);
                        }else{
                            toastr.warning( 'Server Error. Try again!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                            //console.log(request.responseText);
                            element.html('<i class="ft-edit"></i>').prop("disabled",false);
                            toastr.warning( 'Server Error. Try again!', 'Warning');
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
                        url: "{{ route('super.sms_api_delete') }}",
                        data: data,
                        success: function (response) {
                            if (response == 1) {
                                toastr.success('Data removed Successfully!','Success');
                                element.parents("tr").animate({backgroundColor: "#003"}, "slow").animate({opacity: "hide"}, "slow");
                            }
                            else {
                                toastr.warning( 'Data Cannot Removed. Try again!', 'Warning');
                            }
                        },
                        error: function (request, status, error) {
                            //console.log(request.responseText);
                            toastr.warning( 'Server Error. Try again!', 'Warning');
                        }
                    });
                }
                return false;

            });

        });

    </script>

@endsection
