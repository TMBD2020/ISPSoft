@extends('layouts.app')

@section('title', 'Sub Zone')

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
                        <a onclick="document.getElementById('tabOption').innerHTML='@yield("title")'" class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="DataList" href="#DataList" aria-expanded="true">Show @yield("title")</a>
                    </li>
                    <li class="nav-item">
                        <a onclick="document.getElementById('tabOption').innerHTML='Add @yield("title")'" class="nav-link addnew" id="base-tab2" data-toggle="tab" aria-controls="operation" href="#operation" aria-expanded="false">Add New</a>
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
                                                            <th>ID</th>
                                                            <th>Sub Zone </th>
                                                            <th>Zone</th>
                                                            <th>Thana</th>
                                                            <th>Location</th>
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
                                                                    <label for="sub_zone_name" class="col-sm-3">Sub Zone Name <span class="text-danger">*</span></label>
                                                                    <div  class="col-sm-7">
                                                                        <input type="text" class="form-control"  name="sub_zone_name" id="sub_zone_name" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="thana" class="col-sm-3">Thana <span class="text-danger">*</span></label>
                                                                    <div  class="col-sm-7">
                                                                        <input type="text" class="form-control"  name="thana" id="thana" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="sub_zone_location" class="col-sm-3">Location <span class="text-danger">*</span></label>
                                                                    <div  class="col-sm-7">
                                                                        <input type="text" class="form-control"  name="sub_zone_location" id="sub_zone_location" required>
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
    div.dataTables_wrapper div.dataTables_filter label {
        white-space:normal !important;
    }
</style>
@endsection

@section("page_script")
    <script type="text/javascript">
var node_list;
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
                    "targets":[3,4],
                    "orderable": false
                },{
                    "targets":[0,4],
                    className: "text-center"
                } ],
                "ajax": {
                    url: "{{ route("catv_sub_zone_list") }}",
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


//            table.on( 'order.dt search.dt', function () {
//                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
//                    cell.innerHTML = i+1;
//                } );
//            } ).draw();

            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route("save_sub_zone") }}",
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
            });


            $(document).on('click', '.update', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var info = 'id=' + del_id +"&_token={{csrf_token()}}";
                $(".edit"+del_id).html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{route("sub_zone_update")}}",
                    data: info,
                    success: function (response) {
                        $(".edit"+del_id).html('<i class="ft-edit"></i> Edit').prop("disabled",false);
                        if(response!=0){
                            $("#tabOption").text("Update @yield("title")");
                            $("[href='#operation']").tab("show");
                            var json = JSON.parse(response);
                            $("#action").val(2);
                            $("#id").val(json.id);
                            $("#zone_id").val(json.ref_zone_id).trigger("change");
                            $("#sub_zone_name").val(json.sub_zone_name);
                            $("#sub_zone_location").val(json.sub_zone_location);
                            $("#thana").val(json.thana);
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
                        url: "{{ route("delete_sub_zone") }}",
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
