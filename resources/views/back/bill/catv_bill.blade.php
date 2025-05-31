@extends('layouts.app')

@section('title', 'CATV Bill')

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

                    <li class="nav-item" style="display: none">
                        <a class="nav-link" id="base-tab2" data-toggle="tab" aria-controls="operation" href="#operation" aria-expanded="false"></a>
                    </li>
                    <li class="nav-item" style="display: none">
                        <a class="nav-link" id="base-tab3" data-toggle="tab" aria-controls="bill_view" href="#bill_view" aria-expanded="false"></a>
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



                                            <div class="table-responsive">

                                                <table id="all_bill_table" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>Sl No.</th>
                                                        <th>ID-Name</th>
                                                        <th>MRP</th>
                                                        <th>Discount</th>
                                                        <th>Payable</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="operation" aria-labelledby="base-tab2">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title pull-left">Bill Collect</div>
                                                    <button type="button" class="pull-right btn btn-sm btn-primary backL">Back To List</button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
                                                        <form id="DataForm" method="post">
                                                            {{ csrf_field() }}
                                                            <div class="">
                                                                <input type="hidden" id="action" name="action">
                                                                <input type="hidden" id="id" name="id">
                                                                <input type="hidden" id="client_initial_id" name="client_initial_id">

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
                                                                <div class="row">
                                                                    <label class="col-sm-4 control-label">Discount </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="number" class="form-control text-right"  name="discount_amount" id="discount" min="0" value="0">
                                                                    </div>
                                                                </div>
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
                                                            <button type="button" class="btn btn-danger mt-1 mb-0 backL">Cancel</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="tab-pane" id="bill_view" aria-labelledby="base-tab3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title pull-left">Client Billing History</div>
                                                    <button type="button" class="pull-right btn btn-sm btn-primary backL">Back To List</button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-lg-12 col-xs-12  col-md-12 col-sm-12">
                                                        <table class="table table-bordered clientBillHistory">
                                                            <thead>
                                                            <tr>
                                                                <th class='text-center'>Sl. No.</th>
                                                                <th class='text-center'>Date</th>
                                                                <th class='text-center'>Package</th>
                                                                <th class='text-center'>Dis.</th>
                                                                <th class='text-center'>Dr.</th>
                                                                <th class='text-center'>Cr.</th>
                                                                <th class='text-center'>Bal</th>
                                                                @if(auth()->user()->id==1)
                                                                    <th class='text-center'>#</th>
                                                                @endif
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
    .modal table td, .modal table th  {
        border-top:0 !important;
    }
    form .row{
        margin-bottom: 10px;
    }
</style>
@endsection
@section("page_script")
    <script type="text/javascript">
        var table_all_bill;

        var months = ["","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

        function all_bill_table(){
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
                "aaSorting": [[0, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[0,1,2,3,4,5],
                    "orderable": false
                },{
                    "targets":[0],
                    className: "text-center"
                },{
                    "targets":[2,3,4,5],
                    className: "text-right"
                } ],
                "ajax": {
                    url: "{{ route('catv_all_bill') }}",
                    type: "post",
                    "data":{
                        _token: "{{csrf_token()}}",
                        date_from: $("#date_from").val(),
                        date_to: $("#date_to").val()
                    },
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
//                        for (var i = 0, len = jsonData.data.length; i < len; i++) {
//                            jsonData.data[i][6] = '<div class="btn-group align-top" role="group">' +
//                                    '<button id="' + jsonData.data[i][0] + '" class="open btn btn-success btn-sm badge">' +
//                                    '<i class="la la-money"></i></button>' +
//                                    '<button id="' + jsonData.data[i][0] + '" class="details btn btn-info btn-sm badge">' +
//                                    '<i class="la la-eye"></i></button>' +
//                                    '</div>';
//                        }
                        //$(".from_to").html("From "+jsonData.date);
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                }
            });

            table_all_bill.on( 'order.dt search.dt', function () {
                table_all_bill.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
        }

        $(document).ready(function () {
            all_bill_table();

            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();

                //$(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('catv_bill_collect') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
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

            $(document).on('click', '.details', function () {
                var element = $(this);
                var id = element.attr("id");
                var info = 'id=' + id +"&_token={{ csrf_token() }}";
                element.html('<i class="ft-loader"></i>').prop("disabled",true);
                $(".dueTable").hide();
                $.ajax({
                    type: "POST",
                    url: "{{ route('catv_bill_history') }}",
                    data: info,
                    success: function (response) {
                      //  console.log(response)
                        element.html('<i class="la la-eye"></i>').prop("disabled",false);
                        if(response!=0){
                            $("[href='#bill_view']").tab("show");
                            var html='';
                            var json = (response);
                            var bal=0;
                            $.each(json,function(key,value){
                               // console.log(key)
                                //bal+=(value.payable_amount-(value.discount_amount+value.receive_amount+value.permanent_discount_amount));
                                bal+=(value.payable_amount-(value.receive_amount+value.discount_amount));

                                html+='<tr>' +
                                        '<td class="text-center">'+(Number(key)+1)+'</td>' +
                                        '<td class="text-center">'+(value.receive_amount>0?value.receive_date:value.bill_date)+'</td>' +
                                        '<td>'+value.package_name+'/<span class="taka">&#2547;.</span>'+value.package_price+'</td>' +
                                        '<td class="text-right">'+value.discount_amount+'</td>' +
                                        '<td class="text-right">'+value.payable_amount+'</td>' +
                                        '<td class="text-right">'+value.receive_amount+'</td>' +
                                        '<td class="text-right">'+(bal).toFixed(2)+'</td>' +
                                        '<td class="text-center">'+(value.emp_name?value.emp_name:'')+"<br>"+(value.receive_date ? value.receive_date:'')+'</td>' +
                                        @if(auth()->user()->id==1)
                                        '<td class="text-center"><button id="'+value.mybillid+'" class="btn btn-danger badge delmyb"><i class="ft-trash"></button></td>' +
                                        @endif()
                                        '</tr>';
                            });

                            $(".clientBillHistory tbody").html(html)
                        }else{
                            toastr.warning( 'Server Error. Try aging!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        //toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                });
            });

            $(document).on('click', '.open', function () {
                activeNav=$('.nav-tabs').find('.active').attr('href');
                var element = $(this);
                var id = element.attr("id");
                var info = 'id=' + id +"&_token={{ csrf_token() }}";
                element.html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('catv_bill_details') }}",
                    data: info,
                    success: function (response) {
                        element.html('<i class="la la-money"></i>').prop("disabled",false);
                        if(response!=0){
                            $("#tabOption").text("Bill Receive");
                            $("[href='#operation']").tab("show");
                            var json = JSON.parse(response);
                            $("#action").val(2);
                            $("#id").val(element.attr("id"));
                            $("#client").val(json.client_initial_id+" :: "+ json.client_name);
                            $("#payable").val(json.payable_amount);
                            $("#client_initial_id").val(json.client_initial_id);
                            $("#receive,#discount").attr("max",json.payable_amount);
                        } else {
                            toastr.warning( 'Server Error. Try aging!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        //toastr.warning( 'Server Error. Try aging!', 'Warning');
                        //$(".save").text("Save").prop("disabled", false);
                    }
                });
            });
            $(document).on('click', '.backL', function () {

                $("[href='"+activeNav+"']").tab("show");
            });

        });



    </script>


@endsection
