@extends('layouts.app')

@section('title', 'Expense')

@section('content')

    <style>
        form .row {
            margin-top: 10px;
        }

        .form-group {
            margin: 0 !important;
        }
    </style>

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    <h3 class="content-header-title"><span id="tabOption">@yield('title')</span></h3>
                </div>
                <div class="content-header-right col-md-8 col-12">

                    <ul class="nav nav-tabs float-md-right">
                        <li class="nav-item">
                            <a onclick="document.getElementById('tabOption').innerHTML='Add Expense Head'"
                                class="nav-link addnewHead" id="base-tab0" data-toggle="tab" aria-controls="addExpenseHead"
                                href="#addExpenseHead" aria-expanded="true">Add Expense Head</a>
                        </li>
                        <li class="nav-item">
                            <a onclick="document.getElementById('tabOption').innerHTML='Expense Head',expenseHeadTable()"
                                class="nav-link" id="base-tab01" data-toggle="tab" aria-controls="ExpenseHeadList"
                                href="#ExpenseHeadList" aria-expanded="true">Show Expense Head</a>
                        </li>
                        <li class="nav-item">
                            <a onclick="document.getElementById('tabOption').innerHTML='Expense'" class="nav-link active"
                                id="base-tab1" data-toggle="tab" aria-controls="DataList" href="#DataList"
                                aria-expanded="true">Show @yield('title')</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="content-body"><!-- Zero configuration table -->
                <section id="configuration">
                    <div class="row">
                        <div class="col-12">

                            <div class="tab-content pt-1">
                                <div role="tabpanel" class="tab-pane active" id="DataList" aria-expanded="true"
                                    aria-labelledby="base-tab1">

                                    <div class="card">
                                        <div class="card-content collapse show">
                                            <div class="card-body card-dashboard">
                                                <form class="myForm" method="post">
                                                    @csrf
                                                    <input name="action" type="hidden" value="1" class="action">
                                                    <input name="id" type="hidden" id="id">
                                                    <div class="box-body">
                                                        <div class="tab-content">
                                                            <div role="tabpanel" class="tab-pane active" id="addnewuser">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="payment_date"
                                                                                class="active">Voucher No <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" class="form-control"
                                                                                name="expense_voucher_no"
                                                                                id="expense_voucher_no"
                                                                                placeholder="ex: 123456" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="payment_date"
                                                                                class="active">Transaction Date <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input id="payment_date" name="payment_date"
                                                                                class="form-control datepicker"
                                                                                type="text" required=""
                                                                                autocomplete="off">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <label for="expense_head_id">Expense Head</label>
                                                                        <select class="form-control select2" id="expense_head_id"
                                                                            name="expense_head_id">
                                                                            <option value="">Select Expense Head</option>
                                                                            @foreach ($expense_heads as $row)
                                                                                <option value="{{ $row->id }}">
                                                                                    {{ $row->expense_head_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <label for="account_id">Account Head</label>
                                                                        <select name="account_id"
                                                                            id="account_id"
                                                                            class="form-control select2">
                                                                            <option value="">Select Account Head</option>
                                                                            @foreach ($employees as $row)
                                                                                <option value="{{ $row->id }}">
                                                                                    {{ $row->emp_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="amount" class="active">Amount
                                                                                <span class="text-danger">*</span></label>
                                                                            <input id="amount" type="number"
                                                                                name="amount" class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <label for="note" class="active">Note</label>
                                                                        <input id="note" type="text"
                                                                            name="note" class="form-control">
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <button type="submit" style="margin-top: 23px;"
                                                                            class="btn btn-primary save">Save</button>
                                                                        <button type="reset" style="margin-top: 23px;"
                                                                            class="btn btn-danger reset">Add New</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-content collapse show">
                                            <div class="card-body card-dashboard">
                                                <form class="searchForm">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="active">Filter by Account Head</label>
                                                                <select id="filter_account_id"
                                                                    class="form-control select2">
                                                                    <option value="">All</option>

                                                                    
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="amount" class="active">Date From <span
                                                                    class="text-danger">*</span></label>
                                                            <input id="from" type="text" required
                                                                value="<?= date('Y-m-d') ?>" name="from"
                                                                class="form-control datepicker text-center">

                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="amount" class="active">Date To <span
                                                                    class="text-danger">*</span></label>
                                                            <input id="to" type="text" required
                                                                value="<?= date('Y-m-d') ?>" name="to"
                                                                class="form-control datepicker text-center">

                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" style="margin-top: 25px;"
                                                                class="btn btn-primary search">Search</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <table id="datatable_list" class="table display table-bordered table-striped"
                                                    style="width: 100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Account Head</th>
                                                            <th>Transaction Type</th>
                                                            <th>Amount</th>
                                                            <th>Note</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>

                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            

                                <div class="tab-pane" id="addExpenseHead" aria-labelledby="base-tab0">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
                                                <form id="ExpenseHeadForm" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="">
                                                        <input type="hidden" id="action" name="action">
                                                        <input type="hidden" id="id" name="id">
                                                        <div class="row">
                                                            <label for="expense_head_name"
                                                                class="col-sm-4 control-label">Expense
                                                                Head<code>*</code></label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control"
                                                                    name="expense_head_name" id="expense_head_name"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label for="expense_head_note"
                                                                class="col-sm-4 control-label">Head
                                                                Description<code>*</code></label>
                                                            <div class="col-sm-8">
                                                                <textarea class="form-control" name="expense_head_note" id="expense_head_note"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit"
                                                        class="btn btn-primary mt-1 mb-0 save">Save</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div role="tabpanel" class="tab-pane" id="ExpenseHeadList" aria-expanded="true"
                                    aria-labelledby="base-tab01">

                                    <div class="table-responsive">
                                        <table id="expenseHeadDatalist"
                                            class="table table-striped table-bordered zero-configuration"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Expense Head</th>
                                                    <th>Description</th>
                                                    <th>Action</th>
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


    <!-- Modal -->
    <div class="modal fade text-left" id="AdvanceSalaryModal" data-animation="pulse" tabindex="-1"
        role="dialog"data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary white">
                    <h4 class="modal-title white" id="myModalLabel8">Advance Salary</h4>
                    <button type="button" class="close modalClose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="AdvancedSalaryForm" method="post">
                    <input type="hidden" id="store_id" name="store_id">
                    <input type="hidden" id="StoreAction" name="action" value="1">
                    <input type="hidden" id="emp_id" name="emp_id">
                    <input type="hidden" id="advanced_head_id" name="expense_head_id">
                    <div class="modal-body">
                        @csrf
                        <div class="col-md-12">

                            <div class="row">
                                <label for="salary_voucher_no" class="col-sm-4 control-label">Voucher No <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" autocomplete="off" name="salary_voucher_no"
                                        id="salary_voucher_no" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <label for="ref_emp_id" class="col-sm-4 control-label">Employee <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" name="ref_emp_id" id="ref_emp_id" required>
                                        <option></option>
                                        @foreach ($employees as $row)
                                            <option value="{{ $row->id }}">{{ $row->emp_id }} ::
                                                {{ $row->emp_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="warn text-danger"></span>
                                </div>
                            </div>

                            <div class="row">
                                <label for="advance_amount" class="col-sm-4 control-label">Advanced Amount <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" autocomplete="off" name="advance_amount" id="advance_amount"
                                        class="form-control text-right" required onchange="per_installment()"
                                        onkeyup="per_installment()">
                                </div>
                            </div>

                            <div class="row">
                                <label for="installment_time" class="col-sm-4 control-label">Installment Times <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="number" autocomplete="off" name="installment_time"
                                        id="installment_time" class="form-control text-right" required
                                        onchange="per_installment()" onkeyup="per_installment()">
                                </div>
                            </div>

                            <div class="row">
                                <label for="per_installment" class="col-sm-4 control-label">Amount of Per Installment
                                    <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" autocomplete="off" name="" id="per_installment_amount"
                                        class="form-control text-right" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <label for="dates" class="col-sm-4 control-label">Date <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="date" name="receive_date" id="receive_date" class="form-control"
                                        required>
                                </div>
                            </div>

                            <div class="row">
                                <label for="receive_from" class="col-sm-4 control-label">Responsible <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" name="receive_from" id="receive_from" required>
                                        <option></option>
                                        @foreach ($employees as $row)
                                            <option value="{{ $row->id }}">{{ $row->emp_id }} ::
                                                {{ $row->emp_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-secondary modalClose"
                            data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger save">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END: Content-->


@endsection
@section('page_script')
    <script type="text/javascript">
        var extable, exHeadTable;
        $(document).ready(function() {

            $(document).on('submit', "#DataForm", function(e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_expense') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(response) {
                        //console.log(response);
                        if (response == 1) {
                            $("#DataForm").trigger("reset");
                            //toastr.success("Have fun storming the castle!","Miracle Max Says")
                            toastr.success('Data Saved Successfully!', 'Success');
                            $("[href='#DataList']").tab("show");
                            expenseTable()
                            $("#tabOption").html('Expense');
                        } else {
                            toastr.warning('Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(".save").text("Save").prop("disabled", false);
                    },
                    error: function(request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        $(".save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('click', '.addnew', function() {
                $("#action").val(1);
                $("#id").val("");
                $("#DataForm").trigger("reset");
            });

            $(document).on('click', '.update', function() {
                var element = $(this);
                var del_id = element.attr("id");
                var info = 'id=' + del_id + "&_token={{ csrf_token() }}";
                $(element).html('<i class="ft-loader"></i>').prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('expense_update') }}",
                    data: info,
                    success: function(response) {
                        $(element).html('Edit').prop("disabled", false);
                        $("#tabOption").html('Update Expense');
                        if (response != 0) {
                            $("[href='#operation']").tab("show");
                            var json = JSON.parse(response);
                            $("#action").val(2);
                            $("#id").val(json.id);
                            $("#expense_head_id").val(json.expense_head_id).trigger("change");
                            $("#expense_voucher_no").val(json.expense_voucher_no);
                            $("#expense_date").val(json.expense_date);
                            $("#expense_amount").val(json.expense_amount);
                            $("#expense_note").val(json.expense_note);
                            $("#responsible_person").val(json.responsible_person).trigger(
                                "change");
                        } else {
                            toastr.warning('Server Error. Try aging!', 'Warning');
                        }
                    }
                });
            });

            $(document).on('click', '.approve', function() {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{ csrf_token() }}';

                if (confirm("Are you sure you want to approve this?")) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('expense_approve') }}",
                        data: data,
                        success: function(response) {
                            if (response == 1) {
                                toastr.success('Successful!', 'Success');
                                expenseTable()
                            } else {
                                toastr.warning('Failed. Try aging!', 'Warning');
                            }
                        },
                        error: function(request, status, error) {
                            //console.log(request.responseText);
                            toastr.warning('Server Error. Try aging!', 'Warning');
                        }
                    });
                }
                return false;

            });


            $(document).on('submit', "#ExpenseHeadForm", function(e) {
                e.preventDefault();
                var _this = "#ExpenseHeadForm ";
                $(_this + " .save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_expense_head') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(response) {
                        //console.log(response);
                        if (response == 1) {
                            $("#DataForm").trigger("reset");
                            //toastr.success("Have fun storming the castle!","Miracle Max Says")
                            toastr.success('Data Saved Successfully!', 'Success');
                            $("[href='#ExpenseHeadList']").tab("show");
                            _expenseHead();
                            exHeadTable.ajax.reload();
                            $("#tabOption").html('Expense Head');
                        } else {
                            toastr.warning('Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(_this + " .save").text("Save").prop("disabled", false);
                    },
                    error: function(request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        $(_this + " .save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('click', '.addnewHead', function() {
                var _this = "#ExpenseHeadForm ";
                $(_this + " #action").val(1);
                $(_this + " #id").val("");
                $(_this + " .operation_type").text("Add New");
                $(_this).trigger("reset");
            });

            $(document).on('click', '.updateHead', function() {
                var _this = "#ExpenseHeadForm ";
                var element = $(this);
                var del_id = element.attr("id");
                var info = 'id=' + del_id + "&_token={{ csrf_token() }}";
                $(element).html('<i class="ft-loader"></i>').prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('expense_head_update') }}",
                    data: info,
                    success: function(response) {
                        $("#tabOption").html('Update Expense Head');
                        $(element).html('Edit').prop("disabled", false);
                        if (response != 0) {
                            $("[href='#addExpenseHead']").tab("show");
                            var json = JSON.parse(response);
                            $(_this + " #action").val(2);
                            $(_this + " #id").val(json.id);
                            $("#expense_head_name").val(json.expense_head_name);
                            $("#expense_head_note").val(json.expense_head_note);
                        } else {
                            toastr.warning('Server Error. Try aging!', 'Warning');
                        }
                    }
                });
            });

            $(document).on('click', '.deleteHead', function() {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{ csrf_token() }}';

                if (confirm("Are you sure you want to delete this?")) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('expense_head_delete') }}",
                        data: data,
                        success: function(response) {
                            if (response == 1) {
                                toastr.success('Data removed Successfully!', 'Success');
                                element.parents("tr").animate({
                                    backgroundColor: "#003"
                                }, "slow").animate({
                                    opacity: "hide"
                                }, "slow");
                                _expenseHead();
                            } else {
                                toastr.warning('Data Cannot Removed. Try aging!', 'Warning');
                            }
                        },
                        error: function(request, status, error) {
                            //console.log(request.responseText);
                            toastr.warning('Server Error. Try aging!', 'Warning');
                        }
                    });
                }
                return false;

            });

            $(document).on('change', '#expense_head_id', function() {
                if ($(this).val() == 8) {
                    $("#AdvanceSalaryModal").modal("show");
                    $("#advanced_head_id").val(8);
                } else {
                    $("#AdvanceSalaryModal").modal("hide");
                }

            });

            $(document).on('click', '.modalClose', function() {
                $("#expense_head_id").val("").trigger("change");
                $("#ref_emp_id").val("").trigger("change");
            });


            $(document).on('submit', "#AdvancedSalaryForm", function(e) {
                e.preventDefault();

                $("#AdvancedSalaryForm .save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_advanced_salary') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(response) {
                        //console.log(response);
                        if (response == 1) {
                            $("#AdvancedSalaryForm").trigger("reset");
                            toastr.success('Data Saved Successfully!', 'Success');
                            $("#AdvanceSalaryModal").modal("hide");
                            $("[href='#DataList']").tab("show");
                            table.ajax.reload();
                        } else {
                            toastr.warning('Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $("#AdvancedSalaryForm .save").text("Save").prop("disabled", false);
                    },
                    error: function(request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        $("#AdvancedSalaryForm .save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('change', "#ref_emp_id", function() {

                var del_id = $(this).val();
                var info = 'emp_id=' + del_id + "&_token={{ csrf_token() }}";
                $(".warn").html("");
                $("#AdvancedSalaryForm .save").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('check_previous_advanced_salary') }}",
                    data: info,
                    success: function(response) {
                        if (response > 0) { //true
                            $(".warn").html("<b>**This employee have due installment!</b>");
                            $("#AdvancedSalaryForm .save").prop("disabled", true);
                        } else { //false
                            $(".warn").html("");
                            $("#AdvancedSalaryForm .save").prop("disabled", false);
                        }
                    }
                });
            });

        });
        expenseTable();

        function expenseTable() {
            $('#datatable_list').DataTable
            ({
                // "pageLength": 50,
                "bFilter": false,
                "bPaginate": false,
                "paging": false,
                "ordering": false,
                "info": false,
                "destroy":true,
                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                // "aaSorting": [[0, 'asc']],
                // "scrollX": true,
                // "scrollCollapse": true,
                "columnDefs": [
                    {
                        "targets": [0,1,2,3, 4, 5],
                        "orderable": false
                    },
                    {
                        "targets": [0,2, 3, 5],
                        "class": "text-center"
                    }
                ],
                "ajax": {
                    url: "{{ route('expense_datalist') }}",
                    data:{
                        _token : "{{ csrf_token() }}",
                        account_id : $("#filter_account_id").val(),
                        from : $("#from").val(),
                        to : $("#to").val()
                    },
                    type: "post",
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],
        
                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {
        
                            // jsonData.data[i][5] = '<div class="btn-group" role="group">' +
                            //     '<button class="update btn btn-warning btn-xs btn-flat">' +
                            //     '<span class="fa fa-pencil"></span></button>' +
                            //     '<button  class="deleteUser btn btn-danger btn-xs btn-flat">' +
                            //     '<span class="fa fa-trash"></span></button>' +
                            //     '</div>';
                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                      console.log(request.responseText);
                        //alert("error")
                    }
                }
            });

        }

        function expenseHeadTable() {
            exHeadTable = $('#expenseHeadDatalist').DataTable({
                "destroy": true,
                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [
                    [0, 'desc']
                ],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [{
                    "targets": [3],
                    "orderable": false
                }, {
                    "targets": [0, 3],
                    className: "text-center"
                }],
                "ajax": {
                    url: "{{ route('expense_head_datalist') }}",
                    type: "post",
                    "data": {
                        _token: "{{ csrf_token() }}"
                    },
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function(jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {

                            jsonData.data[i][3] = '<div class="btn-group align-top" role="group">' +
                                '<button id="' + jsonData.data[i][0] + '" class="updateHead edit' + jsonData
                                .data[i][0] + ' btn btn-primary btn-sm badge">' +
                                'Edit</button>' +
                                '<button  id=' + jsonData.data[i][0] +
                                ' class="deleteHead btn btn-danger btn-sm badge">' +
                                'Delete</button>' +
                                '</div>';
                        }
                        return jsonData.data;
                    },
                    error: function(request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                    }
                }
            });

        }

        function _expenseHead() {
            var info = '_token={{ csrf_token() }}';
            $.ajax({
                type: "POST",
                url: "{{ route('expenseHeadList') }}",
                data: info,
                success: function(response) {
                    // console.log(response)
                    if (response != 0) {
                        var json = JSON.parse(response);
                        $("#expense_head_id").empty();
                        var html = "";
                        $.each(json, function(key, value) {
                            html += "<option value='" + value.id + "'>" + value.expense_head_name +
                                "</option>";
                        });
                        $("#expense_head_id").html(html);
                    } else {
                        toastr.warning('Failed to fetch expense head list. Try aging!', 'Warning');
                    }
                },
                error: function(request, status, error) {
                    console.log(request.responseText);
                }
            });
        }

        function per_installment() {
            var amount = Number($("#advance_amount").val());
            var times = Number($("#installment_time").val());
            if (!times) {
                times = 1
            }
            $("#per_installment_amount").val(Math.round(amount / times));
        }
    </script>

@endsection
