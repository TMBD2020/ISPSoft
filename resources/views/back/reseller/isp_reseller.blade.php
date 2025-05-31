@extends('layouts.app')

@section('title', 'ISP Reseller')

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
                    <h3 class="content-header-title" id="tabOption">@yield("title")</h3>
                </div>
                <div class="content-header-right col-md-8 col-12">

                    <ul class="nav nav-tabs float-md-right">
                        <li class="nav-item">
                            <a onclick="document.getElementById('tabOption').innerHTML='Reseller'"
                               class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="DataList"
                               href="#DataList" aria-expanded="true">Show @yield("title")</a>
                        </li>
                        <li class="nav-item">
                            <a onclick="document.getElementById('tabOption').innerHTML='Add New Reseller'"
                               class="nav-link addnew" id="base-tab2" data-toggle="tab" aria-controls="operation"
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
                                            <div role="tabpanel" class="tab-pane active" id="DataList"
                                                 aria-expanded="true" aria-labelledby="base-tab1">

                                                <div class="table-responsive">
                                                    <table id="datalist"
                                                           class="table table-striped table-bordered table-hovered"
                                                           style="width: 100%;">
                                                        <thead>
                                                        <tr>
                                                            <th style="width:3%">SL</th>
                                                            <th style="width:20%">ID</th>
                                                            <th style="width:20%">Name</th>
                                                            <th style="width:10%">Personal Contact</th>
                                                            <th style="width:10%">Present Address</th>
                                                            <th style="width:10%">Network</th>
                                                            <th style="width:15%">Zone</th>
                                                            <th style="width:5%">Action</th>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>

                                            </div>
                                            <div class="tab-pane" id="operation" aria-labelledby="base-tab2">
                                                <div class="card">
                                                    <div class="card-body" style="padding:0;">
                                                        <form id="DataForm" method="post"
                                                              class="needs-validation form-horizontal" novalidate>
                                                            {{ csrf_field() }}

                                                            <input type="hidden" id="action" name="action">
                                                            <input type="hidden" id="id" name="id">
                                                            <input type="hidden" id="pop_id_hidden">

                                                            <div class="row">
                                                                <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">

                                                                    <div class="row">
                                                                        <label for="network_id"
                                                                               class="col-sm-5 control-label">Network
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2"
                                                                                    name="network_id" id="network_id"
                                                                                    required>
                                                                                @foreach($networks as $row)
                                                                                    <option value="{{ $row->id }}">{{ $row->network_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="pop_id"
                                                                               class="col-sm-5 control-label">PoP
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2"
                                                                                    name="pop_id" id="pop_id"
                                                                                    required>
                                                                                <option>Select Zone</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row hideEdit">
                                                                        <label for="package_id"
                                                                               class="col-sm-5 control-label">Package</label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2"
                                                                                    name="package_id" id="package_id"
                                                                                    >
                                                                                <option value="" data="">Select None
                                                                                </option>
                                                                                @foreach($packages as $row)
                                                                                    <option value="{{ $row->id }}"
                                                                                            data="{{ $row->package_price }}">{{ $row->package_name }}
                                                                                        [D: {{ $row->download }},
                                                                                        U: {{ $row->upload }}
                                                                                        ,Y: {{ $row->youtube }}]
                                                                                        [৳ {{ $row->package_price }}]
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="reseller_name"
                                                                               class="col-sm-5 control-label">Reseller
                                                                            Name <span
                                                                                    class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" autocomplete="off"
                                                                                   name="reseller_name"
                                                                                   id="reseller_name"
                                                                                   class="form-control" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="reseller_username"
                                                                               class="col-sm-5 control-label">Reseller
                                                                            ID <span
                                                                                    class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" autocomplete="off"
                                                                                   readonly name="reseller_username"
                                                                                   id="reseller_username"
                                                                                   class="form-control" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="reseller_password"
                                                                               class="col-sm-5 control-label">Password<span
                                                                                    class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" autocomplete="off"
                                                                                   value="123123"
                                                                                   name="reseller_password"
                                                                                   id="reseller_password"
                                                                                   class="form-control" required>
                                                                        </div>
                                                                    </div>


                                                                    <div class="row">
                                                                        <label for="f_name"
                                                                               class="col-sm-5 control-label">Father's
                                                                            Name</label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" autocomplete="off"
                                                                                   name="f_name" id="f_name"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="m_name"
                                                                               class="col-sm-5 control-label">Mother's
                                                                            Name</label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" autocomplete="off"
                                                                                   name="m_name" id="m_name"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>


                                                                    <div class="row">
                                                                        <label for="reseller_nid"
                                                                               class="col-sm-5 control-label">National
                                                                            ID</label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" autocomplete="off"
                                                                                   name="reseller_nid" id="reseller_nid"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>


                                                                    <div class="row">
                                                                        <label for="reseller_email"
                                                                               class="col-sm-5 control-label">Email
                                                                            Address </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="email" name="reseller_email"
                                                                                   id="reseller_email"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="reseller_dob"
                                                                               class="col-sm-5 control-label">Date of
                                                                            Birth</label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="reseller_dob"
                                                                                   id="reseller_dob"
                                                                                   class="form-control datepicker">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="reseller_join_date"
                                                                               class="col-sm-5 control-label">Join Date
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="reseller_join_date"
                                                                                   id="reseller_join_date"
                                                                                   class="form-control datepicker"
                                                                                   value="{{ date("d/m/Y") }}" required>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">

                                                                    <div class="row">
                                                                        <label
                                                                               class="col-sm-5 control-label">Reseller
                                                                            Type</label>

                                                                        <div class="col-sm-7">
                                                                           <label for="Mac"> <input type="radio" name="reseller_type"
                                                                                          id="Mac" value="Mac" checked> Mac</label>
                                                                           <label for="Bandwidth"> <input type="radio" name="reseller_type"
                                                                                          id="Bandwidth" value="Bandwidth"> Bandwidth</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row bandwidthTable" style="display: none;">
                                                                        <div class="col-md-12">
                                                                            <table>
                                                                                <thead>
                                                                                <tr>
                                                                                    <th class="text-center">Title</th>
                                                                                    <th class="text-center">Qty</th>
                                                                                    <th class="text-center">Price</th>
                                                                                    <th class="text-center"><button type="button" class="btn btn-default ft-plus btn-sm plusrow text-primary"></button></th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td> <input type="text" class="form-control" name="title[]"></td>
                                                                                    <td> <input type="text"
                                                                                                class="form-control"
                                                                                                name="qty[]"></td>
                                                                                    <td> <input type="text"
                                                                                                class="form-control"
                                                                                                name="price[]"></td>
                                                                                    <td>
                                                                                        <div class="btn-group">
                                                                                            <button type="button" class="btn ft-minus btn-default btn-sm minusrow text-danger"></button>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label class="col-sm-5 control-label">Gender</label>

                                                                        <div class="col-sm-7">
                                                                            <label for="Male"> <input type="radio" name="reseller_sex"
                                                                                   id="Male" value="Male" checked> Male</label>
                                                                                <label for="Female"> <input type="radio" name="reseller_sex"
                                                                                   id="Female" value="Female"> Female</label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="reseller_blood"
                                                                               class="col-sm-5 control-label">Blood
                                                                            Group
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2"
                                                                                    name="reseller_blood"
                                                                                    id="reseller_blood"
                                                                                    required>
                                                                                <option value="Unknown">Unknown</option>
                                                                                <option value="A+">A+</option>
                                                                                <option value="A-">A-</option>
                                                                                <option value="AB+">AB+</option>
                                                                                <option value="AB-">AB-</option>
                                                                                <option value="B+">B+</option>
                                                                                <option value="B-">B-</option>
                                                                                <option value="O+">O+</option>
                                                                                <option value="O-">O-</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="personal_contact"
                                                                               class="col-sm-5 control-label">Personal
                                                                            Contact
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="personal_contact"
                                                                                   id="personal_contact"
                                                                                   class="form-control"
                                                                                   required>
                                                                            <span class="text-danger cellMsg"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="office_contact"
                                                                               class="col-sm-5 control-label">Office
                                                                            Contact</label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="office_contact"
                                                                                   id="office_contact"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="reseller_present_add"
                                                                               class="col-sm-5 control-label">Present
                                                                            Address
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <textarea name="reseller_present_add"
                                                                                      id="reseller_present_add"
                                                                                      class="form-control"
                                                                                      required></textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="reseller_permanent_add"
                                                                               class="col-sm-5 control-label">Permanent
                                                                            Address
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <textarea name="reseller_permanent_add"
                                                                                      id="reseller_permanent_add"
                                                                                      class="form-control"
                                                                                      required></textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row hideEdit">
                                                                        <label for="previous_due"
                                                                               class="col-sm-5 control-label">Previous
                                                                            Due</label>

                                                                        <div class="col-sm-7">
                                                                            <input type="number" name="previous_due"
                                                                                   id="previous_due"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row hideEdit">
                                                                        <label for="permanent_discount_amount"
                                                                               class="col-sm-5 control-label">Permanent
                                                                            Discount</label>

                                                                        <div class="col-sm-7">
                                                                            <input type="number"
                                                                                   name="permanent_discount_amount"
                                                                                   id="permanent_discount_amount"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="reseller_skype"
                                                                               class="col-sm-5 control-label">Skype</label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="reseller_skype"
                                                                                   id="reseller_skype"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="note"
                                                                               class="col-sm-5 control-label">Note </label>

                                                                        <div class="col-sm-7">
                                                                            <textarea name="note" id="note"
                                                                                      class="form-control"></textarea>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="col-md-12 text-center">
                                                                    <button type="submit" style="width: 30%;"
                                                                            class="btn btn-primary mt-1 mb-0 save">
                                                                        Save
                                                                    </button>
                                                                </div>
                                                            </div>
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
                </section>
                <!--/ Zero configuration table -->
            </div>
        </div>
    </div>
    <!-- END: Content-->


    <style>
        #DataForm .row {
            margin-top: 10px;
        }

        #DataForm .row label {
            text-align: left;
        }

        table#datalist tr td:last-child {
            vertical-align: middle !important;
        }
    </style>
@endsection
@section("page_script")
    <script type="text/javascript">

        $(document).ready(function () {
            __startup();

            var table = $('#datalist').DataTable
            ({
                "destroy": true,
                "bProcessing": true,
                "serverSide": true,
                "responsive": false,
                "aaSorting": [[0, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [
                    {
                        "targets": [2, 3, 4, 7],
                        "orderable": false
                    }, {
                        "targets": [0, 2, 7],
                        className: "text-center"
                    }],
                "ajax": {
                    url: "{{ route("reseller_list") }}",
                    type: "post",
                    "data": {_token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                    }
                }
            });



            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route("save_reseller") }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response == 1) {
                            $("#DataForm").trigger("reset");
                            toastr.success('Data Saved Successfully!', 'Success');
                            $("[href='#DataList']").tab("show");
                            _resellerId();
                            table.ajax.reload();

                        }
                        else {
                            toastr.warning('Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(".save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        $(".save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('click', '.addnew', function () {
                $("#action").val(1);
                $("#id").val("");
                $("#reseller_password").attr("required", "required").val("");
                $("#DataForm").trigger("reset");
                $(".modal_bodyHTML").empty();
                
                $('input[name="reseller_type"][value="Mac"]').trigger('change');
                $(".hideEdit").show();
                _resellerId();
            });



            $(document).on('click', '.plusrow', function () {
                var html = ' <tr>' +
                    ' <td>' +
                    '<input type="text" class="form-control" name="title[]">' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control text-center" name="qty[]">' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control text-right" name="price[]">' +
                    '</td>' +
                    '<td>' +
                        '<div class="btn-group">' +
                            '<button type="button" class="btn ft-minus btn-default btn-sm minusrow text-danger"></button>' +
                        '</div>' +
                    '</td>' +
                    '</tr>';
                $(".bandwidthTable tbody").append(html);

            });

            $(document).on('click', '.minusrow', function () {
                if( $(".bandwidthTable tbody tr").length>1){
                    $(this).closest("tr").remove();
                }
            });

            $(document).on('keyup blur', '#cell_no', function () {
                var element = $(this).val();
                var requirement = $(".cellMsg");
                //console.log(element.length)
                if (element.length != 11) {
                    requirement.html("Invalid mobile no! Contain 11 digit.");
                } else {
                    requirement.html("");
                }
            });
            $(document).on('change', 'input[name="reseller_type"]', function () {
                if($(this).val()=='Mac'){
                    $(".bandwidthTable").hide();
                }else{
                    $(".bandwidthTable").show();
                    if($(".bandwidthTable tbody tr").length==0) {
                        $('.plusrow').trigger('click')
                    }

                }
            });

            $(document).on('click', '.update', function () {
                var element = $(this);
                var del_id = element.attr("id");

                $(".bandwidthTable tbody").empty();
                $(element).html('<i class="ft-loader"></i>').prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route("reseller_update") }}",
                    data: {
                        id:del_id,
                        _token:"{{csrf_token()}}"
                    },
                    success: function (response) {
                        console.log(response)
                        $(element).html('<i class="ft-edit"></i> Edit').prop("disabled", false);
                        if (response.status) {
                            $(".hideEdit").hide();
                            $("#tabOption").text("Update Reseller");
                            $("[href='#operation']").tab("show");
                            var json = (response.data);
                            $("#action").val(2);
                            $("#id").val(json.id);
                            $("#reseller_password").removeAttr("required").val("");
                            $("#reseller_username").val(json.reseller_id);
                            $("#reseller_name").val(json.reseller_name);
                            $("#pop_id_hidden").val(json.pop_id);
                            $("#network_id").val(json.network_id).trigger("change");
                            $("#package_id").val(json.package_id).trigger("change");
                            $("#f_name").val(json.f_name);
                            $("#m_name").val(json.m_name);
                            $("#reseller_nid").val(json.reseller_nid);
                            $("#reseller_email").val(json.reseller_email);
                            $("#reseller_dob").val(json.reseller_dob);
                            $("#reseller_join_date").val(json.reseller_join_date);
                            $("input[name='reseller_sex'][value='" + json.reseller_sex + "']").prop("checked", true);
                            $("input[name='reseller_type'][value='" + json.reseller_type + "']").prop("checked", true).trigger('change');
                            $("#reseller_blood").val(json.reseller_blood);
                            $("#permanent_discount_amount").val(json.permanent_discount_amount);
                            $("#personal_contact").val(json.personal_contact);
                            $("#office_contact").val(json.office_contact);
                            $("#reseller_present_add").val(json.reseller_present_add);
                            $("#reseller_permanent_add").val(json.reseller_permanent_add);
                            $("#reseller_skype").val(json.reseller_skype);
                            $("#note").val(json.note);
                            if (json.reseller_image) {
                                $(".picture").html("<img src='" + json.picture + "' style='width:100px; height: 100px;'>");
                            } else {
                                $(".picture").html("");
                            }

                            if (json.bandwidth_details) {
                                var html = '';
                                var bandwidth = JSON.parse(json.bandwidth_details);
                                $.each(bandwidth, function (key, value) {
                                    html += '<tr>' +
                                            '<td>' +
                                            '<input type="text" class="form-control" name="title[]" value="' + value.title + '">' +
                                            '</td>' +
                                            '<td>' +
                                            '<input type="text" class="form-control text-center" name="qty[]" value="' + value.qty + '">' +
                                            '</td>' +
                                            '<td>' +
                                            '<input type="text" class="form-control text-right" name="price[]" value="' + value.price + '">' +
                                            '</td>' +
                                            '<td>' +
                                            '<button type="button" class="btn ft-minus btn-default btn-sm minusrow text-danger"></button>' +
                                            '</td>' +
                                            '</tr>';
                                });
                                $(".bandwidthTable tbody").html(html);
                            }
                        } else {
                            toastr.warning('Server Error. Try aging!', 'Warning');
                        }
                    }
                });
            });

             $(document).on('change', '#custom_bill', function () {
                _payableFee();
            });
            //zone call
            $(document).on('change', '#network_id', function () {
                var element = $(this);
                _pop(element);
            });

        });

        function __startup() {
            _pop($('#network_id'));
            _resellerId();
        }
        function _pop(data) {
            var id = data.val();
            var info = 'id=' + id + "&_token={{csrf_token()}}";
            $.ajax({
                type: "POST",
                url: "{{ route("pop_by_network") }}",
                data: info,
                success: function (response) {
                    //console.log(response)
                    if (response != 0) {
                        var json = JSON.parse(response);
                        $("#pop_id").empty();
                        var html = "";
                        var pop_id = $("#pop_id_hidden").val();

                        //console.log(json)
                        $.each(json, function (key, value) {
                            html += "<option value='" + value.id + "' " + (pop_id == value.id ? 'selected' : '') + ">" + value.pop_name + "</option>";
                        });
                        $("#pop_id").html(html);
                        // _zone($("#pop_id"))
                    } else {
                        toastr.warning('Failed to fetch zone list. Try aging!', 'Warning');
                    }
                }
            });
        }
        function _zone(data) {
            var id = data.val();
            var info = 'id=' + id + "&_token={{ csrf_token() }}";
            $.ajax({
                type: "POST",
                url: "{{ route("pop_by_network") }}",
                data: info,
                success: function (response) {
                    //console.log(response)
                    if (response != 0) {
                        var json = JSON.parse(response);
                        $("#pop_id").empty();
                        var html = "";
                        $.each(json, function (key, value) {
                            html += "<option value='" + value.id + "'>" + value.pop_name + "</option>";
                        });
                        $("#pop_id").html(html);
                    } else {
                        toastr.warning('Failed to fetch zone list. Try aging!', 'Warning');
                    }
                }
            });
        }

        function _payableFee() {
            var price = Number($('#package_id :selected').attr("data"));
            var previous_bill = Number($('#previous_bill').val());
            var signup_fee = Number($('#signup_fee').val());
            var permanent_discount = Number($('#permanent_discount').val());
            var discount = Number($('#discount').val());
            var billing_date = new Date();
            billing_date.setDate($('#billing_date').val());

            //console.log(30-$('#billing_date').val())
            var lastDayOfMonth = new Date();
            lastDayOfMonth.setDate(30);

            if (billing_date != "") {

                //var days = datediff(billing_date, lastDayOfMonth);
                var days = 31 - $('#billing_date').val();
                //console.log(days);

                var total = days * price / 30;
            }
            else {
                total = 0
            }

            if ($('#custom_bill').is(":checked")) {
                total = price;
                $('.bill_msg').html("This bill calculated from this current month.");
            } else {
                $('.bill_msg').html("");
            }

            var payable_amount = (total + signup_fee + previous_bill) - (permanent_discount + discount);

            $('#payable_amount').val(Math.round(payable_amount));
        }

        function _resellerId() {
            var info = "_token={{csrf_token()}}";
            $.ajax({
                type: "POST",
                url: "{{ route("get_reseller_id") }}",
                data: info,
                success: function (response) {
                    if (response !== 0) {
                        $("#reseller_username").val(response);
                    } else {
                        toastr.warning('Failed to fetch reseller id. Try aging!', 'Warning');
                    }
                },
                error: function (request, status, error) {
                    console.log(request.responseText);
                }
            });
        }

        function parseDate(str) {
            return new Date(str);
        }

        function datediff(first, second) {
            // Take the difference between the dates and divide by milliseconds per day.
            // Round to nearest whole number to deal with DST.
            return Math.round((second - first) / (1000 * 60 * 60 * 24));
        }
    </script>

    <link rel="stylesheet" type="text/css"
          href="{{ asset('app-assets/css/plugins/forms/validation/form-validation.css') }}">

    <script src="{{ asset('app-assets/js/scripts/forms/validation/form-validation.js') }}"
            type="text/javascript"></script>


@endsection
