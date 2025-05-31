@extends('layouts.app')

@section('title', 'Reseller Bill')

@section('content')
<style>
    .table td,.table th {
        font-size: 13px;
        vertical-align: middle;
    }
    .table td{
        padding: 1px 5px;
    }
    .table th {
        padding:5px;
    }
    .table td span {
        font-size: 18px;
    }
</style>
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title"><span id="tabOption">Client Bill</span></h3>
            </div>
            <div class="content-header-right col-md-8 col-12">

                <ul class="nav nav-tabs float-md-right">
                    <li class="nav-item">
                        <a onclick="document.getElementById('tabOption').innerHTML='All Bill',all_bill_table()" class="nav-link active" id="base-tab2" data-toggle="tab" aria-controls="allBill" href="#allBill" aria-expanded="true">All Bill</a>
                    </li>
                    <li class="nav-item">
                        <a onclick="document.getElementById('tabOption').innerHTML='Unpaid Bill',pendingBill()" class="nav-link" id="base-tab3" data-toggle="tab" aria-controls="DataList" href="#DataList" aria-expanded="false">Unpaid Bill</a>
                    </li>
                    <li class="nav-item">
                        <a onclick="document.getElementById('tabOption').innerHTML='Today Collection',todayBillCollection()" class="nav-link" id="base-tab4" data-toggle="tab" aria-controls="TodayDataList" href="#TodayDataList" aria-expanded="false">Today Collection</a>
                    </li>
                    <li class="nav-item">
                        <a onclick="document.getElementById('tabOption').innerHTML='All Collection',allBillCollection()" class="nav-link" id="base-tab5" data-toggle="tab" aria-controls="all_collection" href="#all_collection" aria-expanded="false">All Collection</a>
                    </li>
                    <li class="nav-item" style="display: none">
                        <a class="nav-link" id="base-tab2" data-toggle="tab" aria-controls="operation" href="#operation" aria-expanded="false"></a>
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
                                <div class="card-body card-dashboard" style="padding: 0; padding-bottom: 1.5rem;">



                                    <div class="tab-content pt-1">
                                        <div role="tabpanel" class="tab-pane active" id="allBill" aria-expanded="true" aria-labelledby="base-tab1">

                                            <div class="collapse-icon accordion-icon-rotate right">

                                                <div class="table-responsive">
                                                    <table id="all_bill_table" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                                        <thead>
                                                        <tr>
                                                            <th>Sl No.</th>
                                                            <th>ID/Name</th>
                                                            <th>Mobile</th>
                                                            <th>Package</th>
                                                            <th>Payable(Tk)</th>
                                                            <th>Received(Tk)</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div role="tabpanel" class="tab-pane" id="DataList" aria-expanded="true" aria-labelledby="base-tab1">

                                            <div class="table-responsive">
                                                <table id="unpaid_bill_table" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>ID/Name</th>
                                                        <th>Mobile</th>
                                                        <th>Package</th>
                                                        <th>P. Discount</th>
                                                        <th>Payable</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="TodayDataList" aria-expanded="true" aria-labelledby="base-tab1">

                                            <div class="table-responsive">
                                                <table id="ToCollist" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>SL</th>
                                                        <th>Client ID/Name</th>
                                                        <th>Mobile</th>
                                                        <th>Package/Price</th>
                                                        <th>Discount(Tk)</th>
                                                        <th>Received(Tk)</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>

                                        <div role="tabpanel" class="tab-pane" id="all_collection" aria-expanded="true" aria-labelledby="base-tab1">

                                            {{--<div class="card inner-card" style="margin:0;">--}}
                                                {{--<div class="card-body" style="padding: 6px 10px;">--}}
                                                    {{--<form id="AllCollSearchForm" method="post" novalidate>--}}
                                                        {{--{{csrf_field()}}--}}
                                                        {{--<div class="row" style="margin:0;">--}}
                                                            {{--<div class="col-md-2">--}}
                                                            {{--</div>--}}
                                                            {{--<div class="col-md-3">--}}
                                                                {{--<div class="form-group" style="margin:0;">--}}
                                                                    {{--<input type="date" id="all_collection_date_from" class="form-control" value="{{ $date_from }}" required>--}}
                                                                {{--</div>--}}
                                                            {{--</div>--}}
                                                            {{--<div class="col-md-3">--}}
                                                                {{--<div class="form-group" style="margin:0;">--}}
                                                                    {{--<input type="date"id="all_collection_date_to" class="form-control " value="{{ $date_to }}" required>--}}
                                                                {{--</div>--}}
                                                            {{--</div>--}}
                                                            {{--<div class="col-md-3">--}}
                                                                {{--<div class="form-group"  style="margin:0;">--}}
                                                                    {{--<button type="button" onclick="allBillCollection()" class="btn btn-primary mb-0 search">Search</button>--}}
                                                                {{--</div>--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                    {{--</form>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            <div class="table-responsive">
                                                <table id="all_collection_table" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>SL</th>
                                                        <th>Client ID/Name</th>
                                                        <th>Mobile</th>
                                                        <th>Package/Price</th>
                                                        <th>Discount(Tk)</th>
                                                        <th>Received(Tk)</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="operation" aria-labelledby="base-tab2">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
                                                        <form id="DataForm" method="post">
                                                            {{ csrf_field() }}
                                                            <div class="">
                                                                <input type="hidden" id="action" name="action">
                                                                <input type="hidden" id="id" name="id">

                                                                <div class="row">
                                                                    <label class="col-sm-4 control-label">Client<span class="text-danger">*</span></label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"  name="client" id="client" required readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label class="col-sm-4 control-label">Payable <span class="text-danger">*</span></label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control text-right"  name="payable" id="payable" readonly required>
                                                                    </div>
                                                                </div>

                                                                @if(in_array(auth()->user()->ref_role_id,[1,2]))
                                                                <div class="row">
                                                                    <label class="col-sm-4 control-label">Discount </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="number" class="form-control text-right"  name="discount_amount" id="discount" min="0" >
                                                                    </div>
                                                                </div>
                                                                @endif
                                                                <div class="row">
                                                                    <label class="col-sm-4 control-label">Receive <span class="text-danger">*</span></label>
                                                                    <div class="col-sm-8">
                                                                        <input type="number" class="form-control text-right"  name="receive_amount" id="receive" min="1" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label class="col-sm-4 control-label">Date</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" readonly class="form-control datepicker_startdate"  name="receive_date" id="date" value="{{ date("d/m/Y") }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label class="col-sm-4 control-label">Payment Method</label>
                                                                    <div class="col-sm-8">

                                                                        <select class="form-control" id="payment_method_id" name="payment_method_id">
                                                                            @foreach($payments as $row)
                                                                                <option value="{{ $row->id }}">{{ $row->payment_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label class="col-sm-4 control-label">Collected By</label>
                                                                    <div class="col-sm-8">


                                                                        @if(count($employees)>0)
                                                                            <select class="form-control" id="collected_by" name="collected_by">
                                                                                @foreach($employees as $row)
                                                                                    <option value="{{ $row->auth_id }}">{{ $row->emp_name }}</option>
                                                                                    @endforeach
                                                                            </select>
                                                                            @else
                                                                            <input type="hidden"  id="collected_by" name="collected_by" value="{{ Auth::user()->id }}">
                                                                            <input type="text" readonly class="form-control"  value="{{ Auth::user()->name }}" required>

                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label class="col-sm-4 control-label">Note</label>
                                                                    <div class="col-sm-8">
                                                                        <textarea class="form-control"  name="note" id="note" ></textarea>
                                                                    </div>
                                                                </div>
                                                                @if(in_array(auth()->user()->ref_role_id,[1,2]))
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <label><input type="checkbox" name="payment_confirm_sms" value="1" checked> SMS</label>
                                                                        <label><input type="checkbox" name="payment_confirm_email" value="1" checked> Email</label>
                                                                    </div>
                                                                    <div style="clear:both;"></div>
                                                                </div>
                                                                    @endif
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

<div class="modal fade text-left" id="bill_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white" id="myModalLabel8">Client Bill History</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="col-md-12">

                    <div class="row " >

                        <table class="table table-bordered clientBillHistory">
                            <thead>
                            <tr>
                                <th class='text-center'>Sl. No.</th>
                                <th class='text-center'>Bill ID</th>
                                <th class='text-center'>Package</th>
                                <th class='text-center'>P. Dis.</th>
                                <th class='text-center'>Dis.</th>
                                <th class='text-center'>Dr.</th>
                                <th class='text-center'>Cr.</th>
                                <th class='text-center'>Bal</th>
                                <th class='text-center'>Received</th>
                            </tr>
                            </thead>
                            <tbody>


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

<div class="modal fade text-left" id="commitmentDateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning white">
                <h4 class="modal-title white" id="myModalLabel8">Commitment Date</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="UpdateCommitmentDate" method="post">
                @csrf
                <input type="hidden" name="client_id" id="client_id">
                <input type="hidden" name="mobile" id="mobile">
                <input type="hidden" name="name" id="name">
            <div class="modal-body">

                <div class="col-md-12">
                    <div class="row" >
                       Choose Date :
                    </div>
                    <div class="row" >
                        <input type="date" class="form-control" name="commitment_date" required/>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-warning pull-right" type="submit">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>

<style>
    form .row{
        margin-bottom: 10px;
    }
    .taka{
        font-size: 16px;;
    }
</style>
@endsection
@section("page_script")
    <script type="text/javascript">
        var table_unpaid_bill, table_today_collection, table_all_collection,table_all_bill;

        function all_bill_table(d){
            blockLoad();
            table_all_bill = $('#all_bill_table').DataTable
            ({
                "destroy": true,
                "lengthChange": false,
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": false,
                "bProcessing": true,
                "serverSide": true,
                "responsive": false,
                "aaSorting": [[0, 'asc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[1,2,3,4,5,6],
                    "orderable": false
                },{
                    "targets":[0,3,2],
                    className: "text-center"
                },{
                    "targets":[4,5,6],
                    className: "text-right"
                } ],
                "ajax": {
                    url: "{{ route('reseller_all_bill') }}",
                    type: "post",
                    "data":{
                        _token: "{{csrf_token()}}",
                        date_filter: d==1?1:0,
                        date_from: $("#date_from").val(),
                        date_to: $("#date_to").val()
                    },
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        unblockLoad();
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        unblockLoad();
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                }
            });

//            table_all_bill.on( 'order.dt search.dt', function () {
//                table_all_bill.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
//                    cell.innerHTML = i+1;
//                } );
//            } ).draw();
        }

        function pendingBill(){
            table_unpaid_bill = $('#unpaid_bill_table').DataTable
            ({
                "lengthChange": false,
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": false,

                "destroy": true,
                "bAutoWidth": false,
                "bProcessing": true,
                "serverSide": true,
                "responsive": false,
                "aaSorting": [[0, 'asc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [{
                    "targets":[1,2,3,4,5],
                    "orderable": false
                },{
                    "targets":[0,2,6],
                    className: "text-center"
                },{
                    "targets":[4,5],
                    className: "text-right"
                } ],
                "ajax": {
                    url: "{{ route('reseller_unpaid_bill') }}",
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

//            table_unpaid_bill.on( 'order.dt search.dt', function () {
//                table_unpaid_bill.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
//                    cell.innerHTML = i+1;
//                } );
//            } ).draw();
        }

        function todayBillCollection(){
            table_today_collection = $('#ToCollist').DataTable
            ({
                "lengthChange": false,
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": false,

                "retrieve": true,
                "bAutoWidth": false,
                "bProcessing": true,
                "serverSide": true,
                "responsive": false,
                "aaSorting": [[1, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[0,2,3,4,5,6],
                    "orderable": false
                },{
                    "targets":[0,2,6],
                    className: "text-center"
                },{
                    "targets":[4,5],
                    className: "text-right"
                } ],
                "ajax": {
                    url: "{{ route('today_client_collection') }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
//                        for (var i = 0, len = jsonData.data.length; i < len; i++) {
//                            jsonData.data[i][9] = '<div class="btn-group align-top" role="group">' +
//                                    '<button id="' + jsonData.data[i][0] + '" class="'+jsonData.data[i][0]+' btn btn-info btn-sm badge">' +
//                                    '<i class="la la-eye"></i></button>' +
//                                    '</div>';
//                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                }
            });
            table_today_collection.on( 'order.dt search.dt', function () {
                table_today_collection.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
        }

        function allBillCollection(){

            table_all_collection = $('#all_collection_table').DataTable
            ({
                "retrieve": true,
                "lengthChange": false,
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": false,
                "bAutoWidth": false,
                "bProcessing": true,
                "serverSide": true,
                "responsive": false,
                "aaSorting": [[0, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[0,2,3,4,5,6],
                    "orderable": false
                },{
                    "targets":[0,2,6],
                    className: "text-center"
                },{
                    "targets":[4,5],
                    className: "text-right"
                } ],
                "ajax": {
                    url: "{{ route('reseller_all_collected_bill') }}",
                    type: "post",
                    "data":{
                        _token      : "{{csrf_token()}}",
                        date_from   : $("#all_collection_date_from").val(),
                        date_to     : $("#all_collection_date_to").val()
                    },
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

            table_all_collection.on( 'order.dt search.dt', function () {
                table_all_collection.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
        }

        $(document).ready(function () {


            all_bill_table();

            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('reseller_bill_collect') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        console.log(response)
                        $(".save").text("Save").prop("disabled", false);
                        if (response == 1) {
                            $("#DataForm").trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
                            $("[href='#allBill']").tab("show");
                            table_unpaid_bill.ajax.reload();
                            table_today_collection.ajax.reload();
                            table_all_collection.ajax.reload();
                            table_all_bill.ajax.reload();
                        }else if(response == 101){
                            toastr.warning( 'Bill already receive!', 'Warning');
                        }
                        else {
                          toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(".save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('click', '.open', function () {
                var element = $(this);
                var info = 'id=' + element.attr("id") +"&_token={{ csrf_token() }}";
                //alert(info)
                $(element).html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('reseller_bill_details') }}",
                    data: info,
                    success: function (response) {
                        //console.log(response)
                        $(element).html('<i class="la la-money"></i>').prop("disabled",false);
                        if(response!=0){
                            $("#tabOption").text("Bill Receive");
                            $(".operation_type").text("Update");
                            $("[href='#operation']").tab("show");
                            var json = JSON.parse(response);
                            $("#action").val(2);
                            $("#id").val(element.attr("id"));
                            $("#client").val(json.client_initial_id+" :: "+ json.client_name);
                            $("#payable").val(json.payable_amount);

                        } else {
                            toastr.warning( 'Server Error. Try aging!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                });
            });

            $(document).on('click', '.viewBill', function () {
                var element = $(this);
                var id = element.attr("id");
                var info = 'id=' + id +"&_token={{ csrf_token() }}";
                element.html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('isp_reseller_bill_history') }}",
                    data: info,
                    success: function (response) {
                        console.log(response)
                        element.html('<i class="la la-eye"></i>').prop("disabled",false);
                        if(response!=0){
                            $("#bill_view").modal("show");
                            var html='';
                            var json = JSON.parse(response);
                            var bal=0;
                            $.each(json,function(key,value){
                                bal+=(value.payable_amount-value.receive_amount);

                                html+='<tr>' +
                                        '<td class="text-center">'+(key+1)+'</td>' +
                                        '<td class="text-center">'+value.bill_id+'</td>' +
                                        '<td>'+value.package+'</td>' +
                                        '<td class="text-right">'+value.permanent_discount_amount+'</td>' +
                                        '<td class="text-right">'+value.discount_amount+'</td>' +
                                        '<td class="text-right">'+value.payable_amount+'</td>' +
                                        '<td class="text-right">'+value.receive_amount+'</td>' +
                                        '<td class="text-right">'+(bal).toFixed(2)+'</td>' +
                                        '<td class="text-center">'+(value.emp_name?value.emp_name:'')+"<br>"+(value.receive_date ? value.receive_date:'')+'</td>' +
                                        '</tr>';
                            });

                            $(".clientBillHistory tbody").html(html)
                        }else{
                            toastr.warning( 'Server Error. Try aging!', 'Warning');
                        }
                    }, error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        //$(".save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('click', '.dueSMS', function () {
                $("#sms_view textarea").text('')
                var element = $(this);
                var id = element.attr("id");
                var info = 'id=' + id +"&_token={{ csrf_token() }}";
                element.html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('isp_reseller_due_sms') }}",
                    data: info,
                    success: function (response) {
                        element.html('<i class="la la-envelope"></i>').prop("disabled",false);
                        $("#sms_view").modal("show");
                        var html='';
                        var json = JSON.parse(response);
                        var name=element.closest("tr").find("td:nth-child(3)").html();
                        $("#sms_view .clientName").html(name.replace("<br>"," :: "))
                        $("#sms_view .sent_to").val(json.sent_to)
                        $("#sms_view textarea").text(json.sms)

                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        //$(".save").text("Save").prop("disabled", false);
                    }
                });
            });
            $(document).on('click', '.commitDate', function () {
                var element = $(this);
                var id = element.attr("id");
                var mobile = element.closest("tr").find("td:nth-child(3)").html();
                var name = element.closest("tr").find("td:nth-child(2)").html();
                name = name.split("<br>")[1]
                $("#commitmentDateModal #mobile").val(mobile);
                $("#commitmentDateModal #client_id").val(id);
                $("#commitmentDateModal #name").val(name);
                $("#commitmentDateModal").modal("show");

            });

            $(document).on('submit', "#DueSMSSent", function (e) {
                e.preventDefault();

                var element= this;
                $(".sent",element).text("Sending...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('isp_reseller_due_sms_save') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        $(".sent",element).text("Sent SMS").prop("disabled", false);
                        if (response == 1) {
                            $("#sms_view").modal("hide");
                            toastr.success( 'SMS successully sent', 'Success');
                        }
                        else {
                            toastr.warning( 'SMS senting failed. Try aging!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(".save").text("Save").prop("disabled", false);
                    }
                });
            });
            $(document).on('submit', "#UpdateCommitmentDate", function (e) {
                e.preventDefault();

                var element= this;
                $(".sent",element).text("Updating...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('isp_commitment_date_update') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        $(".sent",element).text("Update").prop("disabled", false);
                        if (response == 1) {
                            $("#commitmentDateModal").modal("hide");
                            toastr.success( 'Successfully Updated', 'Success');
                        }
                        else {
                            toastr.warning( 'Update failed. Try aging!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(".save").text("Update").prop("disabled", false);
                    }
                });
            });

        });

    </script>
@endsection
