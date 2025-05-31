@extends('layouts.app')

@section('title', 'Node List')

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
                        <a onclick="document.getElementById('tabOption').innerHTML='Node List'" class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="DataList" href="#DataList" aria-expanded="true">Show @yield("title")</a>
                    </li>
                    <li class="nav-item">
                        <a onclick="document.getElementById('tabOption').innerHTML='Add New Node'" class="nav-link addnew" id="base-tab2" data-toggle="tab" aria-controls="operation" href="#operation" aria-expanded="false">Add New</a>
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
                                                        <th>Node ID</th>
                                                        <th>Node</th>
                                                        <th>Location</th>
                                                        <th>Zone</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="operation" aria-labelledby="base-tab2">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="col-lg-8 col-xs-12  col-md-8 col-sm-12">
                                                        <form id="DataForm" method="post">
                                                            {{ csrf_field() }}
                                                            <div class="">
                                                                <input type="hidden" id="action" name="action">
                                                                <input type="hidden" id="id" name="id">
                                                                <div class="row">
                                                                    <label for="ref_zone_id" class="col-sm-3">Zone<span class="text-danger">*</span></label>
                                                                    <div  class="col-sm-7">
                                                                        <select name="ref_zone_id" id="ref_zone_id" class="form-control select2">
                                                                            @foreach($zones as $zone)
                                                                            <option value="{{ $zone->id }}">{{ $zone->zone_name_en }}</option>
                                                                                @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="node_id" class="col-sm-3">Node ID <span class="text-danger">*</span></label>
                                                                    <div  class="col-sm-7">
                                                                        <div class="input-group">
                                                                            <select class="form-control select2-duel-group" id="node_id" name="node_id"></select>
                                                                            <div class="input-group-addon btn-primary ft-plus addNodeId"  style="cursor: pointer" title="Add ID"></div>
                                                                            <div class="input-group-addon btn-info ft-list nodeIdList" style="cursor: pointer" title="ID List"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="node_name" class="col-sm-3">Node Name <span class="text-danger">*</span></label>
                                                                    <div  class="col-sm-7">
                                                                        <input type="text" class="form-control"  name="node_name" id="node_name" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="node_location" class="col-sm-3">Location <span class="text-danger">*</span></label>
                                                                    <div  class="col-sm-7">
                                                                        <input type="text" class="form-control"  name="node_location" id="node_location" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="node_splitter" class="col-sm-3">Splitter <span class="text-danger">*</span></label>
                                                                    <div  class="col-sm-7">
                                                                       <select name="node_splitter" id="node_splitter" class="form-control">
                                                                                <option value="1:2">1:2</option>
                                                                                <option value="1:4">1:4</option>
                                                                                <option value="1:8">1:8</option>
                                                                                <option value="1:16">1:16</option>
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

<div class="modal fade text-left" id="AddNodeID" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning white">
                <h4 class="modal-title white" id="ModalLabel">Add Node ID</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="NodeIDForm" method="post">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-sm-8">
                            <label for="new_node_id"> Node ID <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" min="1" name="new_node_id" id="new_node_id" required autocomplete="off">
                        </div>
                        <div class="col-sm-4">
                            <label for="id_limit" >Node Qty <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="id_limit" id="id_limit" min='1'  required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-primary nodeIdList">Node Store</button>
                    <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade text-left" id="NodeIDList" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning white">
                <h4 class="modal-title white" id="ModalLabel">Node ID Store</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="col-md-12">
                    <table class="table table-bordered" id="nodeIdlist">
                        <thead>
                            <tr>
                                <th>Node ID</th>
                                <th>Status</th>
                                <th>Requisition No</th>
                            </tr>
                        </thead>
                    </table>
                </div>
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
    div.dataTables_wrapper div.dataTables_filter label {
        white-space:normal !important;
    }
</style>
@endsection

@section("page_script")
    <script type="text/javascript">
var node_list;
        $(document).ready(function () {
            getNodeIdList();
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
                    "targets":[0,1,5],
                    className: "text-center"
                } ],
                "ajax": {
                    url: "{{ route("node_datalist") }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {

                            jsonData.data[i][5] = '<div class="btn-group align-top" role="group">' +
                                    '<button id="' + jsonData.data[i][0] + '" class="update edit'+jsonData.data[i][0]+' btn btn-primary btn-sm badge">' +
                                    '<span class="ft-edit"></span> Edit</button>' +
                                    '<button  id=' + jsonData.data[i][0] + ' class="deleteData btn btn-danger btn-sm badge">' +
                                    '<span class="ft-delete"></span> Delete</button>' +
                                    '</div>';
                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                }
            });


            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
            $(document).on('submit', "#NodeIDForm", function (e) {
                e.preventDefault();
                var element = $(this);
               // $(element +" .save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route("save_node_id") }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        //console.log(response);
                        if (response == 1) {
                            $(element).trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
                            $("#AddNodeID").modal("hide");
                            getNodeIdList();
                        }
                        else {
                            toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                        }
                       // $(element +" .save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                      //  $(element +" .save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route("save_node") }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        //console.log(response);
                        if (response == 1) {
                            $("#DataForm").trigger("reset");
                            //toastr.success("Have fun storming the castle!","Miracle Max Says")
                            toastr.success('Data Saved Successfully!','Success');
                            $("[href='#DataList']").tab("show");
                            table.ajax.reload();
                        }
                        else {
                          //  toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
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
                $("#DataForm").trigger("reset");
                getNodeIdList();
            });

            $(document).on('click', '.addNodeId', function () {
                $("#AddNodeID").modal("show");
                $("#NodeIDList").modal("hide");
                $("#NodeIDForm").trigger("reset");
                getLastNodeId();

            });

            $(document).on('click', '.nodeIdList', function () {
                $("#NodeIDList").modal("show");
                $("#AddNodeID").modal("hide");
                nodeIdList();
            });

            $(document).on('click', '.update', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var info = 'id=' + del_id +"&_token={{csrf_token()}}";
                $(".edit"+del_id).html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{route("node_update")}}",
                    data: info,
                    success: function (response) {
                        $(".edit"+del_id).html('<i class="ft-edit"></i> Edit').prop("disabled",false);
                        if(response!=0){
                            $("#tabOption").text("Update Node");
                            $("[href='#operation']").tab("show");
                            var json = JSON.parse(response);
                            $("#action").val(2);
                            $("#id").val(json.id);
                            $("#node_id").val(json.node_id).trigger("change");
                            $("#zone_id").val(json.ref_zone_id).trigger("change");
                            $("#node_name").val(json.node_name);
                            $("#node_location").val(json.node_location);
                            $("#node_splitter").val(json.node_splitter).trigger("change");
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
                        url: "{{ route("node_delete") }}",
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

        function getNodeIdList(){
            $.ajax({
                type: "POST",
                url: "{{ route("node_id_list") }}",
                data: "_token={{ csrf_token() }}",
                success: function (response) {
                    console.log(response)
                    if (response != 0) {
                        var json = JSON.parse(response);
                        $("#node_id").empty();
                        $.each(json, function(key, value){
                            var html = "<option value='"+value.node_id+"'>"+value.node_id+"</option>";
                            $("#node_id").append(html);
                        });
                    }
                    else {

                    }
                },
                error: function (request, status, error) {
                    //console.log(request.responseText);
                    //toastr.warning( 'Server Error. Try aging!', 'Warning');
                    $("#node_id").empty();
                }
            });
        }

        function getLastNodeId(){
            $.ajax({
                type: "POST",
                url: "{{ route("last_node_id") }}",
                data: "_token={{ csrf_token() }}",
                success: function (response) {
                    console.log(response)
                    if (response == 0) {
                        $("#new_node_id").val("").removeAttr("readonly","readonly");
                    }
                    else {
                        $("#new_node_id").val(response).attr("readonly","readonly");
                    }
                },
                error: function (request, status, error) {
                    //console.log(request.responseText);
                    //toastr.warning( 'Server Error. Try aging!', 'Warning');
                }
            });
        }

        function nodeIdList(){

            node_list = $('#nodeIdlist').DataTable
            ({
                "destroy":true,
                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [[0, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    //"targets":[1],
                    //"orderable": false
                },{
                    "targets":[0,1,2],
                    className: "text-center"
                } ],
                "ajax": {
                    url: "{{ route("node_id_datalist") }}",
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
        }
    </script>
@endsection
