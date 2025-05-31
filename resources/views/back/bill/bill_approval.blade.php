@extends('layouts.app')

@section('title', 'Bill Approval')

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
                <h3 class="content-header-title"><span id="tabOption">ISP Client Bill</span></h3>
            </div>
            <div class="content-header-right col-md-8 col-12">

                <ul class="nav nav-tabs float-md-right">
                    <li class="nav-item">
                        <a onclick="document.getElementById('tabOption').innerHTML='ISP Client Bill'" class="nav-link active" id="base-tab2" data-toggle="tab" aria-controls="allBill" href="#allBill" aria-expanded="true">ISP Client Bill</a>
                    </li>
                    <li class="nav-item">
                        <a onclick="document.getElementById('tabOption').innerHTML='ISP Reseller Bill'" class="nav-link" id="base-tab3" data-toggle="tab" aria-controls="DataList" href="#DataList" aria-expanded="false">ISP Reseller Bill</a>
                    </li>
                    <li class="nav-item">
                        <a onclick="document.getElementById('tabOption').innerHTML='CATV Client Bill'" class="nav-link" id="base-tab4" data-toggle="tab" aria-controls="TodayDataList" href="#TodayDataList" aria-expanded="false">CATV Client Bill</a>
                    </li>
                    <li class="nav-item">
                        <a onclick="document.getElementById('tabOption').innerHTML='CATV Reseller Bill'" class="nav-link" id="base-tab5" data-toggle="tab" aria-controls="all_collection" href="#all_collection" aria-expanded="false">CATV Reseller Bill</a>
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
                                                            <th class="text-center">Sl No.</th>
                                                            <th>ID/Name</th>
                                                            <th class="text-center">Mobile</th>
                                                            <th>Package</th>
                                                            <th class="text-center">Date</th>
                                                            <th class="text-right">Discount</th>
                                                            <th class="text-right">Received</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($client_bills as $key=>$bill)
                                                            <tr>
                                                                <td class="text-center">{{ $key+1 }}</td>
                                                                <td>
                                                                    {{ $bill->client->client_id }}<br>
                                                                    {{ $bill->client->client_name }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $bill->client->cell_no }}
                                                                </td>
                                                                <td>
                                                                    {{ $bill->client->package->package_name }}<br>
                                                                    Tk{{ $bill->client->package->package_price }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $bill->receive_date }}
                                                                </td>
                                                                <td class="text-right">
                                                                    Tk{{ $bill->discount_amount }}
                                                                </td>
                                                                <td class="text-right">
                                                                    Tk{{ $bill->receive_amount }}
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-info btn-sm bill_approve" id="{{ $bill->id }}">Approve</button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
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

            table_all_bill = $('#all_bill_table').DataTable
            ({
               // "destroy": true,
//                "lengthChange": false,
//                "bPaginate": false,
//                "bLengthChange": false,
//                "bFilter": true,
//                "bInfo": false,
                //"bProcessing": true,
                //"serverSide": true,
                "responsive": false,
                //"aaSorting": [[0, 'asc']],
                "scrollX": true,
                "scrollCollapse": true,
//                "columnDefs": [ {
//                    "targets":[1,2,3,4,5,6,7,8],
//                    "orderable": false
//                },{
//                    "targets":[0,3,2,7,8],
//                    className: "text-center"
//                },{
//                    "targets":[4,5,6],
//                    className: "text-right"
//                } ],

            });

        }


        $(document).ready(function () {


            all_bill_table();

            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ url('client_bill_collect') }}",
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

            $(document).on('click', '.bill_approve', function () {
                var element = $(this);
                var info = 'id=' + element.attr("id") +"&_token={{ csrf_token() }}";
                //alert(info)
                if(confirm('Are you sure to approve this?')){
                    $(element).html('<i class="ft-loader"></i>').prop("disabled",true);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('save_bill_approve') }}",
                        data: info,
                        success: function (response) {
                            console.log(response)
                            $(element).closest('tr').remove();
                            $(element).html('Approve').prop("disabled",false);
                            if(response.msg=='success'){
                                toastr.success( 'Successfully approved', 'Success');
                            }else{
                                toastr.warning( 'Server Error. Try aging!', 'Warning');
                            }
                        },
                        error: function (request, status, error) {
                            console.log(request.responseText);
                            $(element).html('Approve').prop("disabled",false);
                            toastr.warning( 'Server Error. Try aging!', 'Warning');
                        }
                    });
                }
            });

            $(document).on('click', '.viewBill', function () {
                var element = $(this);
                var id = element.attr("id");

                var info = 'id=' + id +"&_token={{ csrf_token() }}";
                element.html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{ url('isp_client_bill_history') }}",
                    data: info,
                    success: function (response) {
                        element.html('<i class="la la-eye"></i>').prop("disabled",false);
                        if(response!=0){
                            $("#bill_view").modal("show");
                            var html='';
                            var json = JSON.parse(response);
                            var bal=0;
                            $.each(json,function(key,value){
                                //console.log(value.bill_id)
                                //bal+=(value.payable_amount-(value.discount_amount+value.receive_amount+value.permanent_discount_amount));
                                bal+=(value.payable_amount-(value.receive_amount+value.discount_amount));

                                html+='<tr>' +
                                        '<td class="text-center">'+(key+1)+'</td>' +
                                        '<td class="text-center">'+(value.receive_amount>0?value.receive_date:value.bill_date)+'</td>' +
                                        '<td>'+value.package_name+'/<span class="taka">&#2547;.</span>'+value.package_price+'</td>' +
                                        '<td class="text-right">'+value.permanent_discount_amount+'</td>' +
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
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                });
            });

            $(document).on('click', '.dueSMS', function () {

                $('#sms_view textarea').text('');
                var element = $(this);
                var id = element.attr("id");
                var info = 'id=' + id +"&_token={{ csrf_token() }}";
                element.html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{ url('isp_client_due_sms') }}",
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


        });

    </script>


@endsection
