@extends('layouts.app')

@section('title', 'Employee Liability')

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
                    <h3 class="content-header-title">@yield("title")</h3>
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

                                        <div class="card-body" style="padding: 0;">

                                                <form id="DataForm" method="post" class="form-horizontal">
                                                {{ csrf_field() }}

                                                    <input type="hidden" id="action" name="action">
                                                    <input type="hidden" id="id" name="id">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <select class="form-control select2" name="emp_id" id="emp_id" required>
                                                                <option value="all">All</option>
                                                                @foreach($emp_list as $row)
                                                                    <option value="{{ $row->id }}" >{{ $row->emp_id }} :: {{ $row->emp_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-4 text-left">
                                                            <button type="button" class="btn btn-primary mb-0 store">Check Liability</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="card" style="margin: 20px 0 0 0;">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center">Sl</th>
                                                            <th>Name</th>
                                                            <th class="text-center">Product Qty</th>
                                                            <th class="text-center">Return Qty</th>
                                                            <th class="text-center">Returnable</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="lists"></tbody>
                                                    </table>
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

    <!-- Modal -->
    <div class="modal fade text-left" id="StoreModal" data-animation="pulse" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary white">
                    <h4 class="modal-title white" id="myModalLabel8">Employee Storage</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="StoreForm" method="post">
                    <input type="hidden" id="store_id" name="store_id">
                    <input type="hidden" id="StoreAction" name="action" value="1">
                    <input type="hidden" id="store_emp_id" name="emp_id">
                    <div class="modal-body">
                        @csrf
                        <div class="col-md-12">
                            <div class="row">
                                <label for="store_emp_name" class="col-sm-4 control-label">Employee <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" autocomplete="off"  id="store_emp_name" class="form-control" readonly required>
                                </div>
                            </div>

                            <div class="row">
                                <label for="ref_department_id" class="col-sm-4 control-label">Department <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" name="ref_department_id" id="ref_department_id" required>
                                        @foreach($departments as $row)
                                            <option value="{{ $row->id }}">{{ $row->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <label for="product_name" class="col-sm-4 control-label">Product Name <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" autocomplete="off" name="product_name" id="product_name" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <label for="product_qty" class="col-sm-4 control-label">Product Quantity <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="number" min="1" value="1" autocomplete="off" name="product_qty" id="product_qty" class="form-control text-right" required>
                                </div>
                            </div>

                            <div class="row">
                                <label for="receive_date" class="col-sm-4 control-label">Receive Date <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="receive_date" id="receive_date" value="{{ date("d/m/Y") }}" class="form-control datepicker text-center" required>
                                </div>
                            </div>

                            <div class="row">
                                <label for="receive_from" class="col-sm-4 control-label">Collected From <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" name="receive_from" id="receive_from" required>
                                        <option></option>
                                        @foreach($emp_list as $row)
                                            <option value="{{ $row->id }}" >{{ $row->emp_id }} :: {{ $row->emp_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger save">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade text-left" id="StoreViewModal" data-animation="pulse" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info white">
                    <h4 class="modal-title white" id="ViewModalLabel">Employee Storage</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Product Name</th>
                            <th>Product Qty</th>
                            <th>Return Qty</th>
                            <th>Returnable</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody class="liabilityLIst"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <style>
        form .row{
            margin-top: 10px;
        }
        table.info{
            padding-left: 0;
        }
        table.info td,table.info th{
            border:0;
            padding: 0;
        }
        table.mainTable td, table.mainTable th{
            padding: 5px 3px;
        }
        table.mainTable th{
           text-align: center;
            vertical-align: bottom;
        }
    </style>
@endsection
@section("page_script")
    <script type="text/javascript">

        $(document).ready(function () {

            $(document).on('click', '.store', function () {
                $(".info").hide();
                var id      = $("#DataForm #emp_id").val();
                var info    = 'id=' + id +"&_token={{csrf_token()}}";
                $(".store").html('<i class="ft-loader"></i> Searching').prop("disabled",true);

                $.ajax({
                    type: "POST",
                    url: "{{ route("emp_liability_list") }}",
                    data: info,
                    success: function (response) {
                        $(".store").html('Check Liability').prop("disabled",false);

                        if(response!=0){console.log(response);
                            var existEmp = [];
                            var json = JSON.parse(response);
                            var htmlBody="";
                            var data = false;
                            var employee= json.employee;
                            var liability= json.liability;
                            $.each(employee, function(key,value)
                            {
                                $.each(liability, function(sl,store)
                                {
                                    if(value.id==store.emp_id){
                                        existEmp.push(store.emp_id);
                                        data=true;
                                        htmlBody +='<tr>' +
                                                '<td class="text-center">' + (sl+1) + '</td>' +
                                                '<td>' + value.emp_name + '</td>' +
                                                '<td class="text-center">' + store.product_qty + '</td>' +
                                                '<td class="text-center"></td>' +
                                                '<td class="text-center"></td>' +
                                                '<td class="text-center">' +
                                                '<div class="btn-group align-top" role="group">' +
                                                '<button id="'+value.id+'" name="' + value.emp_name + '" class="add_store btn btn-info btn-sm badge"><i class="ft-plus"></i> Add</button>' +
                                                '<button id="'+value.id+'" name="' + value.emp_name + '" class="storeView btn btn-primary btn-sm badge"><i class="ft-eye"></i> View</button>' +
                                                '<button id="'+value.id+'" class="deleteStore btn btn-danger btn-sm badge"><i class="ft-delete"></i> Delete</button>' +
                                                '</div>'+
                                                '</td>' +
                                                '</tr>';

                                    }
                                });
                            });
                            if(employee.length !=existEmp.length){
                                $.each(employee, function(key,value)
                                {
                                    if(existEmp[key]!=value.id){
                                        htmlBody +='<tr>' +
                                                '<td class="text-center">' + (key+1) + '</td>' +
                                                '<td>' + value.emp_name + '</td>' +
                                                '<td class="text-center">0</td>' +
                                                '<td class="text-center">0</td>' +
                                                '<td class="text-center">0</td>' +
                                                '<td class="text-center">' +
                                                '<div class="btn-group align-top" role="group">' +
                                                '<button id="'+value.id+'" name="' + value.emp_name + '" class="add_store btn btn-info btn-sm badge"><i class="ft-plus"></i> Add</button>' +
                                                '<button id="'+value.id+'" disabled name="' + value.emp_name + '" class="storeView btn btn-primary btn-sm badge"><i class="ft-eye"></i> View</button>' +
                                                '<button id="'+value.id+'" disabled class="deleteStore btn btn-danger btn-sm badge"><i class="ft-delete"></i> Delete</button>' +
                                                '</div>'+
                                                '</td>' +
                                                '</tr>';
                                    }

                                });
                            }
                            $(".lists").html(htmlBody);
                        }else{
                            toastr.warning( 'No data found. Try later!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(".store").html('Check Liability').prop("disabled",false);
                    }
                });
            });

            $(document).on('click', '.storeView', function () {
                var pk_id = $(this).attr("id");
                var name = $(this).attr("name");
                $("#ViewModalLabel").html(name+" - Liabilty");
                $("#StoreViewModal").modal("show");
                var info    = 'id=' + pk_id +"&_token={{csrf_token()}}";
                $.ajax({
                    type: "POST",
                    url: "{{ route("emp_liability_view") }}",
                    data: info,
                    success: function (response) {
                        $(".store").html('Check Liability').prop("disabled",false);

                        if(response!=0){console.log(response)
                            var json = JSON.parse(response);
                            var data = false;
                            var htmlBody = "";

                            if(json!=0){
                                $.each(json, function(key,store){

                                        htmlBody +='<tr>' +
                                                '<td class="text-center">' + (key+1) + '</td>' +
                                                '<td>' + store.product_name + '</td>' +
                                                '<td class="text-center">' + store.product_qty + '</td>' +
                                                '<td class="text-center">' + dateFormat(store.receive_date) + '</td>' +
                                                '<td class="text-center"></td>' +
                                                '<td class="text-center">' +
                                                '<div class="btn-group align-top" role="group">' +
                                                '<button id="'+store.id+'" class="storeUpdate btn btn-primary btn-sm badge"><i class="ft-edit"></i> Edit</button>' +
                                                '<button id="'+store.id+'" class="deleteStore btn btn-danger btn-sm badge"><i class="ft-delete"></i> Delete</button>' +
                                                '</div>'+
                                                '</td>' +
                                                '</tr>';
                                        data = true;

                                });
                                if(!data){
                                    htmlBody +='<tr class="notr">' +
                                            '<td class="text-center" colspan="6">No Liability Found! <button class="btn btn-sm btn-success add_store" id="'+emp.pk_id+'" ><i class="ft-plus"></i>Add</button></td>' +
                                            '</tr>';
                                }
                                $(".liabilityLIst").html(htmlBody)
                            }
                        }else{
                            toastr.warning( 'No data found. Try later!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(".store").html('Check Liability').prop("disabled",false);
                    }
                });
            });

            $(document).on('click', '.add_store', function () {
                $("#StoreForm").trigger("reset");
                var pk_id = $(this).attr("id");
                var name = $("#emp_id"+pk_id).text()+" :: "+$("#emp_name"+pk_id).text();
                $("#StoreAction").val(1);
                $("#store_emp_id").val(pk_id);
                $("#store_emp_name").val(name);
                $("#store_id").val("");
                $("#StoreModal").modal("show");
            });

            $(document).on('submit', "#StoreForm", function (e) {
                e.preventDefault();

                $("#StoreForm .save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route("save_emp_store") }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        //console.log(response);
                        if (response == 1) {

                            $("#StoreForm").trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
                            $("#StoreModal").modal("hide");
                        }
                        else {
                            toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $("#StoreForm .save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        //console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $("#StoreForm .save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('click', '.deleteStore', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{csrf_token()}}';

                if (confirm("Are you sure you want to delete this?"))
                {
                    $.ajax({
                        type: "POST",
                        url: "{{ route("emp_store_delete") }}",
                        data: data,
                        success: function (response) {
                            //console.log(response)
                            if(response == 1){
                                toastr.success('Data removed Successfully!','Success');
                                element.parents("tr").animate({backgroundColor: "#003"}, "slow").animate({opacity: "hide"}, "slow");
                            }
                            else{
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

            $(document).on('click', '.storeUpdate', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{csrf_token()}}';

                    $.ajax({
                        type: "POST",
                        url: "{{ route("emp_liability_update") }}",
                        data: data,
                        success: function (response) {
                            console.log(response)
                            if(response != 0){
                                var json = JSON.parse(response);
                                var name = json.emp_id+" :: "+json.emp_name;
                                $("#StoreModal").modal("show");
                                $("#StoreAction").val(2);
                                $("#store_id").val(json.store_id);
                                $("#store_emp_id").val(json.emp_pk_id);
                                $("#store_emp_name").val(name);
                                $("#ref_department_id").val(json.ref_department_id).trigger("change");
                                $("#product_name").val(json.product_name);
                                $("#product_qty").val(json.product_qty);
                                $("#receive_date").val(json.receive_date).trigger("change");
                                $("#receive_from").val(json.receive_from).trigger("change");
                            }
                            else{
                                toastr.warning( 'Data Cannot Removed. Try aging!', 'Warning');
                            }
                        },
                        error: function (request, status, error) {
                            console.log(request.responseText);
                            toastr.warning( 'Server Error. Try aging!', 'Warning');
                        }
                    });


            });
        });

        function dateFormat(date){
            var v = date.split("-");
            var y = v[0];
            var m = v[1];
            var d = v[2];
            return d+"/"+m+"/"+y;
        }
    </script>

@endsection
