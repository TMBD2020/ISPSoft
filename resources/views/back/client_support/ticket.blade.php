@extends('layouts.app')

@section('title', 'Tickets')

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
                <h3 class="content-header-title"><span id="tabOption">All Tickets</span></h3>
            </div>
            <div class="content-header-right col-md-8 col-12">

                <ul class="nav nav-tabs float-md-right">
                    <li class="nav-item">
                        <a class="nav-link active" onclick="document.getElementById('tabOption').innerHTML='All Tickets',AllTicket()" id="base-tab1" data-toggle="tab" aria-controls="DataList" href="#DataList" aria-expanded="true">
                            All Tickets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="document.getElementById('tabOption').innerHTML='Pending Tickets',PendingTicketList()" id="base-tab1" data-toggle="tab" aria-controls="PendingTickets" href="#PendingTickets" aria-expanded="true">
                            Pending Tickets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="document.getElementById('tabOption').innerHTML='Closed Tickets',ClosedTicketList()" id="base-tab1" data-toggle="tab" aria-controls="ClosedTickets" href="#ClosedTickets" aria-expanded="true">
                            Closed Tickets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link addnew" onclick="document.getElementById('tabOption').innerHTML='Add New Ticket',document.getElementById('filter').style.display='none'" id="base-tab2" data-toggle="tab" aria-controls="operation" href="#operation" aria-expanded="false">
                            Add New</a>
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

                                   <div class="text-center filter" id="filter">
                                       <button id="all" value="all" class="btn btn-default " style="border: 1px solid #c1c1c1;">All</button>
                                       <button id="general" value="general" class="btn btn-success ">General</button>
                                       <button id="line_shift" value="line_shift" class="btn btn-primary">Line Shift</button>
                                       <button id="upcoming_client" value="upcoming_client" class="btn btn-info">Upcoming Client</button>
                                       <button id="package_change" value="package_change" class="btn btn-warning">Package Change</button>
                                   </div>
                                    <!--
                                    <label>Filter Tickets: </label>
                                    <select id="ticket_type">
                                        <option value="all">All</option>
                                        <option value="general">General</option>
                                        <option value="line_shift">LIne Shift</option>
                                        <option value="upcoming_client">Upcoming Client</option>
                                        <option value="package_change">Package Change</option>
                                    </select>
                                    -->
                                    <div class="tab-content pt-1">
                                        <div role="tabpanel" class="tab-pane active" id="DataList" aria-expanded="true" aria-labelledby="base-tab1">

                                            <div class="table-responsive">
                                                <table id="datalist" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Ticket No</th>
                                                        <th>ID/Name</th>
                                                        <th>Subject</th>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                        </div>

                                        <div role="tabpanel" class="tab-pane" id="PendingTickets" aria-expanded="true" aria-labelledby="base-tab1">
                                            <div class="table-responsive">
                                                <table id="PendingTicketList" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Ticket No</th>
                                                        <th>ID/Name</th>
                                                        <th>Subject</th>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>

                                        <div role="tabpanel" class="tab-pane" id="ClosedTickets" aria-expanded="true" aria-labelledby="base-tab1">
                                            <div class="table-responsive">
                                                <table id="ClosedTicketList" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Ticket No</th>
                                                        <th>ID/Name</th>
                                                        <th>Subject</th>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="operation" aria-labelledby="base-tab2">

                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="col-lg-8 col-xs-12  col-md-6 col-sm-12">

                                                        <form id="DataForm" method="post" class="form-horizontal">
                                                            {{ csrf_field() }}

                                                            <input type="hidden" id="action" name="action">
                                                            <input type="hidden" id="id" name="id">

                                                            <div class="row">
                                                                <label for="ref_client_id" class="col-sm-4 control-label">Client <span class="text-danger">*</span></label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control select2" name="ref_client_id" id="ref_client_id" required>
                                                                        @foreach($clients as $row)
                                                                            <option value="{{ $row->id }}" >{{ $row->client_id }} :: {{ $row->client_name }}</option>
                                                                        @endforeach
                                                                    </select>
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
                                                                <label for="subject" class="col-sm-4 control-label">Subject <span class="text-danger">*</span></label>
                                                                <div class="col-sm-8">
                                                                   <input type="text" autocomplete="off" name="subject" id="subject" class="form-control" required>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <label for="complain" class="col-sm-4 control-label">Complain <span class="text-danger">*</span></label>
                                                                <div class="col-sm-8">
                                                                    <textarea name="complain" id="complain" class="form-control" required></textarea>
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

<!-- Modal -->
<div class="modal fade text-left" id="ticketDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success white">
                <h4 class="modal-title white" id="myModalLabel8">Ticket [No-<span class="ticket_no"></span>]</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <table class="ticketInfo">
                        <tr>
                            <th>Client</th>
                            <th>:</th>
                            <td class="client"></td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <th>:</th>
                            <td class="department"></td>
                        </tr>
                        <tr>
                            <th>Subject</th>
                            <th>:</th>
                            <td class="subject"></td>
                        </tr>
                        <tr>
                            <th>Complain</th>
                            <th>:</th>
                            <td class="complain"></td>
                        </tr>
                        <tr>
                            <th>Opening Time</th>
                            <th>:</th>
                            <td class="odate"></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <th>:</th>
                            <td class="status"></td>
                        </tr>
                        <tr class="closeRow">
                            <th>Closing Time</th>
                            <th>:</th>
                            <td class="cdate"></td>
                        </tr>
                    </table>
<hr>
                    <div class="comments">

                            <form id="TicketCommentform" method="post">
                                @csrf
                                <input type="hidden" name="ticket_id" id="ticket_no">
                                <div class="row">
                                    <div class="col-md-8">
                                        <textarea class="form-control" id="comment_text" name="comment_text" required></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn grey btn-primary comment">Comment</button>
                                    </div>

                                </div>
                            </form>
                        <div  class="comment_area" style="display: none;">
                            <hr>
                            <h5>Comments:</h5>
                            <table style="width:100%" class="comment_list"></table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--button type="button" class="btn grey btn-secondary" data-dismiss="modal">Window Close</button-->
            </div>
        </div>
    </div>
</div>

<!-- END: Content-->

<style>
    #DataForm .row{
        margin-top: 10px;
    }
    .comments table td{
       border-bottom: 1px dotted #ddd;
        padding-bottom: 10px;
    }
    .comment_area img{
        border-radius: 50%;
    }
    .comment_list td{
        vertical-align: top;
    }
    .pending_btn{
        width: 91px;
        text-align:left
    }
    .closed_btn{
        width: 91px;
    }
    .ticketInfo th, .ticketInfo td{
        vertical-align: top;
    }
</style>
@endsection

@section("page_script")
    <script type="text/javascript">
        var table, table2, table3, filter;
        function PendingTicketList(e){
            filterTicket("PendingTicketList(this)");
            if(e!=undefined){
                filter= e.value;
                if(filter=="all"){
                    filter="";
                }
            }else{
                filter=""
            }
            table2 = $('#PendingTicketList').DataTable
            ({
                "destroy": true,
                //"retrieve": true,
                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [[0, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[6],
                    "orderable": false
                },{
                    "targets":[6,0,1],
                    className: "text-center"
                } ],
                "ajax": {
                    url: "{{ route("pending_ticket_list") }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}", ticket_type: filter},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {
                            var status = "disabled";
                            if(jsonData.data[i][5]==1){
                                status = "";
                            }else{
                                status = "disabled";
                            }
                            jsonData.data[i][6] = '<div class="btn-group align-top" role="group">' +
                                    '<button  id=' + jsonData.data[i][0] + ' class="showData edit'+jsonData.data[i][0]+' btn btn-primary btn-sm badge">' +
                                    'View</button>' +
                                    '<button  id=' + jsonData.data[i][0] + ' '+status+' class="deleteData btn btn-danger btn-sm badge">' +
                                    'Close</button>' +
                                    '</div>';
                            jsonData.data[i][5] = jsonData.data[i][5]==1? "<span class='btn btn-warning btn-sm pending_btn'>Pending</span>" : "<span class='btn btn-sm closed_btn btn-danger'>Closed</span>";
                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                },
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    //debugger;
                    var index = iDisplayIndexFull + 1;
                    $("td:first", nRow).html(index);
                    return nRow;
                }
            });

        }
        function ClosedTicketList(e){
            filterTicket("ClosedTicketList(this)");
            if(e!=undefined){
                filter= e.value;
                if(filter=="all"){
                    filter="";
                }
            }else{
                filter="";
            }
            table3 = $('#ClosedTicketList').DataTable
            ({
                "destroy": true,
                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [[0, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[6],
                    "orderable": false
                },{
                    "targets":[6,0,1],
                    className: "text-center"
                } ],
                "ajax": {
                    url: "{{ route("closed_ticket_list") }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}", ticket_type: filter},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {
                            var status = "disabled";
                            if(jsonData.data[i][5]==1){
                                status = "";
                            }else{
                                status = "disabled";
                            }
                            jsonData.data[i][6] = '<div class="btn-group align-top" role="group">' +
                                    '<button  id=' + jsonData.data[i][0] + ' class="showData edit'+jsonData.data[i][0]+' btn btn-primary btn-sm badge">' +
                                    'View</button>' +
                                    '</div>';
                            jsonData.data[i][5] = jsonData.data[i][5]==1? "<span class='btn btn-warning btn-sm pending_btn'>Pending</span>" : "<span class='btn btn-sm closed_btn btn-danger'>Closed</span>";
                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                },
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    //debugger;
                    var index = iDisplayIndexFull + 1;
                    $("td:first", nRow).html(index);
                    return nRow;
                }
            });

        }
        function AllTicket(e){
            filterTicket("AllTicket(this)");
            if(e!=undefined){
                filter= e.value;
                if(filter=="all"){
                    filter="";
                }
            }else{
                filter=""
            }

            table = $('#datalist').DataTable
            ({

                "destroy":true,
                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [[0, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[6],
                    "orderable": false
                },{
                    "targets":[6,0,1],
                    className: "text-center"
                } ],
                "ajax": {
                    url: "{{ route("ticket_datalist") }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}", ticket_type: filter},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {
                            var status = "disabled";
                            if(jsonData.data[i][5]==1){
                                status = "";
                            }else{
                                status = "disabled";
                            }
                            jsonData.data[i][6] = '<div class="btn-group align-top" role="group">' +
                                    '<button  id=' + jsonData.data[i][0] + ' class="showData edit'+jsonData.data[i][0]+' btn btn-primary btn-sm badge">' +
                                    'View</button>' +
                                    '<button  id=' + jsonData.data[i][0] + ' '+status+' class="deleteData btn btn-danger btn-sm badge">' +
                                    'Close</button>' +
                                    '</div>';
                            jsonData.data[i][5] = jsonData.data[i][5]==1? "<span class='btn btn-warning btn-sm pending_btn'>Pending</span>" : "<span class='btn btn-sm closed_btn btn-danger'>Closed</span>";
                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                },
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    //debugger;
                    var index = iDisplayIndexFull + 1;
                    $("td:first", nRow).html(index);
                    return nRow;
                }
            });
        }
        function filterTicket(func){
            console.log(filter)
            $(".filter").show();
            $("#all").attr("onclick",func);
            $("#general").attr("onclick",func);
            $("#upcoming_client").attr("onclick",func);
            $("#line_shift").attr("onclick",func);
            $("#package_change").attr("onclick",func);
        }
        $(document).ready(function () {
            AllTicket();

            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route("save_ticket") }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        //console.log(response);
                        if (response == 1) {
                            $("#DataForm").trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
                            $("[href='#DataList']").tab("show");
                            $(".filter").show();
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

            $(document).on('submit', "#TicketCommentform", function (e) {
                e.preventDefault();

                $(".comment").text("Sending...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route("save_ticket_comment") }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        console.log(response);
                        if (response) {
                            $("#TicketCommentform").trigger("reset");
                            toastr.success('Send Successfully!','Success');;
                            var comments = (response);
                            if(comments){
                                $(".comment_area").show();

                                    $('<tr>'+
//                                            '<td><img src="'+comments.photo+'" width="30" class="img-round"></td>'+
                                            '<td>'+
                                            '<b class="text-primary">'+comments.username+'</b> '+ comments.comment_text +
                                            '<br>'+
                                            '<i style="font-size: 12px;"> <b>'+comments.comment_date+'</i>'+
                                            '</b></td>'+

                                            '</tr>').appendTo(
                                            ".comment_list");


                                $('.comment_area').scrollTop($('.comment_area')[0].scrollHeight);
                            }
                        }
                        else {
                           toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(".comment").text("Comment").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(".comment").text("Comment").prop("disabled", false);
                    }
                });
            });

            $(document).on('click', '.showData', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{csrf_token()}}';
                $(element).html('<i class="ft-loader"></i>').prop("disabled",true);

                $.ajax({
                    type: "POST",
                    url: "{{ route("view_ticket") }}",
                    data: data,
                    success: function (response) {
//                        console.log(response);
                        if (response) {
                            $(element).html('View').prop("disabled",false);
                            var json = (response);
                            var ticket = json.ticket;
                            var comments = json.comments;
                            $("#ticketDetails").modal("show");
                            $(".ticket_no").html(ticket.ticket_no);
                            $("#ticket_no").val(ticket.ticket_no);
                            $(".subject").html(ticket.subject);
                            $(".complain").html(ticket.complain);
                            $(".odate").html(ticket.ticket_datetime);
                            if(ticket.close_datetime){
                                $(".closeRow").show();
                                $(".cdate").html(ticket.close_datetime);
                            }else{
                                $(".closeRow").hide();
                            }
                            $(".status").html(ticket.ticket_status==1?"Pending":"Closed");
                            $(".client").html(json.client_name);
                            $(".department").html(json.department_name);
                            if(ticket.ticket_status==0){
                                $("#TicketCommentform").hide();
                            }else{
                                $("#TicketCommentform").show();
                            }
                            $(".comment_list").empty();
                            var height = 0;
                            if(comments){
                                $(".comment_area").show();
                                var i=0
                                $.each(comments, function(key,value){
i++;
//                                    console.log($(".comment_list").height())
                                    height += parseInt($(".comment_list").height());
                                    $(".comment_list").append(
                                            '<tr>'+
//                                            '<td><img src="'+value.photo+'" width="30" class="img-round"></td>'+
                                            '<td>'+
                                            '<b class="text-primary">'+value.username+'</b> '+ value.comment_text +
                                            '<br>'+
                                            '<i style="font-size: 12px;"><b>'+value.comment_date+'</i>'+
                                            '</b></td>'+

                                            '</tr>');
                                });
                                height += '';
                                if(i>=10){
                                    $(".comment_area").attr("style","height:500px; overflow-y:auto;");
                                }else{
                                    $(".comment_area").attr("style","");
                                }
//console.log(height)
                                $('.comment_area').animate({scrollTop: height});
                            }
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

            $(document).on('click', '.deleteData', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{csrf_token()}}';

                if (confirm("Are you sure you want to close ticket?")) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route("ticket_close") }}",
                        data: data,
                        success: function (response) {
                            console.log(response);
                            if (response == 1) {
                                toastr.success('Ticket Closed Successfully!','Success');
                                table.ajax.reload();
                                table2.ajax.reload();
                                table3.ajax.reload();
                            }
                            else {
                                toastr.warning( 'Ticket Cannot Close. Try aging!', 'Warning');
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
        });

        var i  = 0;
        setInterval(function() {
            $(".pending_btn").append(".");
            i++;
            if(i == 3)
            {
                $(".pending_btn").html("Pending");
                i = 0;
            }
        }, 500);

    </script>

@endsection

