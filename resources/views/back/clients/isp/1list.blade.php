@extends('layouts.app')

@section('title', 'Clients')

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
                <h3 class="content-header-title" id="tabOption">All Clients</h3>
            </div>
            <div class="content-header-right col-md-8 col-12">

                <ul class="nav nav-tabs float-md-right">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url("isp-clients") }}" >All Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route("isp-queue") }}" >Add Queue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route("isp-pppoe") }}" >Add PPPoE</a>
                    </li>
                    {{--<li class="nav-item">--}}
                        {{--<a class="nav-link" href="#">Import/Export</a>--}}
                    {{--</li>--}}
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
                                    @if(Auth::user()->id==1)
                                        <div class="card inner-card" style="margin:0;">
                                            <div class="card-body" style="padding: 6px 10px;">
                                                <div class="row" style="margin:0;">
                                                    <div class="col-md-3">
                                                        <label for="search_company_id"
                                                               class="control-label">Company/Reseller
                                                        </label>

                                                        <select class="form-control select2" id="search_company_id" required>
                                                            <option value="{{ auth()->user()->company_id }}"> {{ auth()->user()->name }} (O)</option>
                                                            @foreach($companies as $row)
                                                                <option value="{{ $row->auth_id }}">

                                                                    {{ $row->reseller_id }} ::

                                                                    {{ $row->reseller_name }}
                                                                    (R) </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="search_company_id" class="control-label">Status</label>

                                                        <select class="form-control select2" id="status" required>
                                                            <option value="">All</option>
                                                            <option value="active">Active</option>
                                                            <option value="inactive">Inactive</option>
                                                            <option value="locked">Locked</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group"  style="margin-top:25px;">
                                                            <button type="button" class="btn btn-primary mb-0 search">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" value="{{ Settings::company_id() }}" id="search_company_id">
                                    @endif

                                    <div class="text-center client_summery">
                                        <h4>
                                            <span>Total: <i
                                                        class="text-primary total">{{ $total_client }}</i></span>
                                            <span>Active: <i
                                                        class="text-success actived">{{ $active_client }}</i></span>
                                            <span>Inactive: <i
                                                        class="text-danger inactived">{{ $inactive_client }}</i></span>
                                            <span>Lock: <i
                                                        class="text-warning locked">{{ $locked_client }}</i></span>
                                        </h4>
                                    </div>


                                    <table id="datalist"
                                           class="table table-striped table-bordered table-hovered"
                                           style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th >SL</th>
                                            <th >ID/Name/Cell</th>
                                            <th >Zone/Address</th>
                                            <th >Package</th>
                                            <th >Joining</th>
                                            <th >Payment<br>Deadline</th>
                                            <th >Status</th>
                                            <th >locked status</th>
                                            <th >Action</th>
                                        </tr>
                                        </thead>
                                    </table>




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

<div class="modal fade text-left" id="doLocked" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white" id="ModalLabel"><span class="ltitle">Locked</span>Client</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="UnlockedLockForm" method="post">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="locked_id" name="locked_id"/>
                    <input type="hidden" id="is_locked" name="is_locked"/>

                    <div id="lockedArea">
                        <div class="row" style="margin-bottom: 20px">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <label for="lock_stability"> Time <span class="text-danger">*</span></label>

                                <div class="input-group">
                                    <input type="number" class="form-control text-center" min="1" value="6"
                                           name="lock_stability" id="lock_stability" required autocomplete="off">

                                    <div class="input-group-addon btn-info">Hours</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <label for="lock_sms_notification">
                                    <input type="checkbox" name="lock_sms_notification" id="lock_sms_notification">
                                    Sent SMS Notification
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="">
                                <label id="lockMsg" class="text-danger"></label>
                            </div>
                        </div>
                    </div>
                    <div id="unlockedArea" style="display: none;">
                        <label>Is payment has been paid?
                            <input type="radio" name="paymentPaid" value="1" required>Yes
                            <input type="radio" name="paymentPaid" value="0">No
                        </label>

                        <div id="inputDate" class="col-sm-12">
                            <label>Commitment Date
                                <input type="date" name="payment_commitment_date" value="{{ date("d/m/Y") }}"
                                       class="form-control  datepicker">
                            </label>
                        </div>
                        <h3 class="text-danger unlockedAreaMsg">Are you sure to unlock this client?</h3>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger save">Lock</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade text-left" id="SendSMS" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success white">
                <h4 class="modal-title white" id="myModalLabel8">Send SMS</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="SMSForm" method="post">
                <input type="hidden" id="sms_receiver_id" name="sms_receiver_id">
                <input type="hidden" value="isp" name="client_type">
                <div class="modal-body">
                    @csrf
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="sms_text" class="control-label pull-left">SMS Text <span class="text-danger">*</span>
                                    <button class="btn btn-info" id="chooseTemplate" type="button" style="    padding: 2px;   font-size: 11px;">Choose from template</button></label>

                                <label class="sms_count badge badge-warning" style="font-size: 11px;float: right;">0</label>
                                <textarea class="form-control" required name="sms_text" rows="10" id="sms_text" placeholder=""></textarea>
                            </div>
                        </div>

                        <div class="row" style="    margin-top: 10px;">

                            <div class="col-sm-12">
                                <label for="schedule_time">Schedule Time
                                </label>
                                <input type="text" class="datetimepicker form-control" value="{{ date("d/m/Y H:i") }}" name="schedule_time" id="schedule_time">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger save">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="sms_template_list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white" id="myModalLabel8">SMS Templates</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="col-md-12">

                    <div class="row " >

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class='text-center'>Sl. No.</th>
                                <th class='text-center'>Template Name</th>
                                <th class='text-center'>SMS Text</th>
                                <th class='text-center'>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($sms_templates as $key=>$row)
                                <tr>
                                    <td class='text-center'>{{ $key+1 }}</td>
                                    <td class='text-center'>{{ $row->template_name }}</td>
                                    <td class='text-center' >{{ $row->template_text }}</td>
                                    <td class='text-center'><button id="choose_sms_template" text="{{ $row->template_text }}" style="    padding: 5px;   font-size: 12px;" class="btn btn-success" type="button">Choose</button></td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<div class="modal fade text-left" id="sms_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning white">
                <h4 class="modal-title white" id="myModalLabel8">Sent Due SMS</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="DueSMSSent" method="post">
                @csrf
                <input type="hidden" name="sent_to" class="sent_to">
                <div class="modal-body">

                    <div class="col-md-12">
                        <div class="row" >
                            Sent To :  <b class="clientName"></b>
                        </div>
                        <div class="row" >
                            <textarea class="form-control due_sms_text" name="sms_text"></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-warning pull-right" type="submit">Send SMS</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
        var block_ele = $('.card:first-child');
        $(document).ready(function () {
            __startup();

            var table;

            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();
                blockLoad()
                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ url('save_client') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response) {
                        unblockLoad()
                        if (response == 1) {
                            $("#DataForm").trigger("reset");
                            toastr.success('Data Saved Successfully!', 'Success');
                            $("[href='#DataList']").tab("show");
                            _clientId();
                            _clientCount();
                            table.ajax.reload();

                        }
                        else {
                            toastr.warning('Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(".save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        unblockLoad()
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        $(".save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('submit', "#SMSForm", function (e) {
                e.preventDefault();

                $("#SMSForm .save").text("Sending...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ url('send_sms_from_client') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response == 1) {
                            $("#SendSMS").modal("hide");
                            $("#SMSForm ").trigger("reset");
                            toastr.success('Sent Successfully!', 'Success');
                        }
                        else {
                            toastr.warning('Failed. Try aging!', 'Warning');
                        }
                        $("#SMSForm .save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Failed. Try aging!', 'Warning');
                        $("#SMSForm .save").text("Sent").prop("disabled", false);
                    }
                });
            });

            $(document).on('submit', "#UnlockedLockForm", function (e) {
                e.preventDefault();
                var element = $(this);
                //$(element +" .save").text("Locking...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ url('lockedUnlockedClient') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response) {

                        console.log(response);
                        if (response != 11) {
                            $("#doLocked").modal("hide");
                            var client_id = ".clientId" + $('#locked_id').val();
                            //var is_locked = $(client_id).find(".lockCl").attr("is_locked");
                            var icons = "{{ asset("app-assets/images/active_icon.png") }}";
                            if (response == "quick_lock") {
                                icons = "{{ asset("app-assets/images/locked_icon.png") }}";
                                $(client_id).find(".lockCl").attr("is_locked", 2).html("<i class='ft-unlock'></i> Unlock");
                                toastr.success('Locked successfully!', 'Success');
                            } else if (response == "sch_lock") {
                                $(client_id).find(".lockCl").attr("is_locked", 20).html("<i class='ft-lock'></i> Cancel");
                                toastr.success('Sent to lock schedule successfully!', 'Success');
                            } else if (response == "sch_lock_cancel") {
                                $(client_id).find(".lockCl").attr("is_locked", 1).html("<i class='ft-lock'></i> Lock");
                                toastr.success('Schedule lock canceled successfully!', 'Success');
                            } else if (response == "quick_unlock_and_sch_lock") {
                                $(client_id).find(".lockCl").attr("is_locked", 20).html("<i class='ft-lock'></i> Cancel");
                                toastr.success('Unlocked and sent to schedule lock successfully!', 'Success');
                            } else {
                                $(client_id).find(".lockCl").attr("is_locked", 1).html("<i class='ft-lock'></i> Lock");
                                toastr.success('Unlocked successfully!', 'Success');
                            }

                            $(element).trigger("reset");
                            $(client_id).parents("tr").find("img").attr("src", icons);
                            _clientCount();
                        }
                        else {
                            toastr.warning('Data Cannot Saved. Try aging!', 'Warning');
                        }

                        //$(element +" .save").text("Lock").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        // $(element +" .save").text("Lock").prop("disabled", false);
                    }
                });
            });

            $(document).on('click', "#chooseTemplate", function () {
                $("#sms_template_list").modal("show");
            });
            $(document).on('click', "#choose_sms_template", function () {
                var sms = $(this).attr("text");
                $("#sms_template_list").modal("hide");
                $("#sms_text").val(sms).focus();
            });
            $(document).on('change input keyup focus blur', "#sms_text", function () {
                var txtCount = $(this).val().trim().length;
                $(".sms_count").html(txtCount);
            });
            $(document).on('click', '.search', function () {
                _clientCount();
                clientData();
            });
            $(document).on('click', '.send_sms', function () {
                $("#sms_receiver_id").val($(this).attr("id"));
                $("#SendSMS").modal("show");
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

            $(document).on('click', '.lockCl', function () {
                $("#inputDate").hide();
                var is_locked = $(this).attr("is_locked");
                $("#locked_id").val($(this).attr("id"));
                $("#is_locked").val(is_locked);
                $("#doLocked").modal("show");
                $("#LockForm").trigger("reset");
                if (is_locked > 2) {
                    $("#lockedArea").hide();
                    $("#unlockedArea").show();
                    $(".unlockedAreaMsg").html("Are you sure to cancel this schedule lock?");
                    $("#UnlockedLockForm .save").text("Yes");
                    $('input[name="paymentPaid"]').removeAttr("required", "required");
                }
                else if (is_locked != 2) {
                    $(".ltitle").text("Lock");
                    $("#UnlockedLockForm .save").text("Lock");
                    $("#lockedArea").show();
                    $("#unlockedArea").hide();
                    $('input[name="paymentPaid"]').removeAttr("required", "required");
                } else {
                    $('input[name="paymentPaid"]').attr("required", "required");
                    $(".ltitle").text("Unlock");
                    $("#UnlockedLockForm .save").text("Yes");
                    $("#lockedArea").hide();
                    $("#unlockedArea").show();
                    $(".unlockedAreaMsg").html("Are you sure to unlock this client?");
                }
                $("#lockMsg").html("**Locked for " + $("#lock_stability").val() + " hours instantly.");
            });

            $(document).on('change', '#lock_sms_notification', function () {
                if ($(this).is(":checked")) {
                    $("#lockMsg").html("**Locked for " + $("#lock_stability").val() + " hours after 30 minutes.");
                } else {
                    $("#lockMsg").html("**Locked for " + $("#lock_stability").val() + " hours instantly.");
                }
            });

            $(document).on('change keyup', '#lock_stability', function () {
                if ($('#lock_sms_notification').is(":checked")) {
                    $("#lockMsg").html("**Locked for " + $("#lock_stability").val() + " hours after 30 minutes.");
                } else {
                    $("#lockMsg").html("**Locked for " + $("#lock_stability").val() + " hours instantly.");
                }
            });

            $(document).on('change', 'input[name="paymentPaid"]', function () {
                if ($(this).val() == 1) {
                    $("#inputDate").hide();
                } else {
                    $("#inputDate").show();
                }
            });

            $(document).on('click', '.deleteData', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{csrf_token()}}';

                if (confirm("Are you sure you want to delete this?")) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('client_delete') }}",
                        data: data,
                        success: function (response) {
                            console.log(response);
                            if (response == 1) {
                                toastr.success('Data removed Successfully!', 'Success');
                                element.parents("tr").animate({backgroundColor: "#003"}, "slow").animate({opacity: "hide"}, "slow");

                                _clientCount();
                            }
                            else {
                                toastr.warning('Data Cannot Removed. Try aging!', 'Warning');
                            }
                        },
                        error: function (request, status, error) {
                            console.log(request.responseText);
                            toastr.warning('Server Error. Try aging!', 'Warning');
                        }
                    });
                }
                return false;

            });

            //zone call
            $(document).on('change', '#network_id', function () {
                var element = $(this);
                _pop(element);
            });

            //node call
            $(document).on('change', '#zone_id', function () {
                var element = $(this);
                _node(element);
            });

            $(document).on('click', '.dueSMS', function () {
                blockLoad(block_ele);
                var element = $(this);
                var id = element.attr("id");
                var info = 'id=' + id +"&_token={{ csrf_token() }}";
                //element.html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{ url('isp_client_due_sms') }}",
                    data: info,
                    success: function (response) {
                        unblockLoad(block_ele);
                        var json = JSON.parse(response);
                        console.log(json)
                        if(json.status==true){
                            $("#sms_view").modal("show");
                            var name=element.closest("tr").find("td:nth-child(2)").html();
                            $("#sms_view .clientName").html(name.replace("<br>"," :: ").replace("<br>"," :: "))
                            $("#sms_view .sent_to").val(json.sent_to)
                            $("#sms_view textarea").text(json.sms)
                        } else {
                            toastr.warning( 'Dues not found. Try aging!', 'Warning');
                        }


                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        unblockLoad();
                    }
                });
            });


            $(document).on('submit', "#DueSMSSent", function (e) {
                e.preventDefault();
                blockLoad()
                var element= this;
                $(".sent",element).text("Sending...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ url('isp_client_due_sms_save') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        unblockLoad();
                        $(".sent",element).text("Sent SMS").prop("disabled", false);
                        if (response == 1) {
                            $("#sms_view").modal("hide");
                            toastr.success( 'SMS successfully sent', 'Success');
                        }
                        else {
                            toastr.warning( 'SMS sending failed. Try aging!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        unblockLoad();
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(".save").text("Save").prop("disabled", false);
                    }
                });
            });

        });

        function __startup() {
            clientData();
            _pop($('#network_id'));
            _zone($('#network_id'));
        }

        function clientData(){
            if(localStorage.getItem("isp_client_status")){
                $("#status").val(localStorage.getItem("isp_client_status")).trigger("change");
            }
            table = $('#datalist').removeAttr('width').DataTable
            ({
                "bAutoWidth": false,
                "destroy": true,
                "bProcessing": true,
                "serverSide": true,
                "responsive": false,
                "aaSorting": [[0, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [
                    {
                        "targets": [7],
                        "visible": false
                    }, {
                        "targets": [4, 5, 6, 8],
                        "orderable": false
                    }, {
                        "targets": [0, 6, 8],
                        className: "text-center"
                    }],
                "ajax": {
                    url: "{{ url('client_datalist') }}",
                    type: "post",
                    "data": {
                        _token: "{{csrf_token()}}",
                        company_id:  $("#search_company_id").val(),
                        status: localStorage.getItem("isp_client_status") == null ? $("#status").val() : localStorage.getItem("isp_client_status")
                    },
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {
                            var status = "", lock;
                            if (jsonData.data[i][6] == 1) {
                                status = "<img src='{{ asset("app-assets/images/active_icon.png") }}' style='width: 20px; height: 20px;' title='Active'> ";
                                lock = "Lock";
                            } else if (jsonData.data[i][6] == 0) {
                                status = "<img src='{{ asset("app-assets/images/deactive_icon.png") }}' style='width: 20px; height: 20px;' title='Deactive'> ";
                                lock = "Lock";
                            } else {
                                lock = "Unlock";
                                status = "<img src='{{ asset("app-assets/images/locked_icon.png") }}' style='width: 20px; height: 20px;' title='Locked'> ";
                            }
                            if (jsonData.data[i][7] == 3) {
                                jsonData.data[i][6] = 20;
                                lock = "Cancel";
                            }
                            var url = '{{ route("isp-client-update", ":id") }}';
                            url = url.replace(':id',jsonData.data[i][0]);
                            jsonData.data[i][8] =
                            '<button class="btn btn-outline-purple btn-sm dropdown-toggle clientId' + jsonData.data[i][0] + '" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                            'Action'+
                            '</button>'+
                            '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">'+
                            '<a id="' + jsonData.data[i][0] + '" class="send_sms dropdown-item text-success" href="#"><span class="ft-message-circle"></span> SMS</a>'+
                            '<a id="' + jsonData.data[i][0] + '" class="lockCl dropdown-item text-info" href="#" is_locked="' + jsonData.data[i][6] + '"><span class="ft-lock"></span> ' + lock + '</a>'+
                            '<a href="' + url + '" class="dropdown-item text-primary"><span class="ft-edit"></span> Edit</a>'+
                            '<a id="' + jsonData.data[i][1].split("<br>")[0] + '" class="dropdown-item text-warning dueSMS"><span class="la la-envelope"></span> Due SMS</a>'+
                            '<a id="' + jsonData.data[i][0] + '" class="deleteData dropdown-item text-danger" href="#"><span class="ft-trash"></span> Del</a>'+
                            '</div>';

                            jsonData.data[i][6] = status;
                        }
                        localStorage.clear();
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        localStorage.clear();
                    }
                }
            });

            table.on('order.dt search.dt', function () {
                table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }


        function _pop(data) {
            var id = data.val();
            var info = 'id=' + id + "&_token={{csrf_token()}}";
            $.ajax({
                type: "POST",
                url: "{{ url('pop_by_network') }}",
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
                        _zone($("#pop_id"))
                    } else {
                        toastr.warning('Failed to fetch zone list. Try aging!', 'Warning');
                    }
                }
            });
        }
        function _zone(data) {
            var id = data.val();
            var info = 'id=' + id + "&_token={{csrf_token()}}";
            $.ajax({
                type: "POST",
                url: "{{ url('zone_by_pop') }}",
                data: info,
                success: function (response) {
                    //console.log(response)
                    if (response != 0) {
                        var json = JSON.parse(response);
                        $("#zone_id").empty();
                        var html = "";
                        $.each(json, function (key, value) {
                            html += "<option value='" + value.id + "'>" + value.zone_name_en + "</option>";
                        });
                        $("#zone_id").html(html);
                        _node($('#zone_id'));
                    } else {
                        toastr.warning('Failed to fetch zone list. Try aging!', 'Warning');
                    }
                }
            });
        }

        function _node(data) {
            var id = data.val();
            var info = 'id=' + id + "&_token={{csrf_token()}}";
            $.ajax({
                type: "POST",
                url: "{{ url('node_by_zone') }}",
                data: info,
                success: function (response) {
                    // console.log(response)
                    if (response != 0) {
                        var json = JSON.parse(response);
                        $("#node_id").empty();
                        var html = "";
                        $.each(json, function (key, value) {
                            html += "<option value='" + value.id + "'>" + value.node_name + "</option>";
                        });
                        $("#node_id").html(html);
                        _box($("#node_id"));
                    } else {
                        toastr.warning('Failde to fetch node list. Try aging!', 'Warning');
                    }
                }
                ,
                error: function (request, status, error) {
                    console.log(request.responseText);
                }
            });
        }

        function _box(data) {
            var id = data.val();
            var info = 'id=' + id + "&_token={{csrf_token()}}";
            $.ajax({
                type: "POST",
                url: "{{ url('box_by_node') }}",
                data: info,
                success: function (response) {
                    // console.log(response)
                    if (response != 0) {
                        var json = JSON.parse(response);
                        $("#box_id").empty();
                        var html = "";
                        $.each(json, function (key, value) {
                            html += "<option value='" + value.id + "'>" + value.box_name + "</option>";
                        });
                        $("#box_id").html(html);
                    } else {
                        toastr.warning('Failed to fetch box list. Try aging!', 'Warning');
                    }
                }
                ,
                error: function (request, status, error) {
                    console.log(request.responseText);
                }
            });
        }


        function _clientCount() {
            var info = "_token={{csrf_token()}}&company_id="+$("#search_company_id").val();
            $.ajax({
                type: "POST",
                url: "{{ url('get_client_count') }}",
                data: info,
                success: function (response) {
                    var json = JSON.parse(response);
                    var total = Number(json.active)+Number(json.inactive)+Number(json.locked);
                    $(".client_summery .total").html(total);
                    $(".client_summery .actived").html(json.active);
                    $(".client_summery .inactived").html(json.inactive);
                    $(".client_summery .locked").html(json.locked);
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

        var _validFileExtensions = [".xls",".xlsx", ".csv"];
        function Validate(oForm) {
            var arrInputs = $("#file");
            for (var i = 0; i < arrInputs.length; i++) {
                var oInput = arrInputs[i];
                if (oInput.type == "file") {
                    var sFileName = oInput.value;
                    if (sFileName.length > 0) {
                        var blnValid = false;
                        for (var j = 0; j < _validFileExtensions.length; j++) {
                            var sCurExtension = _validFileExtensions[j];
                            if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                                blnValid = true;
                                $(".excel_img").show();
                                $(".fileName").html(sFileName.split(/(\\|\/)/g).pop());
                                break;
                            }
                        }

                        if (!blnValid) {
                            arrInputs.val('');
                            $(".excel_img").hide();
                            $(".fileName").html('');
                            alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                            return false;
                        }
                    }
                }
            }
            if(arrInputs.val()==""){
                $(".excel_img").hide();
                $(".fileName").html('');
            }
            return true;
        }
    </script>

@endsection
