@extends('layouts.app')

@section('title', 'Users')

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
                        <a class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="tab1" href="#DataList" aria-expanded="true">Show @yield("title")</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link addnew" id="base-tab2" data-toggle="tab" aria-controls="tab2" href="#operation" aria-expanded="false">Add New</a>
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
                                                        <th>Name</th>
                                                        <th>Username</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="operation" aria-labelledby="base-tab2">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4><span class="operation_type">Add New</span> User</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
                                                        <form id="DataForm" method="post">
                                                            {{csrf_field()}}
                                                            <div class="">
                                                                <input type="hidden" id="action" name="action">
                                                                <input type="hidden" id="id" name="id">
                                                                <div class="form-group ">
                                                                    <label for="user">User <span class="text-danger">*</span></label>
                                                                    <select class="form-control" name="user" id="user"></select>
                                                                    <div class="userss"></div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="username">Username <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control"  name="username" id="username" required readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="role">Role <span class="text-danger">*</span></label>
                                                                    <select name="role" id="role" class="form-control">
                                                                        @foreach($roles as $row)
                                                                            <option value="{{ $row->id }}">{{ $row->role_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="status">Status</label>
                                                                    <select name="status" id="status" class="form-control">
                                                                        <option value="1">Active</option>
                                                                        <option value="0">Inactive</option>
                                                                    </select>
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
                    "targets":[2,3,4],
                    "orderable": false
                },{
                    "targets":[0,3,4],
                    className: "text-center"
                } ],
                "ajax": {
                    url: "{{url('user_datalist')}}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        var disabled = "";
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {
                            if(jsonData.data[i][0]==1 || jsonData.data[i][0]=={{ Auth::user()->id }}){
                                disabled = "disabled";
                            }else{
                                disabled = "";
                            }
                            jsonData.data[i][4] = '<div class="btn-group align-top" role="group">' +
                                    '<button '+disabled+' id="' + jsonData.data[i][0] + '" class="update edit'+jsonData.data[i][0]+' btn btn-primary btn-sm badge">' +
                                    '<span class="ft-edit"></span></button>' +
                                    '<button '+disabled+' id=' + jsonData.data[i][0] + ' class="deleteData btn btn-danger btn-sm badge">' +
                                    '<span class="ft-delete"></span></button>' +
                                    '</div>';
                            var status = "";
                            if(jsonData.data[i][3]==1){
                                status = "<img src='{{ asset("app-assets/images/active_icon.png") }}' style='width: 20px; height: 20px;' title='Active'> ";
                            }else{
                                status = "<img src='{{ asset("app-assets/images/deactive_icon.png") }}' style='width: 20px; height: 20px;' title='Deactive'> ";
                            }
                            jsonData.data[i][3] = status;
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
                },
            });


            $(document).on('change', '#user', function () {
                var element = $("#user option:selected");
                var user_info = element.attr("data");
                $("#username").val(user_info);
            });


            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{url('save_user')}}",
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
                            _userList();
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
                $('#user').show().attr("name","user");
                $('#id').attr("name","id");
                $('.userss').html("");
                _userList();
            });


            $(document).on('click', '.deleteData', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{csrf_token()}}';

                if (confirm("Are you sure you want to delete this?")) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('user_delete') }}",
                        data: data,
                        success: function (response) {
                            console.log(response);
                            if (response == 1) {
                                toastr.success('Data removed Successfully!','Success');
                                element.parents("tr").animate({backgroundColor: "#003"}, "slow").animate({opacity: "hide"}, "slow");
                                _userList();
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


            $(document).on('click', '.update', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var info = 'id=' + del_id +"&_token={{csrf_token()}}";
                $(".edit"+del_id).html('<i class="ft-edit"></i>').prop("disabled",false);
                $.ajax({
                    type: "POST",
                    url: "{{url('update_user')}}",
                    data: info,
                    success: function (response) {

                        $(".edit"+del_id).html('<i class="ft-edit"></i>').prop("disabled",false);
                        if(response!=0){
                            $(".operation_type").text("Update");
                            $("[href='#operation']").tab("show");
                            var json = JSON.parse(response);
                            $("#action").val(2);
                            $('#id').val(json.id).attr("name","user");
                            $("#username").val(json.email);
                            $("#user_type").val(json.is_admin).trigger("change");
                            $("#role").val(json.ref_role_id).trigger("change");
                            $("#status").val(json.is_active).trigger("change");
                            $('#user').hide().attr("name","name");
                            $('.userss').html("<input type='text' value='"+json.name +" ["+ json.email +"]' class='form-control' readonly>");
                        }else{
                            toastr.warning( 'Server Error. Try aging!', 'Warning');
                        }

                    }
                });
            });

        });

        function _userList(){
            var info = '_token={{csrf_token()}}';
            $.ajax({
                type: "POST",
                url: "{{ url('userList') }}",
                data: info,
                success: function (response) {
                    // console.log(response)
                    if(response!=0){
                        var json = JSON.parse(response);
                        $("#user").empty();
                        var html="<option value=\"\">Choose User</option>";
                        $.each(json, function (key, value) {
                            html+="<option value='"+value.auth_id+"' data='"+value.emp_id+"'>"+value.emp_name+ " ["+value.emp_id+"]"+"</option>";
                        });
                        $("#user").html(html);
                    }else{
                        toastr.warning( 'Failed to fetch expense head list. Try aging!', 'Warning');
                    }
                }
                ,
                error: function (request, status, error) {
                    console.log(request.responseText);
                }
            });
        }
    </script>
@endsection
