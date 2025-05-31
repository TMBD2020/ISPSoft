@extends('layouts.app')

@section('title', 'Salary Distribution')

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
                                                        <th>Title</th>
                                                        <th>Percentage(%)</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tfoot>
                                                    <tr>
                                                        <th colspan="2" style="text-align:right">Total:</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                    </tfoot>
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
                                                                <input type="hidden" id="avpercentageHidden">
                                                                <div class="form-group">
                                                                    <label for="title">Title<code>*</code></label>
                                                                    <input type="text" class="form-control"  name="title" id="title" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="avpercentage">Available</label>

                                                                    <div class="input-group">
                                                                        <input type="text" readonly class="form-control text-right" id="avpercentage"/>
                                                                        <div class="input-group-addon avload text-white"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="percentage">Percentage<code>*</code></label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control text-right"  name="percentage" id="percentage" required>
                                                                        <div class="input-group-addon newload text-white bg-danger"><i class="ft-x"></i></div>
                                                                    </div>
                                                                    <span class="warn text-danger"></span>
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
        availableP();
        $(document).ready(function () {

            //datatable
            var table = $('#datalist').DataTable
            ({

                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [[0, 'asc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[0,1,2,3],
                    "orderable": false
                },{
                    "targets":[0,3],
                    className: "text-center"
                } ],
                "ajax": {
                    url: "{{ route('distribution-list') }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {

                            var disabled = "";
                            if(jsonData.data[i][1]=="Other"){
                                disabled = "hidden";
                            }
                            jsonData.data[i][3] = '<div class="btn-group align-top '+disabled+'" role="group">' +
                                    '<button id="' + jsonData.data[i][0] + '"   class="update edit'+jsonData.data[i][0]+' btn btn-primary btn-sm badge">' +
                                    '<span class="ft-edit"></span></button>' +
                                    '<button  id=' + jsonData.data[i][0] + '  class="deleteData btn btn-danger btn-sm badge">' +
                                    '<span class="ft-delete"></span></button>' +
                                    '</div>';
                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                },
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // Total over all pages
                    total = api
                        .column( 2 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    // Total over this page
                    pageTotal = api
                        .column( 2, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    // Update footer
                    $( api.column( 2 ).footer() ).html(
                        pageTotal +'%'
                    );
                }
            });

            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_salary_setting') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        console.log(response);
                        if (response == 1) {
                            $("#DataForm").trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
                            $("[href='#DataList']").tab("show");
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
                $("#role_id").val("");
                $(".operation_type").text("Add New");
                $("#DataForm").trigger("reset");
                availableP();
            });

            $(document).on('keyup', '#percentage', function () {
                var available   = Number($("#avpercentageHidden").val());
                var avpercentageHidden   = Number($("#avpercentage").val());
                var newp        = Number($(this).val());
                $(".newload").addClass("bg-danger").html('<i class="ft-loader"></i>');

                if(newp>available){
                    $(".newload").addClass("bg-danger").html('<i class="ft-x"></i>');
                   $(".warn").html("You cannot set largest then available percent!")
                   $(".save").prop("disabled",true)
                }else{
                    if(!newp){
                        $(".newload").addClass("bg-danger").html('<i class="ft-x"></i>');
                        $(".save").prop("disabled",true)
                        $(".warn").html("")
                    }else{
                        $(".newload").addClass("bg-success").removeClass("bg-danger").html('<i class="ft-check"></i>');
                        $(".save").prop("disabled",false)
                        $(".warn").html("")
                    }
                }
                $("#avpercentage").val(available-newp)
            });

            $(document).on('click', '.update', function () {
                availableP();
                var element = $(this);
                var del_id = element.attr("id");
                var info = 'id=' + del_id +"&_token={{csrf_token()}}";
                $(".edit"+del_id).html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{route('salary_setting_update')}}",
                    data: info,
                    success: function (response) {
                        $(".edit"+del_id).html('<i class="ft-edit"></i>').prop("disabled",false);
                        if(response!=0){
                            $(".operation_type").text("Update");
                            $("[href='#operation']").tab("show");
                            var json = JSON.parse(response);
                            $("#action").val(2);
                            $("#id").val(json.id);
                            $("#title").val(json.title);
                            $("#percentage").val(json.percentage);
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
                        url: "{{ route('salary_setting_delete') }}",
                        data: data,
                        success: function (response) {
                            if (response == 1) {
                                toastr.success('Data removed Successfully!','Success');
                                element.parents("tr").animate({backgroundColor: "#003"}, "slow").animate({opacity: "hide"}, "slow");
                                table.ajax.reload();
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

        function  availableP() {

            $(".avload").addClass("bg-danger").html('<i class="ft-loader"></i>');
            $.ajax({
                type: "POST",
                url : "{{ route('salary_distribute_percent') }}",
                data: "_token={{ csrf_token() }}",
                success: function (response) {
                    $("#avpercentage").val(response);
                    $("#avpercentageHidden").val(response);
                    $(".avload").addClass("bg-success").removeClass("bg-danger").html('<i class="ft-check"></i>');
                }
            });
        }
    </script>

@endsection

