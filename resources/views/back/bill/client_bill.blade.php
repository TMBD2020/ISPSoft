@extends('layouts.app')

@section('title', 'Client Bill')

@section('content')
    <style>
        .table td,
        .table th {
            font-size: 13px;
            vertical-align: middle;
        }

        .table td {
            padding: 1px 5px;
        }

        .table th {
            padding: 5px;
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
                            <a onclick="document.getElementById('tabOption').innerHTML='All Bill',all_bill_table()"
                                class="nav-link active" id="base-tab2" data-toggle="tab" aria-controls="allBill"
                                href="#allBill" aria-expanded="true">All Bill</a>
                        </li>
                        <li class="nav-item">
                            <a onclick="document.getElementById('tabOption').innerHTML='Unpaid Bill',pendingBill()"
                                class="nav-link" id="base-tab3" data-toggle="tab" aria-controls="DataList" href="#DataList"
                                aria-expanded="false">Unpaid Bill</a>
                        </li>
                        <li class="nav-item">
                            <a onclick="document.getElementById('tabOption').innerHTML='Today Collection',todayBillCollection()"
                                class="nav-link" id="base-tab4" data-toggle="tab" aria-controls="TodayDataList"
                                href="#TodayDataList" aria-expanded="false">Today Collection</a>
                        </li>
                        <li class="nav-item">
                            <a onclick="document.getElementById('tabOption').innerHTML='All Collection',allBillCollection()"
                                class="nav-link" id="base-tab5" data-toggle="tab" aria-controls="all_collection"
                                href="#all_collection" aria-expanded="false">All Collection</a>
                        </li>
                        <li class="nav-item" style="display: none">
                            <a class="nav-link" id="base-tab2" data-toggle="tab" aria-controls="operation" href="#operation"
                                aria-expanded="false"></a>
                        </li>
                        <li class="nav-item" style="display: none">
                            <a class="nav-link" id="base-tab34" data-toggle="tab" aria-controls="bill_view"
                                href="#bill_view" aria-expanded="false"></a>
                        </li>

                        <li class="nav-item" style="display: none">
                            <a class="nav-link" id="base-tab2" data-toggle="tab" aria-controls="createOtherBill"
                                href="#createOtherBill" aria-expanded="false"></a>
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
                                            <div role="tabpanel" class="tab-pane active" id="allBill" aria-expanded="true"
                                                aria-labelledby="base-tab1">

                                                <div class="collapse-icon accordion-icon-rotate right">
                                                    <div class="card-header text-right" id="headingBTwo"
                                                        style="    padding-top: 0; padding-bottom: 0;">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" data-toggle="collapse"
                                                                data-target="#collapseB2" aria-expanded="false"
                                                                aria-controls="collapseB2">Filter Bill</button>
                                                        </h5>
                                                    </div>
                                                    <div id="collapseB2" class="collapse" aria-labelledby="headingBTwo">
                                                        <div class="card-body"
                                                            style="border: 1px solid #ddd;border-radius: 10px;">
                                                            <form id="IncomeSearchForm" method="post" novalidate>
                                                                {{ csrf_field() }}
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            <label>Filter Type</label>
                                                                            <select class="form-control"  id="filter_type" name="filter_type">
                                                                                <option value="1">Commitment Date</option>
                                                                                <option value="2">Payment Date</option>
                                                                                <option value="3">Billing Date</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            <label>From</label>
                                                                            <input type="date" name="date_from"
                                                                                id="date_from" class="form-control"
                                                                                value="{{ $date_from }}" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            <label>To</label>
                                                                            <input type="date" name="date_to"
                                                                                id="date_to" class="form-control "
                                                                                value="{{ $date_to }}" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <button type="button"
                                                                                style="margin-top:25px;"
                                                                                class="btn btn-primary mb-0 search"
                                                                                onclick="all_bill_table(1)">Search</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table id="all_bill_table"
                                                                class="table table-striped table-bordered zero-configuration"
                                                                style="width: 100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th rowspan="2">#</th>
                                                                        <th rowspan="2">ID/Name/Mobile</th>
                                                                        <th colspan="3" class="text-center">Date</th>
                                                                        <th rowspan="2">Package</th>
                                                                        <th rowspan="2">Payable(Tk)</th>
                                                                        <th rowspan="2">Action</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Payment</th>
                                                                        <th>Billing</th>
                                                                        <th>Commitment</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane" id="DataList" aria-expanded="true"
                                                aria-labelledby="base-tab1">

                                                <div class="table-responsive">
                                                    <table id="unpaid_bill_table"
                                                        class="table table-striped table-bordered zero-configuration"
                                                        style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>ID/Name</th>
                                                                <th>Mobile</th>
                                                                <th>Commitment<br>Date</th>
                                                                <th>Package</th>
                                                                <th>P. Discount</th>
                                                                <th>Payable</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane" id="TodayDataList"
                                                aria-expanded="true" aria-labelledby="base-tab1">

                                                <div class="table-responsive">
                                                    <table id="ToCollist"
                                                        class="table table-striped table-bordered zero-configuration"
                                                        style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
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
                                            <div role="tabpanel" class="tab-pane" id="all_collection"
                                                aria-expanded="true" aria-labelledby="base-tab1">

                                                {{-- <div class="card inner-card" style="margin:0;"> --}}
                                                {{-- <div class="card-body" style="padding: 6px 10px;"> --}}
                                                {{-- <form id="AllCollSearchForm" method="post" novalidate> --}}
                                                {{-- {{csrf_field()}} --}}
                                                {{-- <div class="row" style="margin:0;"> --}}
                                                {{-- <div class="col-md-2"> --}}
                                                {{-- </div> --}}
                                                {{-- <div class="col-md-3"> --}}
                                                {{-- <div class="form-group" style="margin:0;"> --}}
                                                {{-- <input type="date" id="all_collection_date_from" class="form-control" value="{{ $date_from }}" required> --}}
                                                {{-- </div> --}}
                                                {{-- </div> --}}
                                                {{-- <div class="col-md-3"> --}}
                                                {{-- <div class="form-group" style="margin:0;"> --}}
                                                {{-- <input type="date"id="all_collection_date_to" class="form-control " value="{{ $date_to }}" required> --}}
                                                {{-- </div> --}}
                                                {{-- </div> --}}
                                                {{-- <div class="col-md-3"> --}}
                                                {{-- <div class="form-group"  style="margin:0;"> --}}
                                                {{-- <button type="button" onclick="allBillCollection()" class="btn btn-primary mb-0 search">Search</button> --}}
                                                {{-- </div> --}}
                                                {{-- </div> --}}
                                                {{-- </div> --}}
                                                {{-- </form> --}}
                                                {{-- </div> --}}
                                                {{-- </div> --}}
                                                <div class="table-responsive">
                                                    <table id="all_collection_table"
                                                        class="table table-striped table-bordered zero-configuration"
                                                        style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
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
                                            <div class="tab-pane" id="bill_view" aria-labelledby="base-tab34">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="card-title pull-left">Client Billing History</div>
                                                        <button type="button"
                                                            class="pull-right btn btn-sm btn-primary backL">Back To
                                                            List</button>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="col-lg-12 col-xs-12  col-md-12 col-sm-12">
                                                            <div class="table-responsive clientBillHistory"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="tab-pane" id="operation" aria-labelledby="base-tab2">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="card-title pull-left">Bill Collect</div>
                                                        <button type="button"
                                                            class="pull-right btn btn-sm btn-primary backL">Back To
                                                            List</button>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
                                                            <form id="DataForm" method="post">
                                                                {{ csrf_field() }}
                                                                <div class="">
                                                                    <input type="hidden" id="action" name="action">
                                                                    <input type="hidden" id="id" name="id">
                                                                    <input type="hidden" id="client_initial_id"
                                                                        name="client_initial_id">

                                                                    <div class="row">
                                                                        <label class="col-sm-4 control-label">Client<span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                name="client" id="client" required
                                                                                readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label class="col-sm-4 control-label">Payable <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text"
                                                                                class="form-control text-right"
                                                                                name="payable" id="payable" readonly
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    @if (auth()->user()->can('isp-bill-discount-input-show'))
                                                                        <div class="row">
                                                                            <label class="col-sm-4 control-label">Discount
                                                                            </label>
                                                                            <div class="col-sm-8">
                                                                                <input type="number"
                                                                                    class="form-control text-right"
                                                                                    name="discount_amount" id="discount"
                                                                                    min="0" value="0">
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    <div class="row">
                                                                        <label class="col-sm-4 control-label">Receive <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="number"
                                                                                class="form-control text-right"
                                                                                @if (auth()->user()->can('isp-bill-receive-input-disable')) readonly @endif
                                                                                name="receive_amount" id="receive"
                                                                                min="1" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label class="col-sm-4 control-label">Date</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" readonly
                                                                                class="form-control datepicker_startdate"
                                                                                name="receive_date" id="date"
                                                                                value="{{ date('d/m/Y') }}" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label
                                                                            class="col-sm-4 control-label">Mobile</label>
                                                                        <div class="col-sm-8">
                                                                            <div class="input-group">
                                                                                <input type="text" readonly
                                                                                    class="form-control" name="mobile"
                                                                                    id="mobile">
                                                                                <div class="input-group-addon btn-primary ft-edit takeUpdateMobile"
                                                                                    style="cursor: pointer"
                                                                                    title="Add ID"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label class="col-sm-4 control-label">Payment
                                                                            Method</label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control"
                                                                                id="payment_method_id"
                                                                                name="payment_method_id">
                                                                                @foreach ($payments as $row)
                                                                                    <option value="{{ $row->id }}">
                                                                                        {{ $row->payment_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label class="col-sm-4 control-label">Collected
                                                                            By</label>
                                                                        <div class="col-sm-8">
                                                                            @if (auth()->user()->can('collected-by-custom'))
                                                                                <select class="form-control select2"
                                                                                    id="collected_by" name="collected_by">
                                                                                    @foreach ($employees as $row)
                                                                                        <option
                                                                                            value="{{ $row->id }}">
                                                                                            {{ $row->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            @else
                                                                                <input type="hidden" id="collected_by"
                                                                                    name="collected_by"
                                                                                    value="{{ auth()->user()->id }}">
                                                                                <input type="text" readonly
                                                                                    class="form-control"
                                                                                    value="{{ auth()->user()->name }}"
                                                                                    required>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label class="col-sm-4 control-label">Note</label>
                                                                        <div class="col-sm-8">
                                                                            <textarea class="form-control" name="note" id="note"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    @if(auth()->user()->can('collection-sms-checkbox'))
                                                                    <div class="row">
                                                                        <div class="col-sm-4">
                                                                            <label><input type="checkbox"
                                                                                    name="payment_confirm_sms"
                                                                                    value="1" checked> SMS</label>
                                                                            <label><input type="checkbox"
                                                                                    name="payment_confirm_email"
                                                                                    value="1" checked> Email</label>
                                                                        </div>
                                                                        <div style="clear:both;"></div>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                                <button type="submit"
                                                                    class="btn btn-primary mt-1 mb-0 save">Save</button>
                                                                <button type="button"
                                                                    class="btn btn-danger mt-1 mb-0 backL">Cancel</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="tab-pane" id="createOtherBill" aria-labelledby="base-tab2">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="card-title pull-left">Create Other Bill</div>
                                                        <button type="button"
                                                            class="pull-right btn btn-sm btn-primary backL">Back To
                                                            List</button>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
                                                            <form id="OtherBillForm" method="post">
                                                                {{ csrf_field() }}
                                                                <div class="">
                                                                    <input type="hidden" id="action2" name="action">
                                                                    <input type="hidden" id="id2" name="id">
                                                                    <input type="hidden" id="client_initial_id2"
                                                                        name="client_initial_id">

                                                                    <div class="row">
                                                                        <label class="col-sm-4 control-label">Client<span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                name="client" id="client2" required
                                                                                readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label
                                                                            class="col-sm-4 control-label">Details/Particular
                                                                            <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                name="particular" id="particular"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label class="col-sm-4 control-label">Bill Amount
                                                                            <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="number"
                                                                                class="form-control text-right"
                                                                                name="bill_amount" id="bill_amount2"
                                                                                min="0" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label class="col-sm-4 control-label">Discount
                                                                        </label>
                                                                        <div class="col-sm-8">
                                                                            <input type="number"
                                                                                class="form-control text-right"
                                                                                name="discount_amount" id="discount2"
                                                                                min="0" value="0">
                                                                        </div>
                                                                    </div>

                                                                    {{-- <div class="row"> --}}
                                                                    {{-- <label class="col-sm-4 control-label">Receive <span class="text-danger">*</span></label> --}}
                                                                    {{-- <div class="col-sm-8"> --}}
                                                                    {{-- <input type="number" class="form-control text-right"  name="receive_amount" id="receive" min="1" required> --}}
                                                                    {{-- </div> --}}
                                                                    {{-- </div> --}}

                                                                    <div class="row">
                                                                        <label class="col-sm-4 control-label">Date</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" readonly
                                                                                class="form-control datepicker_startdate"
                                                                                name="bill_date" id="bill_date2"
                                                                                value="{{ date('d/m/Y') }}" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label class="col-sm-4 control-label">Note</label>
                                                                        <div class="col-sm-8">
                                                                            <textarea class="form-control" name="note" id="note2"></textarea>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <button type="submit"
                                                                    class="btn btn-primary mt-1 mb-0 save">Create</button>
                                                                <button type="button"
                                                                    class="btn btn-danger mt-1 mb-0 backL">Cancel</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!--end create other bill-->

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

    {{-- mobile no change modal --}}
    <div class="modal fade text-left" id="updateMobileModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning white">
                    <h4 class="modal-title white" id="ModalLabel">Update Mobile No</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateMobileForm" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="mobile_id">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="new_mobile"> New Mobile No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="new_mobile" id="new_mobile" required
                                    autocomplete="off">
                            </div>
                            <div class="col-sm-6" style="    margin-top: 10px;">
                                <label> <input type="radio" checked name="set_as" value="alternative"> Set as
                                    Alternative Number</label>
                                <label> <input type="radio" name="set_as" value="primary"> Set as Primary
                                    Number</label>
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

    <div class="modal fade text-left" id="sms_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8"
        aria-hidden="true">
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
                            <div class="row">
                                Sent To : <b class="clientName"></b>
                            </div>
                            <div class="row">
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

    <div class="modal fade text-left" id="commitmentDateModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel8" aria-hidden="true">
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
                            <div class="row">
                                Choose Date :
                            </div>
                            <div class="row">
                                <input type="date" class="form-control" name="commitment_date" required />
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
    {{-- responsible person modal --}}
    <div class="modal fade text-left" id="responisblePersonModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel8" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark white">
                    <h4 class="modal-title white" id="myModalLabel8">Billing Responsible Person</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="responisblePersonForm" method="post">
                    @csrf
                    <input type="hidden" name="client_id" class="client_id">
                    <div class="modal-body">

                        <div class="form-group">
                          
                           <span class="client"></span>

                        </div>
                        <div class="form-group">
                            <label>Responsible Person <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="billing_responsible" name="billing_responsible" required>
                                <option value="">Select One</option>
                                @foreach ($employees as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-dark pull-right" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        form .row {
            margin-bottom: 10px;
        }

        .taka {
            font-size: 16px;
            ;
        }
    </style>
@endsection
@section('page_script')
    <script type="text/javascript">
        var table_unpaid_bill, table_today_collection, table_all_collection, table_all_bill;
    </script>
    <script src="{{ asset('app-assets/js/project/bill/isp_bill.js?v=1008') }}" type="text/javascript"></script>

@endsection
