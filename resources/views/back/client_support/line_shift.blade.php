@extends('layouts.app')

@section('title', 'Line Shift')

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
                                        <div style="display: none" class="alert alert-success border-0 alert-dismissible mb-2" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">x</span>
                                            </button>
                                            <h4 class="alert-heading mb-2">Success!</h4>
                                            <p><u class="ticket">ticket</u> no ticket has been created successfully!
                                                <a href='{{ route("tickets") }}' target="_blank" class="alert-link">Check the ticket?</a> </p>
                                        </div>
<div class="col-md-2"></div>
                                        <div class="col-md-8">

                                            <div class="card-body" style="padding: 0;">

                                                <form id="lineShiftForm" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="">
                                                        <input type="hidden" id="action" name="action">
                                                        <input type="hidden" id="id" name="id">
                                                        <input type="hidden" id="old_zone_id" name="old_zone_id">
                                                        <input type="hidden" id="old_node_id" name="old_node_id">
                                                        <input type="hidden" id="old_box_id" name="old_box_id">

                                                        <div class="row">
                                                            <label for="ref_client_id" class="col-sm-4 control-label">Client <code>*</code></label>
                                                            <div class="col-sm-8">
                                                                <select name="ref_client_id" id="ref_client_id" class="select2 form-control" required>
                                                                    <option></option>
                                                                    @foreach($clients as $row)
                                                                        <option value="{{ $row->auth_id }}">{{ $row->client_id }} :: {{ $row->client_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="new_zone_id" class="col-sm-4 control-label">Zone <code>*</code></label>
                                                            <div class="col-sm-8">
                                                                <select name="new_zone_id" id="new_zone_id" class="select2  form-control" required>
                                                                    <option></option>
                                                                    @foreach($zones as $row)
                                                                        <option value="{{ $row->id }}">{{ $row->zone_name_en }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="new_node_id" class="col-sm-4 control-label">Node <code>*</code></label>
                                                            <div class="col-sm-8">
                                                                <select name="new_node_id" id="new_node_id" class="select2  form-control node_list" required>
                                                                    <option></option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="new_box_id" class="col-sm-4 control-label">Box <code>*</code></label>
                                                            <div class="col-sm-8">
                                                                <select name="new_box_id" id="new_box_id" class="select2 box_list form-control" required>
                                                                    <option></option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="old_address" class="col-sm-4 control-label">Old Address</label>
                                                            <div class="col-sm-8">
                                                                <textarea class="form-control"  name="old_address" id="old_address" required readonly></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="new_address" class="col-sm-4 control-label">New Address</label>
                                                            <div class="col-sm-8">
                                                                <textarea class="form-control"  name="new_address" id="new_address" required></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="shift_date" class="col-sm-4 control-label">Commitment Date</label>
                                                            <div class="col-sm-8">
                                                                <input type="date" class="form-control"  name="shift_date" id="shift_date" value="{{ date("Y-m-d") }}" required>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="shift_charge" class="col-sm-4 control-label">Charge<code>*</code></label>
                                                            <div class="col-sm-8">
                                                                <input type="number" class="form-control text-right"  name="shift_charge" id="shift_charge" value="0" min="0" required>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="note" class="col-sm-4 control-label">Note</label>
                                                            <div class="col-sm-8">
                                                                <textarea class="form-control" name="note" id="note"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mt-1 mb-0 save">Save</button>
                                                </form>

                                                <div class="card info lists" style="margin: 20px 0 0 0;"></div>
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
    </style>
@endsection
@section("page_script")
    <script type="text/javascript">

        $(document).ready(function () {

            $(document).on('submit', "#lineShiftForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route("save_line_shift") }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        //console.log(response);
                        if (response !=0) {
                            toastr.success( 'Ticket has been created.', 'Success');
                            $("#lineShiftForm").trigger("reset");
                            $(".ticket").html(response);
                            $(".alert-success").show();

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

            $(document).on('change', '#ref_client_id', function () {
                var element = $(this);

                var info = "id="+element.val()+"&_token={{csrf_token()}}";
                $.ajax({
                    type: "POST",
                    url: "{{ route("client_update") }}",
                    data: info,
                    success: function (response) {
                        if(response!=0){
                            var json = JSON.parse(response);
                            $("#old_zone_id").val(json.zone_id);
                            $("#old_box_id").val(json.box_id);
                            $("#old_node_id").val(json.box_id);
                            $("#old_address").val(json.address);
                        }else{
                            toastr.warning( 'Failed to fetch client id. Try aging!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                    }
                });

            });
            $(document).on('change', '#new_zone_id', function () {
                var element = $(this);
                _node(element);
            });
        });

        function _node(data){
            var id = data.val();
            var info = 'id=' + id +"&_token={{csrf_token()}}";
            $.ajax({
                type: "POST",
                url: "{{ route("node_by_zone") }}",
                data: info,
                success: function (response) {
                    // console.log(response)
                    if(response!=0){
                        var json = JSON.parse(response);
                        $("#new_node_id").empty();
                        var html="";
                        $.each(json, function (key, value) {
                            html+="<option value='"+value.id+"'>"+value.node_name+"</option>";
                        });
                        $("#new_node_id").html(html);
                        _box($("#new_node_id"));
                    }else{
                        toastr.warning( 'Failde to fetch node list. Try aging!', 'Warning');
                    }
                }
                ,
                error: function (request, status, error) {
                    console.log(request.responseText);
                }
            });
        }
        function _box(data){
            var id = data.val();
            var info = 'id=' + id +"&_token={{csrf_token()}}";
            $.ajax({
                type: "POST",
                url: "{{ route("box_by_node") }}",
                data: info,
                success: function (response) {
                    // console.log(response)
                    if(response!=0){
                        var json = JSON.parse(response);
                        $("#new_box_id").empty();
                        var html="";
                        $.each(json, function (key, value) {
                            html+="<option value='"+value.id+"'>"+value.box_name+"</option>";
                        });
                        $("#new_box_id").html(html);
                    }else{
                        toastr.warning( 'Failed to fetch box list. Try aging!', 'Warning');
                    }
                },
                error: function (request, status, error) {
                    console.log(request.responseText);
                }
            });
        }

    </script>

@endsection
