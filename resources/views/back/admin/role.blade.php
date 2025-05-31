@extends('layouts.app')

@section('title', 'Role List')

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

                    <ul class="nav nav-tabs float-md-right">
                        <li class="nav-item">
                            <a class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="DataList"
                                href="#DataList" aria-expanded="true">Show @yield('title')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link addnew" id="base-tab2" data-toggle="tab" aria-controls="operation"
                                href="#operation" aria-expanded="false">Add New</a>
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
                                                                <th>ID</th>
                                                                <th>Name</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>

                                            </div>
                                            <div class="tab-pane" id="operation" aria-labelledby="base-tab2">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4><span class="operation_type">Add New</span> Role</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                                            <form id="DataForm" method="post">
                                                                {{ csrf_field() }}
                                                                <div class="">
                                                                    <input type="hidden" id="action" name="action">
                                                                    <input type="hidden" id="role_id" name="role_id">
                                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Role Name
                                                                                <code>*</code></label>
                                                                            <input type="text" class="form-control"
                                                                                name="role_name" id="role_name" required
                                                                                autofocus>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-xs-12 col-sm-12 col-md-12 permissions">


                                                                        <strong>Permissions:</strong> &nbsp;
                                                                        &nbsp;<label><input type="checkbox"
                                                                                name="selectall"> Select All</label>
                                                                        <br />
                                                                        <br />
                                                                        <div class="row">

                                                                            @foreach ($modules as $m => $module)
                                                                                <div class="col-md-3 col-xs-6 col-sm-6">
                                                                                    <ul class="mod">
                                                                                        <li><label><input type="checkbox"
                                                                                                    value="{{ $module->id }}"
                                                                                                    name="module[]">
                                                                                                {{ $module->module_name }}</label>
                                                                                            <ul class="perm">
                                                                                                @foreach ($permission as $i => $value)
                                                                                                    @if ($value->head == $module->id)
                                                                                                        <li><input
                                                                                                                type="checkbox"
                                                                                                                value="{{ $value->id }}"
                                                                                                                head="{{ $value->head }}"
                                                                                                                class="name"
                                                                                                                name="permission[]">
                                                                                                            {{ $value->name }}
                                                                                                        </li>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <button type="submit"
                                                                    class="btn btn-primary mt-1 mb-0 save">Save</button>
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
        .permissions ul {
            padding-left: 10px;
        }

        .permissions .mod li {
            font-weight: bold;
        }

        .permissions .perm li {
            list-style: none;
            font-weight: normal !important;
        }
    </style>
@endsection

@section('page_script')
    <script type="text/javascript">
        $(document).ready(function() {

            var table = $('#datalist').DataTable({
                "destory": true,
                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [
                    [0, 'desc']
                ],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [{
                    "targets": [2],
                    "orderable": false
                }, {
                    "targets": [0, 2],
                    className: "text-center"
                }],
                "ajax": {
                    url: "{{ route('role_datalist') }}",
                    type: "post",
                    "data": {
                        _token: "{{ csrf_token() }}"
                    },
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function(jsonData) {
                        {{-- for (var i = 0, len = jsonData.data.length; i < len; i++) { --}}
                        {{-- if(jsonData.data[i][0]==1 || jsonData.data[i][0]=={{ Auth::user()->id }}){ --}}
                        {{-- jsonData.data[i][2] =""; --}}
                        {{-- }else{ --}}
                        {{-- jsonData.data[i][2] = ; --}}
                        {{-- } --}}
                        {{-- } --}}
                        return jsonData.data;
                    },
                    error: function(request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                    }
                }
            });

            $(document).on('submit', "#DataForm", function(e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_role') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(response) {
                        // console.log(response);
                        if (response == 1) {
                            $("#DataForm").trigger("reset");
                            toastr.success('Data Saved Successfully!', 'Success');
                            $("[href='#DataList']").tab("show");
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
                $("#role_id").val("");
                $(".operation_type").text("Add New");
                $("#DataForm").trigger("reset");
                $('.permissions input[type="checkbox"]').removeAttr('checked');
            });

            $(document).on('change', 'input[name="selectall"]', function() {
                if ($(this).is(":checked")) {
                    $('.permissions input[type="checkbox"]').attr('checked', 'checked');
                } else {
                    $('.permissions input[type="checkbox"]').removeAttr("checked");
                }
            });

            $(document).on('change', 'input[name="module[]"]', function() {
                if ($(this).is(":checked")) {
                    $(this).closest('.mod').find('input[head="' + $(this).val() + '"]').attr('checked',
                        'checked');
                } else {
                    $(this).closest('.mod').find('input[head="' + $(this).val() + '"]').removeAttr(
                        'checked');
                }
            });

            $(document).on('click', '.update', function() {
                var element = $(this);
                var del_id = element.attr("id");
                var info = 'id=' + del_id + "&_token={{ csrf_token() }}";
                $(".edit" + del_id).html('<i class="ft-loader"></i>').prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('role_update') }}",
                    data: info,
                    success: function(response) {
                        //console.log(response)
                        $(element).html('<i class="ft-edit"></i> Edit').prop("disabled", false);
                        if (response.length > 0) {
                            $(".operation_type").text("Update");
                            $("[href='#operation']").tab("show");
                            $("#action").val(2);
                            $("#role_id").val(response[0].id);
                            $("#role_name").val(response[0].name);
                            //  console.log(response[1])
                            $('.permissions input').each(function(key, value) {
                                $(value).removeAttr('checked');
                                $.each(response[1], function(k, v) {
                                    if ($(value).val() == v) {
                                        $(value).attr('checked', 'checked');
                                    }
                                });
                            });
                        } else {
                            toastr.warning('Server Error. Try aging!', 'Warning');
                        }
                    },

                    error: function(request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        $(element).html('<i class="ft-edit"></i> Edit').prop("disabled", false);
                    }
                });
            });

            $(document).on('click', '.deleteData', function() {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{ csrf_token() }}';

                if (confirm("Are you sure you want to delete this?")) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('role_delete') }}",
                        data: data,
                        success: function(response) {
                            if (response == 1) {
                                toastr.success('Data removed Successfully!', 'Success');
                                element.parents("tr").animate({
                                    backgroundColor: "#003"
                                }, "slow").animate({
                                    opacity: "hide"
                                }, "slow");
                            } else {
                                toastr.warning('Data Cannot Removed. Try aging!', 'Warning');
                            }
                        },
                        error: function(request, status, error) {
                            //console.log(request.responseText);
                            toastr.warning('Server Error. Try aging!', 'Warning');
                        }
                    });
                }
                return false;

            });

        });
    </script>

@endsection
