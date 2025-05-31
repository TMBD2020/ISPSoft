
<!-- lock modal -->
<div class="modal fade text-left" id="doLocked" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">

    </div>
</div>

<!-- send general sms modal -->
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

<!-- sms templete modal -->
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

<!-- send due sms modal -->
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
    table#datalist tr td {
       padding: 5px !important;
    }
    table#datalist tr td:nth-child(7) {
        vertical-align: middle !important;
    }
</style>



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
                    url: "{{ route('save_client') }}",
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
                    url: "{{ route('send_sms_from_client') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response) {
                        //console.log(response);
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
                    url: "{{ route('lockedUnlockedClient') }}",
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
                var element = $(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('client-widget') }}",
                    data: "_token="+"{{ csrf_token() }}"+"&action=lock",
                    success: function (response) {
                        $("#doLocked .modal-dialog").html(response);
                        $("#inputDate").hide();
                        var is_locked = element.attr("is_locked");
                        $("#locked_id").val(element.attr("id"));
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

                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        // $(element +" .save").text("Lock").prop("disabled", false);
                    }
                });

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


            $(document).on('change', '.action_status', function () {
                var element = $(this);
                var action;
                if(element.val()==0){
                   if(confirm("Are you sure to inactive this client?")){
                      $.ajax({
                          type: "POST",
                          url: "{{ route('client_status_update') }}",
                          data:  {
                              "_token": "{{ csrf_token() }}",
                              "status": 0,
                              "id": element.attr("id"),
                          } ,
                          success: function (response) {
                                if(response["status"]=="success"){
                                    toastr.success('Client successfully inactivated', 'Success');
                              }else{
                                    element.val(element.attr("data"));
                                    toastr.warning('Request failed. Try aging!', 'Warning');
                                }
                          },
                          error: function (request, status, error) {
                              console.log(request.responseText);
                              toastr.warning('Server Error. Try aging!', 'Warning');
                              // $(element +" .save").text("Lock").prop("disabled", false);
                          }
                      });
                       return false
                   }
                   else{
                       element.val(element.attr("data"));
                       return false
                   }
                }else if(element.val()==1){
//                if(element.attr("data")==2){
//                alert("You cannot active this client");
//                                    element.val(element.attr("data"));
//                return false;
//                }
                    action="&action=active&id="+element.attr("id")+"&details="+element.attr("details")+"&aid="+element.attr("activeId");
                }else {
                    action="&action=lock&id="+element.attr("id");
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('client-widget') }}",
                    data: "_token="+"{{ csrf_token() }}"+action,
                    success: function (response) {
                      
                        $("#doLocked .modal-dialog").html(response);
                        $("#doLocked").modal("show");
                        if(element.val()!=1 && element.val()!=0) {
                            $("#inputDate").hide();
                            var is_locked = element.attr("is_locked");

                            $("#locked_id").val(element.attr("id"));
                            $("#is_locked").val(is_locked);
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
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        // $(element +" .save").text("Lock").prop("disabled", false);
                    }
                });

            });

            $(document).on('change kyeup input', 'input[name="receive_amount"]', function (e) {
                var receive=$('input[name="receive_amount"]');
                var commitment=$('input[name="commitment_date"]');
                if(receive.val()){
                    receive.attr("required","required");
                    commitment.removeAttr("required");
                    return false;
                }else{
                    commitment.attr("required","required");
                    receive.removeAttr("required");
                    return false;
                }
            });
            $(document).on('change kyeup input', 'input[name="commitment_date"]', function (e) {
                var receive=$('input[name="receive_amount"]');
                var commitment=$('input[name="commitment_date"]');
                if(receive.val()){
                    receive.attr("required","required");
                    commitment.removeAttr("required");
                    return false;
                }else{
                    commitment.attr("required","required");
                    receive.removeAttr("required");
                    return false;
                }
            });


            $(document).on('submit', "#ActiveClient", function (e) {
                e.preventDefault();

              if(confirm("Are you sure to active this client?")){
                  var element= this;
                //  if($("input[name='payable']",element).val()>0){
                      $.ajax({
                          type: "POST",
                          url: "{{ route('create_isp_reactive_client_bill') }}",
                          data: new FormData(this),
                          processData: false,
                          contentType: false,
                          cache: false,
                          success: function (response)
                          {
                          console.log(response);
                              if (response == 1) {
                                  $("#doLocked").modal("hide");
                                  {{--$.ajax({--}}
                                      {{--type: "POST",--}}
                                      {{--url: "{{ route('client_status_update') }}",--}}
                                      {{--data:  {--}}
                                          {{--"_token": "{{ csrf_token() }}",--}}
                                          {{--"status": 1,--}}
                                          {{--"id": $("input[name='id2']",element).val(),--}}
                                      {{--} ,--}}
                                      {{--success: function (response) {--}}

                                      {{--},--}}
                                      {{--error: function (request, status, error) {--}}
                                          {{--console.log(request.responseText);--}}
                                          {{--toastr.warning('Server Error. Try aging!', 'Warning');--}}
                                          {{--// $(element +" .save").text("Lock").prop("disabled", false);--}}
                                      {{--}--}}
                                  {{--});--}}
                                 toastr.success( 'Client successfully activated', 'Success');
                              }
                              else {
                                  toastr.warning( 'Request failed. Try aging!', 'Warning');
                              }
                          },
                          error: function (request, status, error) {
                              console.log(request.responseText);
                              toastr.warning( 'Server Error. Try aging!', 'Warning');
                          }
                      });
                  {{--}else{--}}
                      {{--$("#doLocked").modal("hide");--}}
                      {{--$.ajax({--}}
                          {{--type: "POST",--}}
                          {{--url: "{{ route('client_status_update') }}",--}}
                          {{--data:  {--}}
                              {{--"_token": "{{ csrf_token() }}",--}}
                              {{--"status": 1,--}}
                              {{--"id": $("input[name='id2']",element).val(),--}}
                          {{--} ,--}}
                          {{--success: function (response) {--}}
                            {{--if(response["status"]=="success"){--}}
                                {{--toastr.success( 'Client successfully activated', 'Success');--}}
                            {{--} else {--}}
                                {{--toastr.warning( 'Request failed. Try aging!', 'Warning');--}}
                            {{--}--}}
                          {{--},--}}
                          {{--error: function (request, status, error) {--}}
                              {{--console.log(request.responseText);--}}
                              {{--toastr.warning('Server Error. Try aging!', 'Warning');--}}
                              {{--// $(element +" .save").text("Lock").prop("disabled", false);--}}
                          {{--}--}}
                      {{--});--}}
                  {{--}--}}


              }return false;
            });

            $(document).on('click', '.dismiss_status', function () {
                var id = $("#doLocked input[name='id2']").val();
                var data =  $('.action_status[id="'+id+'"]').attr("data");
                $('.action_status[id="'+id+'"]').val(data);
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
                        url: "{{ route('client_delete') }}",
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
                    url: "{{ route('isp_client_due_sms') }}",
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
                    url: "{{ route('isp_client_due_sms_save') }}",
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
           // _pop($('#network_id'));
           // _zone($('#network_id'));
        }
 function _payableFee() {
            var permanent_discount = Number($('.permanent_discount').val());
            var price = Number($('#package_id :selected').attr("data"))-permanent_discount;

            var previous_bill = Number($('#previous_bill').val());
            var discount = Number($('.discount_amount').val());

            var today = new Date(),
             endDate= new Date(today.getFullYear(), today.getMonth() + 1, 0);

            if ($('#custom_bill').is(":checked")) {
                total = price;
            } else {
                var days = endDate.getDate()-today.getDate();
                var total = days * price / 30;
                total=Math.round(total);
            }
            var payable_amount = (total + previous_bill) -  discount;

            $('.dayWiseBill').val(total);
            $('.payable_amount').val(Math.round(payable_amount));
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
//                        "targets": [7],
//                        "visible": false
                    }, {
                        "targets": [4, 5, 6],
                        "orderable": false
                    }, {
                        "targets": [0,4,5, 6],
                        className: "text-center"
                    }],
                "ajax": {
                    url: "{{ route('client_datalist') }}",
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
                                //status = "<img src='{{ asset("app-assets/images/active_icon.png") }}' style='width: 20px; height: 20px;' title='Active'> ";
                                lock = "Lock";
                            } else if (jsonData.data[i][6] == 0) {
                               // status = "<img src='{{ asset("app-assets/images/deactive_icon.png") }}' style='width: 20px; height: 20px;' title='Deactive'> ";
                                lock = "Lock";
                            } else {
                                lock = "Unlock";
                                //status = "<img src='{{ asset("app-assets/images/locked_icon.png") }}' style='width: 20px; height: 20px;' title='Locked'> ";
                            }
                            var statusHTML='<select>' +
                                    '<option value="1" '+( jsonData.data[i][6] == 1 ? "selected":"") +'>Active<option>' +
                                    '<option value="0" '+( jsonData.data[i][6] == 0 ? "selected":"") +'>In-active<option>' +
                                    '<option value="2" '+( jsonData.data[i][6] == 2 ? "selected":"") +'>Locked<option>' +
                                    '</select>';
                            if (jsonData.data[i][7] == 3) {
                                jsonData.data[i][6] = 20;
                                lock = "Cancel";
                            }
                            {{--var url = '{{ route("isp-client-update", ":id") }}';--}}
                            {{--url = url.replace(':id',jsonData.data[i][0]);--}}
//                            jsonData.data[i][8] =
//                                    '<button class="btn btn-outline-purple btn-sm dropdown-toggle clientId' + jsonData.data[i][0] + '" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
//                                    'Action'+
//                                    '</button>'+
//                                    '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">'+
//                                    '<a id="' + jsonData.data[i][0] + '" class="send_sms dropdown-item text-success" href="#"><span class="ft-message-circle"></span> SMS</a>'+
//                                    '<a id="' + jsonData.data[i][0] + '" class="lockCl dropdown-item text-info" href="#" is_locked="' + jsonData.data[i][6] + '"><span class="ft-lock"></span> ' + lock + '</a>'+
//                                    '<a href="' + url + '" class="dropdown-item text-primary"><span class="ft-edit"></span> Edit</a>'+
//                                    '<a id="' + jsonData.data[i][1].split("<br>")[0] + '" class="dropdown-item text-warning dueSMS"><span class="la la-envelope"></span> Due SMS</a>'+
//                                    '<a id="' + jsonData.data[i][0] + '" class="deleteData dropdown-item text-danger" href="#"><span class="ft-trash"></span> Del</a>'+
//                                    '</div>';

//                            jsonData.data[i][6] = statusHTML;
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

          
        }

        function _pop(data) {
            var id = data.val();
            var info = 'id=' + id + "&_token={{csrf_token()}}";
            $.ajax({
                type: "POST",
                url: "{{ route('pop_by_network') }}",
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
                url: "{{ route('zone_by_pop') }}",
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
                url: "{{ route('node_by_zone') }}",
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
                url: "{{ route('box_by_node') }}",
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
                url: "{{ route('get_client_count') }}",
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
