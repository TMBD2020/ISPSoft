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
                                                                    <label for="user">Name <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control"  name="name" id="name" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="username">Username <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control"  name="email" id="username" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="password">Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control"  name="password" id="password" required>
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
                    url: "{{route('super.user_datalist')}}",
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
                    url: "{{route('super.save_user')}}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        console.log(response);
                        if (response.id) {
                            $("#DataForm").trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
                            $("[href='#DataList']").tab("show");
                            table.ajax.reload();
                        }
                        else {
                            var msg=''
                            if(response){
                                $.each(response,function(k,v){
                                    msg+=(v)+"\n"
                                })
                            }
                         if(!msg){
                             msg='Data Cannot Saved. Try aging!'
                         }
                            toastr.warning( msg, 'Warning');
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
                $("#password").attr("required");
                $("#password").closest(".form-group").find('.text-danger').html('*');
            });




            $(document).on('click', '.update', function () {
                var element = $(this);
                var del_id = element.attr("id");
                $(element).html('<i class="ft-edit"></i>').prop("disabled",false);
                $.ajax({
                    type: "POST",
                    url: "{{route('super.get_user')}}",
                    data: {
                        id:del_id,
                        _token:"{{csrf_token()}}"
                    },
                    success: function (response) {
console.log(response)
                        $(element).html('<i class="ft-settings"></i>').prop("disabled",false);
                        if(response.id){
                            $(".operation_type").text("Update");
                            $("[href='#operation']").tab("show");
                            var json = response;
                            $("#action").val(2);
                            $('#id').val(json.id);
                            $("#name").val(json.name);
                            $("#username").val(json.email);
                            $("#status").val(json.is_active).trigger("change");
                            $("#password").removeAttr("required");
                            $("#password").closest(".form-group").find('.text-danger').html('');
                        }else{
                            toastr.warning( 'Data not found. Try aging!', 'Warning');
                        }

                    },

                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(element).html('<i class="ft-settings"></i>').prop("disabled",false);
                    }
                });
            });

        });

    </script>
@endsection
