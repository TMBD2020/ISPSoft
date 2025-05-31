@extends('layouts.app')

@section('title', 'Payment Method')

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
                                                        <th>Name</th>
                                                        <th>Account No</th>
                                                        <th>Note</th>
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
                                                                    <label for="">Payment Method Name<code>*</code></label>
                                                                    <input type="text" class="form-control"  name="payment_name" id="payment_name" required>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="">Account Number </label>
                                                                    <input type="text" class="form-control"  name="payment_account" id="payment_account">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="note">Note</label>
                                                                    <textarea class="form-control"  name="note" id="note"></textarea>
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
                "aaSorting": [[1, 'asc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[0,2,3,4],
                    "orderable": false
                },{
                    "targets":[0,2,4],
                    className: "text-center"
                } ],
                "ajax": {
                    url: "{{ route('payment_method_datalist') }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],
                    
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
                    url: "{{ route('save_payment_method') }}",
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
                $(".edit"+del_id).html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{route('payment_method_update')}}",
                    data: info,
                    success: function (response) {
                        $(".edit"+del_id).html('<i class="ft-edit"></i>').prop("disabled",false);
                        if(response!=0){
                            $(".operation_type").text("Update");
                            $("[href='#operation']").tab("show");
                            var json = JSON.parse(response);
                            $("#action").val(2);
                            $("#id").val(json.id);
                            $("#payment_name").val(json.payment_name);
                            $("#payment_account").val(json.payment_account);
                            $("#note").val(json.note);
                        }else{
                            toastr.warning( 'Server Error. Try again!', 'Warning');
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
                        url: "{{ route('payment_method_delete') }}",
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
