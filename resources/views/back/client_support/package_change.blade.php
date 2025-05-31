@extends('layouts.app')

@section('title', 'Package Change')

@section('content')
    <style>
        h4 span {
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
                                        <div style="display: none"
                                             class="alert alert-success border-0 alert-dismissible mb-2" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">x</span>
                                            </button>
                                            <h4 class="alert-heading mb-2">Success!</h4>

                                            <p><u class="ticket">ticket</u> no ticket has been created successfully!
                                                <a href='{{ route("tickets") }}' target="_blank" class="alert-link">Check
                                                    the ticket?</a></p>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-8">

                                            <div class="card-body" style="padding: 0;">

                                                <form id="package_change_form" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="">
                                                        <input type="hidden" id="action" name="action">
                                                        <input type="hidden" id="id" name="id">
                                                        <input type="hidden" id="old_zone_id" name="old_zone_id">
                                                        <input type="hidden" id="old_node_id" name="old_node_id">
                                                        <input type="hidden" id="old_box_id" name="old_box_id">
                                                        <input type="hidden" name="old_pack" id="old_pack">
                                                        <input type="hidden" name="new_pack" id="new_pack">

                                                        <div class="row">
                                                            <label for="ref_client_id" class="col-sm-4 control-label">Client
                                                                <span class="text-danger">*</span></label>

                                                            <div class="col-sm-8">
                                                                <select name="ref_client_id" id="ref_client_id"
                                                                        class="select2 form-control" required>
                                                                    <option></option>
                                                                    @foreach($clients as $row)
                                                                        <option value="{{ $row->auth_id }}">{{ $row->client_id }}
                                                                            :: {{ $row->client_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="current_package" class="col-sm-4 control-label">Current
                                                                Package </label>

                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control"
                                                                       name="current_package" id="current_package"
                                                                       required readonly>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="current_bandwidth"
                                                                   class="col-sm-4 control-label">Current
                                                                Bandwidth</label>

                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">D</div>
                                                                        <input type="number" min="0"
                                                                               class="form-control text-center"
                                                                               id="current_download"
                                                                               name="current_download" readonly required
                                                                               style="padding: 0">

                                                                        <div class="input-group-addon">U</div>
                                                                        <input type="number" min="0"
                                                                               class="form-control text-center"
                                                                               id="current_upload" name="current_upload"
                                                                               readonly required style="padding: 0">

                                                                        <div class="input-group-addon">Y</div>
                                                                        <input type="number" min="0"
                                                                               class="form-control text-center"
                                                                               id="current_youtube"
                                                                               name="current_youtube" readonly required
                                                                               style="padding: 0">

                                                                        <div class="input-group-addon">Q</div>
                                                                        <input type="number" min="0"
                                                                               class="form-control text-center"
                                                                               id="current_que_type"
                                                                               name="current_que_type" readonly required
                                                                               style="padding: 0">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="current_price" class="col-sm-4 control-label">Current
                                                                Price</label>

                                                            <div class="col-sm-8">
                                                                <input type="number" class="form-control text-right"
                                                                       readonly name="current_price" id="current_price"
                                                                       required>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="new_package" class="col-sm-4 control-label">New
                                                                Package <span class="text-danger">*</span></label>

                                                            <div class="col-sm-8">
                                                                <select class="form-control select2" name="new_package"
                                                                        id="new_package"></select>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="new_bandwidth" class="col-sm-4 control-label">New
                                                                Bandwidth</label>

                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">D</div>
                                                                        <input type="number" min="0"
                                                                               class="form-control text-center"
                                                                               id="new_download" readonly
                                                                               name="new_download" style="padding: 0">

                                                                        <div class="input-group-addon">U</div>
                                                                        <input type="number" min="0"
                                                                               class="form-control text-center"
                                                                               id="new_upload" readonly
                                                                               name="new_upload" style="padding: 0">

                                                                        <div class="input-group-addon">Y</div>
                                                                        <input type="number" min="0"
                                                                               class="form-control text-center"
                                                                               id="new_youtube" readonly
                                                                               name="new_youtube" style="padding: 0">

                                                                        <div class="input-group-addon">Q</div>
                                                                        <input type="number" min="0"
                                                                               class="form-control text-center"
                                                                               id="new_que_type" readonly
                                                                               name="new_que_type" style="padding: 0">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="new_price" class="col-sm-4 control-label">New
                                                                Price </label>

                                                            <div class="col-sm-8">
                                                                <input type="number" class="form-control text-right"
                                                                       name="new_price" id="new_price" required
                                                                       readonly>

                                                                <i class="msg text-danger"></i>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label for="change_date" class="col-sm-4 control-label">Date
                                                                <span class="danger">*</span></label>

                                                            <div class="col-sm-8">
                                                                <input type="date" class="form-control"
                                                                       value="{{ date("Y-m-d") }}" name="change_date"
                                                                       id="change_date" required>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label for="package_change_charge"
                                                                   class="col-sm-4 control-label">Charge</label>

                                                            <div class="col-sm-8">
                                                                <input type="number" class="form-control text-right"
                                                                       value="0" min="0" name="package_change_charge"
                                                                       id="package_change_charge" required>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label for="package_change_charge"
                                                                   class="col-sm-4 control-label">Permanent Discount</label>

                                                            <div class="col-sm-8">
                                                                <input type="number" class="form-control text-right"
                                                                        name="permanent_discount"
                                                                       id="permanent_discount">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="note"
                                                                   class="col-sm-4 control-label">Note </label>

                                                            <div class="col-sm-8">
                                                                <textarea class="form-control" name="note"
                                                                          id="note"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mt-1 mb-0 save">Save
                                                    </button>
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
            </div>
        </div>
    </div>

    <!-- END: Content-->

    <style>
        form .row {
            margin-top: 10px;
        }

        form .row .form-group {
            margin: 0;
        }
    </style>
@endsection
@section("page_script")
    <script type="text/javascript">

        $(document).ready(function () {

            $(document).on('submit', "#package_change_form", function (e) {
                e.preventDefault();

                $("#package_change_form .save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route("migrate_new_package") }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response) {
                        //console.log(response);
                        if (response != 0) {
                            $("#package_change_form").trigger("reset");
                            toastr.success('Ticket has been created.', 'Success');
                            $(".ticket").html(response);
                            $(".alert-success").show();
                        }
                        else {
                            toastr.warning('Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(".msg").html("");
                        $("#package_change_form .save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        $("#package_change_form .save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('change', '#ref_client_id', function () {
                var element = $(this);
                _choose_package(element, "old");
            });

            $(document).on('change', '#new_package', function () {
                var element = $(this);
                _choose_package(element, "new");
            });
        });

        function _choose_package(element, param) {
            var info = "id=" + element.val() + "&param=" + param + "&_token={{csrf_token()}}";
            $.ajax({
                type: "POST",
                url: "{{ route("package_client") }}",
                data: info,
                success: function (response) {
                    //console.log(response);
                    if (response != 0) {
                        var data = JSON.parse(response);
                         json = data[0];
                        if (param == "old") {
                            $("#old_pack").val(json.id);
                            $("#current_package").val(json.package_name);
                            $("#current_price").val(json.package_price);
                            $("#current_download").val(json.download);
                            $("#current_upload").val(json.upload);
                            $("#current_youtube").val(json.youtube);
                            $("#current_que_type").val(json.que_type);
                            $("#permanent_discount").val(data[1]);
                            _package(json.id);
                        } else {
                            $("#new_pack").val(json.id);
                            $("#new_price").val(json.package_price);
                            $("#new_download").val(json.download);
                            $("#new_upload").val(json.upload);
                            $("#new_youtube").val(json.youtube);
                            $("#new_que_type").val(json.que_type);
                            $(".msg").html("New price will calculate (day wise) automatically with this month.");
                        }
                    } else {
                        $("#package_change_form").trigger("reset");
                        toastr.warning('Failed to fetch data. Try aging!', 'Warning');
                    }
                },
                error: function (request, status, error) {
                    $("#package_change_form").trigger("reset");
                    console.log(request.responseText);
                }
            });
        }

        function _package(id) {
            var info = 'id=' + id + "&_token={{csrf_token()}}";
            $.ajax({
                type: "POST",
                url: "{{ route("all_package") }}",
                data: info,
                success: function (response) {
                    // console.log(response)
                    if (response != 0) {
                        var json = JSON.parse(response);
                        $("#new_package").empty();
                        var html = "";
                        html += "<option value=''></option>";
                        $.each(json, function (key, value) {
                            html += "<option value='" + value.id + "'>" + value.package_name + "</option>";
                        });
                        $("#new_package").html(html);
                    } else {
                        toastr.warning('Failde to fetch node list. Try aging!', 'Warning');
                    }
                },
                error: function (request, status, error) {
                    console.log(request.responseText);
                }
            });
        }
    </script>

@endsection
