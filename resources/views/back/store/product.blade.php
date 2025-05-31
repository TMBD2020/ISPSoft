@extends('layouts.app')

@section('title', 'Product')

@section('content')


        <!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title" id="tabOption">@yield("title")</h3>
            </div>
            <div class="content-header-right col-md-8 col-12">

                <ul class="nav nav-tabs float-md-right">
                    <li class="nav-item">
                        <a class="nav-link active" id="base-tab1" onclick="document.getElementById('tabOption').innerHTML='Product'" data-toggle="tab" aria-controls="DataList" href="#DataList" aria-expanded="true">Show @yield("title")</a>
                    </li>
                    <li class="nav-item">
                        <a onclick="brandList()" class="nav-link" id="base-tab2" data-toggle="tab" aria-controls="brandPanel" href="#brandPanel" aria-expanded="false">Brand List</a>
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
                                            <span class="btn btn-primary ft-plus-square addItem pull-right" data-toggle="modal" data-backdrop="false" data-target="#ProductAdd">Add New</span>

                                            <div class="table-responsive">
                                                <table id="datalist" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Product Name</th>
                                                        <th>Brand</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="brandPanel" aria-expanded="false" aria-labelledby="base-tab2">
                                            <span class="btn btn-primary ft-plus-square addBrand  pull-right" data-toggle="modal" data-backdrop="false" data-target="#AddProductBrand">Add New</span>

                                            <div class="table-responsive">
                                                <table id="brandList" class="table table-striped table-bordered" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Brand Name</th>
                                                        <th>Description</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
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

<div class="modal fade text-left" id="ProductAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success white">
                <h4 class="modal-title white" id="myModalLabel8">Add Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="ProductAddForm" method="post">

                <input type="hidden" id="id" name="id">
                <input type="hidden" id="action" name="action" value="1">

                <div class="modal-body">
                    @csrf
                    <div class="col-md-12">
                        <div class="row">
                            <label for="product_name" class="col-sm-4">Product Name<code>*</code></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"  name="product_name" id="product_name" required autocomplete="off">
                            </div>
                        </div>
                        <div class="row">
                            <label for="brand_id" class="col-sm-4">Brand</label>
                            <div class="col-sm-8">

                                <div class="input-group">
                                    <select class="form-control select2-single-group" required name="brand_id" id="brand_id">
                                        <option></option>
                                    </select>

                                    <div class="input-group-addon btn-primary ft-plus addBrand"  style="cursor: pointer" title="Add Brand"></div>

                                </div>
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

<div class="modal fade text-left" id="AddProductBrand" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success white">
                <h4 class="modal-title white" id="myModalLabel8">Add Brand</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="AddBrandForm" method="post">

                <input type="hidden" id="id" name="id">
                <input type="hidden" id="action" name="action" value="1">

                <div class="modal-body">
                    @csrf
                    <div class="col-md-12">
                        <div class="row">
                            <label for="brand_name" class="col-sm-4">Brand Name<code>*</code></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"  name="brand_name" id="brand_name" required autocomplete="off">
                            </div>
                        </div>
                        <div class="row">
                            <label for="brand_description" class="col-sm-4">Description</label>
                            <div class="col-sm-8">
                                <textarea class="form-control"  name="brand_description" id="brand_description" placeholder="optional"></textarea>
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

<style>
    form .row{
        margin-top:10px;
    }
</style>
@endsection
@section("page_script")
    <script type="text/javascript">
        $(document).ready(function () {
            brandSelectList();
            var table = $('#datalist').DataTable
            ({

                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [[0, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[3],
                    "orderable": false
                },{
                    "targets":[0,3],
                    className: "text-center"
                } ],
                "ajax": {
                    url: "{{ route('product_datalist') }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {

                            jsonData.data[i][3] = '<div class="btn-group align-top" role="group">' +
                                    '<button id="' + jsonData.data[i][0] + '" class="updateItem edit'+jsonData.data[i][0]+' btn btn-primary btn-sm badge">' +
                                    '<span class="ft-edit"></span> Edit</button>' +
                                    '<button  id=' + jsonData.data[i][0] + ' class="deleteItem btn btn-danger btn-sm badge">' +
                                    '<span class="ft-delete"></span> Del</button>' +
                                    '</div>';
                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try again!', 'Warning');
                    }
                }
            });

            $(document).on('submit', "#ProductAddForm", function (e) {
                e.preventDefault();

                $("#ProductAddForm .save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_product') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        //console.log(response);
                        if (response == 1) {
                            $("#ProductAdd").modal("hide");
                            $("#ProductAddForm").trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
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

            $(document).on('click', '.addItem', function () {
                var _this = "#ProductAddForm ";
                $(_this+" #action").val(1);
                $(_this+" #id").val("");
                $(_this).trigger("reset");
            });

            $(document).on('click', '.updateItem', function () {
                var element = $(this);
                var _this = "#ProductAddForm ";
                var del_id = element.attr("id");
                var info = 'id=' + del_id +"&_token={{csrf_token()}}";
                $(".edit"+del_id).html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{route('product_update')}}",
                    data: info,
                    success: function (response) {
                        $(".edit"+del_id).html('<i class="ft-edit"></i> Edit').prop("disabled",false);
                        if(response){
                            $("#ProductAdd").modal("show");
                            var json = JSON.parse(response);
                            $(_this +" #action").val(2);
                            $(_this +" #id").val(json.id);

                            $(_this +" #product_name").val(json.product_name);
                            $(_this +" #brand_id").val(json.brand_id).trigger("change");

                        }else{
                            toastr.warning( 'Server Error. Try again!', 'Warning');
                        }
                    }
                });
            });

            $(document).on('click', '.deleteItem', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{csrf_token()}}';

                if (confirm("Are you sure you want to delete this?")) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('product_delete') }}",
                        data: data,
                        success: function (response) {
                            if (response) {
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

            $(document).on('click', '.deleteBrand', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{csrf_token()}}';

                if (confirm("Are you sure you want to delete this?")) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('product_brand_delete') }}",
                        data: data,
                        success: function (response) {
                            if (response) {
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

            $(document).on('click', '.addBrand', function () {
                $("#AddProductBrand").modal("show");
                var _this = "#AddProductBrand ";
                $(_this+" #action").val(1);
                $(_this+" #id").val("");
                $(_this).trigger("reset");
            });

            $(document).on('submit', "#AddBrandForm", function (e) {
                e.preventDefault();
                var _this = "#AddPersonForm ";
                $(_this +" .save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_product_brand') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        if (response) {
                            $("#AddProductBrand").modal("hide");

                            $("#AddBrandForm #action").val(1);
                            $("#AddBrandForm").trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
                            brandSelectList();
                            brandTable.ajax.reload();
                        }
                        else {
                            toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(_this +" .save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        //console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(_this +" .save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('click', '.brandUpdate', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{csrf_token()}}';

                $.ajax({
                    type: "POST",
                    url: "{{ route('product_brand_update') }}",
                    data: data,
                    success: function (response) {
                        if (response) {
                            var json = response;
                            $("#AddProductBrand").modal("show");
                            $("#AddProductBrand #action").val(2);
                            $("#AddProductBrand #id").val(json.id);
                            $("#AddProductBrand #brand_name").val(json.brand_name);
                            $("#AddProductBrand #brand_description").val(json.brand_description);
                        }
                        else {
                            toastr.warning( 'Data Cannot Read. Try aging!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                });
            });
        });


        function brandList(){
            document.getElementById('tabOption').innerHTML='Brand'
            brandTable = $('#brandList').DataTable
            ({
                "destroy":true,
                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [[0, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[3],
                    "orderable": false
                },{
                    "targets":[0,3],
                    className: "text-center"
                } ],
                "ajax": {
                    url: "{{ route('product_brand_list') }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {

                            jsonData.data[i][3] = '<div class="btn-group align-top" role="group">' +
                                    '<button id="' + jsonData.data[i][0] + '" class="brandUpdate edit'+jsonData.data[i][0]+' btn btn-primary btn-sm badge">' +
                                    '<span class="ft-edit"></span> Edit</button>' +
                                    '<button  id=' + jsonData.data[i][0] + ' class="deleteBrand btn btn-danger btn-sm badge">' +
                                    '<span class="ft-delete"></span> Del</button>' +
                                    '</div>';
                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try again!', 'Warning');
                    }
                }
            });
        }
        function brandSelectList(){
            var info = "_token={{csrf_token()}}"
            $.ajax({
                type: "POST",
                url: "{{route('product_brand_show')}}",
                data: info,
                success: function (response) {
                    var element = $("#brand_id");
                    element.empty();

                    $.each(response,function(key,value){
                        element.append("<option value='"+value.id+"'>"+value.brand_name+"</option>");
                    });
                },
                error: function (request, status, error) {
                    console.log(request.responseText);
                    //toastr.warning( 'Server Error. Try again!', 'Warning');
                }
            });
        }

    </script>

@endsection
