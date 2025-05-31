@extends('layouts.app')

@section('title', 'Liability')

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-2 col-12 mb-2">
                <h3 class="content-header-title">@yield("title")</h3>
            </div>
            <div class="content-header-right col-md-10 col-12">

                <ul class="nav nav-tabs float-md-right">
                    <li class="nav-item">
                        <a class="nav-link" id="base-tab0" data-toggle="tab" aria-controls="Creditor" href="#Creditor" aria-expanded="true"> <span class="ft-list"></span> Creditor List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link addnewHead" id="base-tab0" data-toggle="tab" aria-controls="addExpenseHead" href="#addExpenseHead" aria-expanded="true"> <span class="ft-plus"></span> Add Receive </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link addnew" id="base-tab2" data-toggle="tab" aria-controls="operation" href="#operation" aria-expanded="false"> <span class="ft-plus"></span> Add Payment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="base-tab01" data-toggle="tab" aria-controls="PaymentList" href="#PaymentList" aria-expanded="true"> <span class="ft-list"></span>Payment List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="ReceiveList" href="#ReceiveList" aria-expanded="true"> <span class="ft-list"></span>Receive List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="base-tab1" data-toggle="tab" aria-controls="SummeryList" href="#SummeryList" aria-expanded="true"> <span class="ft-list"></span>Summery</a>
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
                                        <div role="tabpanel" class="tab-pane" id="Creditor" aria-expanded="true" aria-labelledby="base-tab1">

                                            <div class="card-header">
                                                <h4><span class="operation_type">List of Creditor  <span class="btn btn-primary ft-plus-square addCreditor" data-toggle="modal" data-backdrop="false" data-target="#AddPerson">Add New</span></h4>
                                            </div>

                                            <div class="table-responsive">
                                                <table id="CreditorDataList" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>SL</th>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Mobile</th>
                                                        <th>Address</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                        </div>
                                        <div role="tabpanel" class="tab-pane active" id="ReceiveList" aria-expanded="true" aria-labelledby="base-tab1">

                                            <div class="card-header">
                                                <h4><span class="operation_type">List of Liability Receive</h4>
                                            </div>

                                            <div class="table-responsive">
                                                <table id="ReceiveDataList" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>SL</th>
                                                        <th>ID</th>
                                                        <th>Date</th>
                                                        <th>Liability From</th>
                                                        <th>Receive To</th>
                                                        <th>Amount</th>
                                                        <th>Note</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="operation" aria-labelledby="base-tab2">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Add Payment Liability </h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
                                                        <form id="LoanPayment" method="post">
                                                            {{ csrf_field() }}
                                                            <div class="">
                                                                <input type="hidden" id="action" name="action">
                                                                <input type="hidden" id="id" name="id">
                                                                <input type="hidden" id="loan_type" name="loan_type" value="2">
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">Date<code>*</code></label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control datepicker text-center"  name="receive_date" id="receive_date" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">Payment From<code>*</code></label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" value="{{ Settings::settings()["company_name"] }}" name="receiver" id="receiver" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">Payment To<code>*</code></label>
                                                                    <div class="col-sm-8">
                                                                        <select name="loan_person" id="" class="select2  form-control loan_person_list" required>
                                                                            <option></option>
                                                                            @foreach($loan_persons as $row)
                                                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">Payable Amount</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control text-right"  name="payable_receive_amount" id="payable_receive_amount" readonly required>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">Amount<code>*</code></label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control text-right"  name="loan_amount" id="loan_amount" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">Note<code>*</code></label>
                                                                    <div class="col-sm-8">
                                                                        <textarea class="form-control"  name="note" id="note"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary mt-1 mb-0 save">Save</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="addExpenseHead" aria-labelledby="base-tab0">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Add Liability Receive</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
                                                        <form id="LoanReceive" method="post">
                                                            {{ csrf_field() }}
                                                            <div class="">
                                                                <input type="hidden" id="action" name="action">
                                                                <input type="hidden" id="loan_type" name="loan_type" value="1">
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">Date<code>*</code></label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control text-center datepicker"  name="receive_date" id="receive_date" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">Receive To<code>*</code></label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" value="{{ Settings::settings()["company_name"] }}" name="receiver" id="receiver" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">Receive From<code>*</code></label>
                                                                   <div class="col-sm-8">
                                                                       <div class="input-group">
                                                                           <select name="loan_person" id="" class="select2-group form-control loan_person_list" required>
                                                                               <option></option>
                                                                               @foreach($loan_persons as $row)
                                                                                   <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                                               @endforeach
                                                                           </select>
                                                                           <div class="input-group-addon btn-primary ft-plus"  data-toggle="modal" data-backdrop="false" data-target="#AddPerson" style="cursor: pointer" title="Add Person"></div>
                                                                       </div>
                                                                   </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">Amount<code>*</code></label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control text-right"  name="loan_amount" id="loan_amount" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="payment_amount_per_month" class="col-sm-4 control-label">Payable Amount <small class="text-danger">(Per month)</small></label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control text-right"  name="installment_per_month" id="installment_per_month">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="payment_date_per_month" class="col-sm-4 control-label">Payable Deadline</label>
                                                                    <div class="col-sm-8">
                                                                        <select name="installment_date" id="installment_date" class="select2-group form-control">
                                                                            <option></option>
                                                                            @for($i=1;$i<=28;$i++)
                                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                            @endfor
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label for="" class="col-sm-4 control-label">Note<code>*</code></label>
                                                                    <div class="col-sm-8">
                                                                        <textarea class="form-control"  name="note" id="note"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary mt-1 mb-0 save">Save</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div role="tabpanel" class="tab-pane" id="PaymentList" aria-expanded="true" aria-labelledby="base-tab01">
                                            <div class="card-header">
                                                <h4><span class="operation_type">List of Payment</span></h4>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="PaymentDataList" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>SL</th>
                                                        <th>ID</th>
                                                        <th>Date</th>
                                                        <th>Payment To</th>
                                                        <th>Payment From</th>
                                                        <th>Amount</th>
                                                        <th>Note</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                        </div>

                                        <div role="tabpanel" class="tab-pane" id="SummeryList" aria-expanded="true" aria-labelledby="base-tab01">
                                            <div class="card-header">
                                                <h4>Summery</h4>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="SummeryDataList" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>SL</th>
                                                        <th>ID</th>
                                                        <th>Liability From</th>
                                                        <th>Total Receive</th>
                                                        <th>Total Payment</th>
                                                        <th>Total Payable</th>
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


        <!-- summery view -->
        <div class="modal fade text-left" id="SummeryView" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info white">
                        <h4 class="modal-title white" id="myModalLabel8">Liability [<span class="name"></span>]</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="col-md-12">
                            <form id="SummeryForm" method="post">
                                @csrf
                                <input type="hidden" name="id" id="summeryCreditorId">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="from_date" class="">From<code>*</code></label>
                                        @php
                                        $date = date("d")-1;
                                        @endphp
                                        <input type="text" class="form-control text-center datepicker" value="{{ date("Y-m-d" , strtotime("-".  $date ." days ". date("Y-m-d"))) }}"  name="from_date" id="from_date" required />
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="to_date" class="">To<code>*</code></label>
                                        <input type="text" class="form-control text-center datepicker" value="{{ date("Y-m-d") }}" name="to_date" id="to_date" required />
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-primary" style="margin-top: 25px;">Search</button>
                                    </div>
                                </div>
                            </form>
                            <div class="row liabilityList" style="display: none; ">

                              <div style="margin-top: 15px;">
                                  <b>Creditor Name: </b> <span class="name"></span>
                                  <br>
                                  <b>Date: </b> <i class="fromDate"></i> &nbsp; to &nbsp; <i class="toDate"></i>
                              </div>

                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th class='text-center'>Date</th>
                                        <th class='text-right'>Receive</th>
                                        <th class='text-right'>Payment</th>
                                        <th class='text-right'>Payable</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot></tfoot>

                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <form id="OPform" action="{{ route("download-liability-pdf") }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="to_date" class="to_date" />
                            <input type="hidden" name="from_date" class="from_date" />
                            <input type="hidden" name="creditor_id" class="creditor_id" />
                            <input type="submit" class="btn grey btn-info" name="operation" value="View PDF">
                            <input type="submit" class="btn grey btn-primary" name="operation" value="Download PDF">
                            <input type="submit" class="btn grey btn-success" name="operation" value="Print">
                        </form>
                        <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

      <!-- Modal -->
        <div class="modal fade text-left" id="AddPerson" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary white">
                        <h4 class="modal-title white" id="myModalLabel8">Creditor</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="AddPersonForm" method="post">
                        <input type="hidden" id="creditor_id" name="creditor_id">
                        <input type="hidden" id="action" name="action" value="1">
                    <div class="modal-body">
                        @csrf
                        <div class="col-md-12">
                            <div class="row">
                                <label for="name" class="col-sm-4">Name<code>*</code></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control"  name="name" id="name" required autocomplete="off">
                                </div>
                            </div>
                            <div class="row">
                                <label for="mobile" class="col-sm-4">Mobile No</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control"  name="mobile" id="mobile" placeholder="optional">
                                </div>
                            </div>
                            <div class="row">
                                <label for="address" class="col-sm-4">Address</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control"  name="address" id="address" placeholder="optional"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger save">Save</button>
                    </div>
                    </form>
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

            //datatable
            var table = $('#ReceiveDataList').DataTable
            ({

                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [[2, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[7],
                    "orderable": false
                },{
                    "targets":[0,7],
                    className: "text-center"
                },{
                    "targets":[1],
                    "visible": false,
                    "searchable": false
                } ],
                "ajax": {
                    url: "{{ route('loan_receive_list') }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {

                            jsonData.data[i][7] = '<div class="btn-group align-top" role="group">' +
                                    '<button  id=' + jsonData.data[i][0] + ' class="deleteData btn btn-danger btn-sm badge">' +
                                    '<span class="ft-delete"></span></button>' +
                                    '</div>';
                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                }
            });

            //datatable
            var table2 = $('#PaymentDataList').DataTable
            ({

                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [[2, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[7],
                    "orderable": false
                },{
                    "targets":[0,7],
                    className: "text-center"
                },{
                    "targets":[1],
                    "visible": false,
                    "searchable": false
                }  ],
                "ajax": {
                    url: "{{ route('loan_payment_list') }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {

                            jsonData.data[i][7] = '<div class="btn-group align-top" role="group">' +
                                '<button  id=' + jsonData.data[i][0] + ' class="deleteData btn btn-danger btn-sm badge">' +
                                '<span class="ft-delete"></span></button>' +
                                '</div>';
                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                }
            });

            //datatable
            var table3 = $('#SummeryDataList').DataTable
            ({

                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "searching": false,
                "aaSorting": [[0, 'asc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[4],
                    "orderable": false
                },{
                    "targets":[0,4],
                    className: "text-center"
                } ,  {
                    "targets": [ 1 ],
                    "visible": false,
                    "searchable": false
                }],
                "ajax": {
                    url: "{{ route('loan_summery_list') }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {

                            jsonData.data[i][6] = '<div class="btn-group align-top" role="group">' +
                                '<button id="' + jsonData.data[i][1] + '" name="'+jsonData.data[i][2]+'" class="viewSummery edit'+jsonData.data[i][0]+' btn btn-primary btn-sm badge">' +
                                'View</button>' +
                                '</div>';
                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                }
            });

            //datatable
            var table4 = $('#CreditorDataList').DataTable
            ({

                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [[0, 'asc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[5],
                    "orderable": false
                },{
                    "targets":[0,5],
                    className: "text-center"
                },{
                    "targets":[1],
                    "visible": false,
                    "searchable": false
                } ],
                "ajax": {
                    url: "{{ route('creditor_list') }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {

                            jsonData.data[i][5] = '<div class="btn-group align-top" role="group">' +
                                '<button id="' + jsonData.data[i][0] + '" class="creditorUpdate edit'+jsonData.data[i][0]+' btn btn-primary btn-sm badge">' +
                                '<span class="ft-edit-2"></span></button>' +
                                '</div>';
                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        //console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                }
            });

            table4.on( 'order.dt search.dt', function () {
                table4.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

            table3.on( 'order.dt search.dt', function () {
                table4.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

            table2.on( 'order.dt search.dt', function () {
                table2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();


            $(document).on('click', '.addnew', function () {
                $("#action").val(1);
                $("#id").val("");
                $("#LoanPayment").trigger("reset");
            });

            $(document).on('submit', "#LoanPayment", function (e) {
                e.preventDefault();
                var _this = "#LoanPayment ";
                $(_this +" .save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_loan') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        //console.log(response);
                        if (response == 1) {
                            $(_this).trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
                            $("[href='#PaymentList']").tab("show");
                            table2.ajax.reload();
                            table3.ajax.reload();
                        }
                        else {
                              toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(_this +" .save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(_this +" .save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('submit', "#LoanReceive", function (e) {
                e.preventDefault();
                var _this = "#LoanReceive ";
                $(_this +" .save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_loan') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        //console.log(response);
                        if (response == 1) {
                            $(_this).trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
                            $("[href='#ReceiveList']").tab("show");
                            table.ajax.reload();
                            table3.ajax.reload();
                        }
                        else {
                              toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(_this +" .save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(_this +" .save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('click', '.addnewHead', function () {
                var _this = "#ExpenseHeadForm ";
                $(_this+" #action").val(1);
                $(_this+" #id").val("");
                $(_this).trigger("reset");
            });

            $(document).on('click', '.addCreditor', function () {
                var _this = "#AddPersonForm ";
                $(_this+" #action").val(1);
                $(_this+" #creditor_id").val("");
                $(_this).trigger("reset");
            });

            $(document).on('click', '.deleteData', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{csrf_token()}}';

                if (confirm("Are you sure you want to delete this?")) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('loan_delete') }}",
                        data: data,
                        success: function (response) {
                            if (response == 1) {
                                toastr.success('Data removed Successfully!','Success');
                                element.parents("tr").animate({backgroundColor: "#003"}, "slow").animate({opacity: "hide"}, "slow");
                                table3.ajax.reload();
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

            $(document).on('submit', "#AddPersonForm", function (e) {
                e.preventDefault();
                var _this = "#AddPersonForm ";
                $(_this +" .save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_loan_person') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        if (response == 1) {
                            $("#AddPerson").modal("hide");

                            $("#AddPersonForm #action").val(1);
                            $(_this).trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
                            table4.ajax.reload();
                            _loan_person();
                        }
                        else {
                            toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(_this +" .save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(_this +" .save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('click', '.viewSummery', function () {
                var element = $(this);
                var id = element.attr("id");
                var name = element.attr("name");
                $("#SummeryView").modal("show");
                $(".name").html(name);
                $("#summeryCreditorId").val(id);
                $(".liabilityList").hide();
                $("#OPform").hide();
                $(".liabilityList tbody").empty();
                $('#SummeryForm').trigger("submit");
            });

            $(document).on('submit', '#SummeryForm', function (e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ route('creditor_liability_list') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response) {
                        //console.log(response)
                        if (response !=0) {
                            var json = JSON.parse(response)

                            $("#OPform").show();
                            $(".liabilityList").show();
                            $(".liabilityList tbody").empty();

                            $(".fromDate").html(dateFormat($("#from_date").val()));
                            $(".toDate").html(dateFormat($("#to_date").val()));

                            $(".to_date").val($("#to_date").val());
                            $(".from_date").val($("#from_date").val());
                            $(".creditor_id").val($("#summeryCreditorId").val());

                            var receive=0,payment= 0,due= 0,current_due =0;
                            var i=0;
                            var rcv_pay = 0
                            $.each(json,function(key,value){
                                i++;
                                rcv_pay+=Number(value.rcv_amount);
                                var date = value.receive_date;
                                date = date.split("-");
                                date = date[2]+"/"+date[1]+"/"+date[0];
                                if(json.length==i){
                                    current_due = Number(value.current_due);
                                    due+=Number(current_due);
                                }else{
                                    current_due = rcv_pay-Number(value.pay_amount)
                                }

                                var html="<tr>" +
                                        "<td class='text-center'>" + date +"</td>" +
                                        "<td class='text-right'>" + decimal(value.rcv_amount) +"</td>" +
                                        "<td class='text-right'>" + decimal(value.pay_amount) +"</td>" +
                                        "<td class='text-right'>" + decimal(current_due) +"</td>" +
                                        "</tr>";
                                receive+=Number(value.rcv_amount);
                                payment+=Number(value.pay_amount);

                                $(".liabilityList tbody").append(html);
                            });

                            $(".liabilityList tfoot").html(
                                    "<tr>" +
                                    "<th class='text-center'>Total</th>" +
                                    "<th class='text-right'>" + decimal(receive) +"</th>" +
                                    "<th class='text-right'>" + decimal(payment) +"</th>" +
                                    "<th class='text-right'>" + decimal(due) +"</th>" +
                                    "</tr>"
                            );
                        }
                        else {

                            $("#OPform").hide();
                            $(".liabilityList tbody").empty();
                            $(".liabilityList tfoot").empty();
                            toastr.warning( 'Data not found. Try aging!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                });
            });

            $(document).on('click', '.creditorUpdate', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{csrf_token()}}';

                $.ajax({
                    type: "POST",
                    url: "{{ route('creditor_update') }}",
                    data: data,
                    success: function (response) {
                        if (response !=0) {
                            var json = JSON.parse(response)
                            $("#AddPerson").modal("show");
                            $("#AddPersonForm #action").val(2);
                            $("#creditor_id").val(json.id);
                            $("#name").val(json.name);
                            $("#mobile").val(json.mobile);
                            $("#address").val(json.address);
                        }
                        else {
                            toastr.warning( 'Data Cannot Read. Try aging!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        //console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                });
            });


            $(document).on('change', '#LoanPayment .loan_person_list', function () {
                var element = $(this);
                var del_id = element.val();
                var data = 'id=' + del_id + '&_token={{csrf_token()}}';

                $.ajax({
                    type: "POST",
                    url: "{{ route('payable_liability') }}",
                    data: data,
                    success: function (response) {
                        //console.log(response)
                        if (response>=0) {
                            $("#payable_receive_amount").val(response);
                        }
                        if(response==""){
                            $("#payable_receive_amount").val(0);
                        }
                        var amount = $("#payable_receive_amount").val();
                        if(amount<=0){
                          $("#LoanPayment .save").prop("disabled",true);
                        }else{
                            $("#LoanPayment .save").prop("disabled",false);
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                    }
                });
            });

        });

        function _loan_person(){
            var info = '_token={{csrf_token()}}';
            $.ajax({
                type: "POST",
                url: "{{ route('loan_person_list') }}",
                data: info,
                success: function (response) {
                    // console.log(response)
                    if(response!=0){
                        var json = JSON.parse(response);
                        $(".loan_person_list").empty();
                        var html="";
                        $.each(json, function (key, value) {
                            html+="<option value='"+value.id+"'>"+value.name+"</option>";
                        });
                        $(".loan_person_list").html(html);
                    }else{
                        toastr.warning( 'Failed to fetch expense head list. Try aging!', 'Warning');
                    }
                }
                ,
                error: function (request, status, error) {
                    console.log(request.responseText);
                }
            });
        }
        function decimal(num){
            var value = num/100*100;
            return value.toFixed(2);
        }
        function dateFormat(date){
            date = date.split("-");
            date = date[2]+"/"+date[1]+"/"+date[0];
            return date;
        }
    </script>

@endsection
