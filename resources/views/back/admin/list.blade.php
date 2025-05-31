@extends('layouts.app')

@section('title', 'Users')

@section('content')


    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    <h3 class="content-header-title">@yield('title')</h3>
                </div>
                <div class="content-header-right col-md-8 col-12">

                    <ul class="nav nav-tabs float-md-right hidden">
                        <li class="nav-item">
                            <a class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="tab1" href="#DataList"
                                aria-expanded="true">Show @yield('title')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="base-tab2" data-toggle="tab" aria-controls="tab2" href="#operation"
                                aria-expanded="false">Add New</a>
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
                                            <div role="tabpanel" class="tab-pane active" id="DataList" aria-expanded="true"
                                                aria-labelledby="base-tab1">

                                                <div class="table-responsive">
                                                    <table id="datalist"
                                                        class="table table-striped table-bordered zero-configuration"
                                                        style="width: 100%;">
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
                                                                {{ csrf_field() }}
                                                                <div class="">
                                                                    <input type="hidden" id="action" name="action">
                                                                    <input type="hidden" id="id" name="id">
                                                                    <div class="form-group ">
                                                                        <label for="user">User <span
                                                                                class="text-danger">*</span></label>
                                                                        <select class="form-control" name="user"
                                                                            id="user"></select>
                                                                        <div class="userss"></div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="username">Username <span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control"
                                                                            name="username" id="username" required
                                                                            readonly>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="role">Role <span
                                                                                class="text-danger">*</span></label>
                                                                        <select name="roles" id="role"
                                                                            class="form-control select2">
                                                                            @foreach ($roles as $row)
                                                                                <option value="{{ $row->id }}">
                                                                                    {{ $row->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="status">Status</label>
                                                                        <select name="status" id="status"
                                                                            class="form-control">
                                                                            <option value="1">Active</option>
                                                                            <option value="0">Inactive</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <button type="submit"
                                                                    class="btn btn-primary save">Save</button>
                                                                <button type="button"
                                                                    class="btn btn-danger cancel">Cancel</button>
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

@section('page_script')
    <script type="text/javascript">
        $(document).ready(function() {

            //datatable
            var table = $('#datalist').DataTable({

                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [
                    [0, 'desc']
                ],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [{
                    "targets": [2, 3, 4],
                    "orderable": false
                }, {
                    "targets": [0, 3, 4],
                    className: "text-center"
                }],
                "ajax": {
                    url: "{{ route('user_datalist') }}",
                    type: "post",
                    "data": {
                        _token: "{{ csrf_token() }}"
                    },
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function(jsonData) {
                        return jsonData.data;
                    },
                    error: function(request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                    }
                },
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    //debugger;
                    var index = iDisplayIndexFull + 1;
                    $("td:first", nRow).html(index);
                    return nRow;
                },
            });


            $(document).on('change', '#user', function() {
                var element = $("#user option:selected");
                var user_info = element.attr("data");
                $("#username").val(user_info);
            });


            $(document).on('submit', "#DataForm", function(e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_user') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(response) {
                        console.log(response);
                        if (response == 1) {
                            $("#DataForm").trigger("reset");
                            toastr.success('Data Saved Successfully!', 'Success');
                            $("[href='#DataList']").tab("show");
                            _userList();
                            table.ajax.reload();
                        } else {
                            toastr.warning('Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(".save").text("Save").prop("disabled", false);
                    },
                    error: function(request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        $(".save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('click', '.addnew', function() {
                $("#action").val(1);
                $("#id").val("");
                $(".operation_type").text("Add New");
                $("#DataForm").trigger("reset");
                $('#user').show().attr("name", "user");
                $('#id').attr("name", "id");
                $('.userss').html("");
                _userList();
            });
            $(document).on('click', '.cancel', function() {
                $("#action").val(1);
                $("#id").val("");
                $("#DataForm").trigger("reset");
                $('#user').show().attr("name", "user");
                $('#id').attr("name", "id");
                $('.userss').html("");
                $("[href='#DataList']").tab("show");
            });


            $(document).on('click', '.deleteData', function() {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{ csrf_token() }}';

                if (confirm("Are you sure you to reset password this?")) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user_delete') }}",
                        data: data,
                        success: function(response) {
                            // console.log(response);
                            if (response != false) {
                                toastr.success(' <b style="color:red;">New password is <u>'+response+'</u></b>', 'Success');

                            } else {
                                toastr.warning('Password reset failed. Try aging!', 'Warning');
                            }
                        },
                        error: function(request, status, error) {
                            console.log(request.responseText);
                            toastr.warning('Server Error. Try aging!', 'Warning');
                        }
                    });
                }
                return false;

            });


            $(document).on('click', '.update', function() {
                var element = $(this);
                var del_id = element.attr("id");
                var info = 'id=' + del_id + "&_token={{ csrf_token() }}";
                $(element).html('<i class="ft-edit"></i>').prop("disabled", false);
                $.ajax({
                    type: "POST",
                    url: "{{ route('update_user') }}",
                    data: info,
                    success: function(response) {
                        console.log(response)
                        $(element).html('<i class="ft-settings"></i>').prop("disabled", false);
                        if (response.length > 0) {
                            $(".operation_type").text("Update");
                            $("[href='#operation']").tab("show");
                            var json = response[0];
                            $("#action").val(2);
                            $('#id').val(json.id).attr("name", "user");
                            $("#username").val(json.email);
                            $("#user_type").val(json.is_admin).trigger("change");
                            $("#role").val(response[1]).trigger("change");
                            $("#status").val(json.is_active).trigger("change");
                            $('#user').hide().attr("name", "name");
                            $('.userss').html("<input type='text' value='" + json.name + " [" +
                                json.email + "]' class='form-control' readonly>");
                        } else {
                            toastr.warning('Server Error. Try aging!', 'Warning');
                        }

                    },

                    error: function(request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        $(element).html('<i class="ft-settings"></i>').prop("disabled", false);
                    }
                });
            });

        });

        function _userList() {
            var info = '_token={{ csrf_token() }}';
            $.ajax({
                type: "POST",
                url: "{{ route('userList') }}",
                data: info,
                success: function(response) {
                    // console.log(response)
                    if (response != 0) {
                        var json = JSON.parse(response);
                        $("#user").empty();
                        var html = "<option value=\"\">Choose User</option>";
                        $.each(json, function(key, value) {
                            html += "<option value='" + value.auth_id + "' data='" + value.emp_id +
                                "'>" + value.emp_name + " [" + value.emp_id + "]" + "</option>";
                        });
                        $("#user").html(html);
                    } else {
                        toastr.warning('Failed to fetch expense head list. Try aging!', 'Warning');
                    }
                },
                error: function(request, status, error) {
                    console.log(request.responseText);
                }
            });
        }
    </script>
@endsection
