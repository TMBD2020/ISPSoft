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
                    <h3 class="content-header-title" id="tabOption">@yield("title")</h3>
                </div>
                <div class="content-header-right col-md-8 col-12">

                    <ul class="nav nav-tabs float-md-right">
                        <li class="nav-item">
                            <a onclick="document.getElementById('tabOption').innerHTML='Clients'"
                               class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="DataList"
                               href="#DataList" aria-expanded="true">Show @yield("title")</a>
                        </li>
                        <li class="nav-item">
                            <a onclick="document.getElementById('tabOption').innerHTML='Add New Client'"
                               class="nav-link addnew" id="base-tab2" data-toggle="tab" aria-controls="operation"
                               href="#operation" aria-expanded="false">Add New</a>
                        </li>
                        <li class="nav-item">
                            <a onclick="document.getElementById('tabOption').innerHTML='Client Import/Export'"
                               class="nav-link" id="base-tab3" data-toggle="tab" aria-controls="importExport"
                               href="#importExport" aria-expanded="false">Import/Export</a>
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

                                                @if(Auth::user()->id==1)
                                                <div class="card inner-card" style="margin:0;">
                                                    <div class="card-body" style="padding: 6px 10px;">
                                                        <div class="row" style="margin:0;">
                                                            <div class="col-md-3">
                                                                <label for="search_company_id"
                                                                       class="control-label">Company/Reseller
                                                                </label>

                                                                <select class="form-control select2" id="search_company_id"
                                                                        required>
                                                                    @foreach($companies as $row)
                                                                        <option value="{{ $row->id }}"
                                                                                >@if($row->reseller_type != 0)  {{ $row->reseller_id }} :: @endif {{ $row->reseller_name }} @if($row->reseller_type != 0) (R) @else {{"(O)"}} @endif</option>
                                                                    @endforeach
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
                                                    <input type="hidden" value="{{Auth::user()->id}}" id="search_company_id">
                                                @endif

                                                <div class="text-center client_summery">
                                                    <h4>
                                                        <span>Total: <i
                                                                    class="text-primary total">{{ $total_client }}</i></span>
                                                        <span>Active: <i
                                                                    class="text-success actived">{{ $active_client }}</i></span>
                                                        <span>De-active: <i
                                                                    class="text-danger inactived">{{ $inactive_client }}</i></span>
                                                        <span>Lock: <i
                                                                    class="text-warning locked">{{ $locked_client }}</i></span>
                                                    </h4>
                                                </div>


                                                <div class="">
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
                                            <div class="tab-pane" id="operation" aria-labelledby="base-tab2">
                                                <div class="card">
                                                    <div class="card-body" style="padding:0;">
                                                        <form id="DataForm" method="post" class="form-horizontal">
                                                            {{ csrf_field() }}

                                                            <input type="hidden" id="action" name="action">
                                                            <input type="hidden" id="id" name="id">

                                                            <div class="row">
                                                                <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">

                                                                    <div class="row">
                                                                        <label for="company_id"
                                                                               class="col-sm-5 control-label">Company/Reseller
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2"
                                                                                    name="company_id" id="company_id"
                                                                                    required>
                                                                                @foreach($companies as $row)


                                                                                    <option value="{{ $row->id }}"
                                                                                            >@if($row->reseller_type != 0)  {{ $row->reseller_id }} :: @endif {{ $row->reseller_name }} @if($row->reseller_type != 0) (R) @else {{"(O)"}} @endif</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
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
                                                                               class="col-sm-5 control-label">POP <span
                                                                                    class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2"
                                                                                    name="pop_id" id="pop_id" required>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="zone_id"
                                                                               class="col-sm-5 control-label">Zone Name
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2"
                                                                                    name="zone_id" id="zone_id"
                                                                                    required>
                                                                                <option>Select Zone</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="node_id"
                                                                               class="col-sm-5 control-label">Node <span
                                                                                    class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2"
                                                                                    name="node_id" id="node_id"
                                                                                    required>
                                                                                <option>Select Node</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="box_id"
                                                                               class="col-sm-5 control-label">Box <span
                                                                                    class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2"
                                                                                    name="box_id" id="box_id" required>
                                                                                <option>Select Box</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>


                                                                    <div class="row">
                                                                        <label for="package_id"
                                                                               class="col-sm-5 control-label">Package
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2"
                                                                                    name="package_id" id="package_id"
                                                                                    required>
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
                                                                        <label for="client_name"
                                                                               class="col-sm-5 control-label">Client
                                                                            Name <span
                                                                                    class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" autocomplete="off"
                                                                                   name="client_name" id="client_name"
                                                                                   class="form-control" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="client_username"
                                                                               class="col-sm-5 control-label">Client ID
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" autocomplete="off"
                                                                                   readonly name="client_username"
                                                                                   id="client_username"
                                                                                   class="form-control" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="client_password"
                                                                               class="col-sm-5 control-label">Password<span
                                                                                    class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" autocomplete="off"
                                                                                   value="123123" name="client_password"
                                                                                   id="client_password"
                                                                                   class="form-control" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="payment_dateline"
                                                                               class="col-sm-5 control-label">Payment
                                                                            Deadline <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select name="payment_dateline"
                                                                                    id="payment_dateline"
                                                                                    class="form-control select2"
                                                                                    required>
                                                                                @for($i=1;$i<=31;$i++)
                                                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="termination_date"
                                                                               class="col-sm-5 control-label">Termination
                                                                            Date</label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" autocomplete="off"
                                                                                   name="termination_date"
                                                                                   id="termination_date"
                                                                                   class="form-control  text-center datepicker">
                                                                        </div>

                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="billing_date"
                                                                               class="col-sm-5 control-label">Billing
                                                                            Date <span
                                                                                    class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">

                                                                            <select name="billing_date"
                                                                                    id="billing_date"
                                                                                    class="form-control select2"
                                                                                    required>
                                                                                @for($i=1;$i<=31;$i++)
                                                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="join_date"
                                                                               class="col-sm-5 control-label">Join Date
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" autocomplete="off"
                                                                                   name="join_date" id="join_date" value="{{ date("d/m/Y") }}"
                                                                                   class="form-control  datepicker text-center" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="cell_no"
                                                                               class="col-sm-5 control-label">Cell No
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="cell_no"
                                                                                   id="cell_no" class="form-control"
                                                                                   required>
                                                                            <span class="text-danger cellMsg"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="technician_id"
                                                                               class="col-sm-5 control-label">Technician
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2"
                                                                                    name="technician_id"
                                                                                    id="technician_id" required>
                                                                                @foreach($technicians as $row)
                                                                                    <option value="{{ $row->id }}">{{ $row->emp_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="alter_cell_no_1"
                                                                               class="col-sm-5 control-label">Alternative
                                                                            Cell No 1 </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="alter_cell_no_1"
                                                                                   id="alter_cell_no_1"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="alter_cell_no_2"
                                                                               class="col-sm-5 control-label">Alternative
                                                                            Cell No 2</label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="alter_cell_no_2"
                                                                                   id="alter_cell_no_2"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="alter_cell_no_3"
                                                                               class="col-sm-5 control-label">Alternative
                                                                            Cell No 3 </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="alter_cell_no_3"
                                                                                   id="alter_cell_no_3"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">

                                                                        <label for="alter_cell_no_4"
                                                                               class="col-sm-5 control-label">Alternative
                                                                            Cell No 4 </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="alter_cell_no_4"
                                                                                   id="alter_cell_no_4"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="address"
                                                                               class="col-sm-5 control-label">Address
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <textarea name="address" id="address"
                                                                                      class="form-control"
                                                                                      required></textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="thana"
                                                                               class="col-sm-5 control-label">Thana
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="thana" id="thana"
                                                                                   class="form-control" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="occupation"
                                                                               class="col-sm-5 control-label">Occupation </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="occupation"
                                                                                   id="occupation" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="email"
                                                                               class="col-sm-5 control-label">Email
                                                                            Address </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="email" id="email"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>


                                                                    <div class="row">
                                                                        <label for="nid" class="col-sm-5 control-label">NID </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="nid" id="nid"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">


                                                                    <div class="row">
                                                                        <label for="previous_isp"
                                                                               class="col-sm-5 control-label">Previous
                                                                            ISP </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="previous_isp"
                                                                                   id="previous_isp"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="client_type_id"
                                                                               class="col-sm-5 control-label">Type of
                                                                            Client <span
                                                                                    class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control"
                                                                                    name="client_type_id"
                                                                                    id="client_type_id" required>
                                                                                @foreach($client_types as $row)
                                                                                    <option value="{{ $row->id }}">{{ $row->client_type_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="connectivity_id" class="col-sm-5 control-label">Type of Connectivity <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control" name="connectivity_id" id="connectivity_id" required>
                                                                                @foreach($connectivity_types as $row)
                                                                                    <option value="{{ $row->id }}">{{ $row->connectivity_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">

                                                                        <label for="ip_address" class="col-sm-5 control-label">IP Address <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="ip_address" id="ip_address" class="form-control" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">

                                                                        <label for="mac_address"
                                                                               class="col-sm-5 control-label">MAC Address <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="mac_address" id="mac_address" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">

                                                                        <label for="gpon_mac_address"
                                                                               class="col-sm-5 control-label">GPON/Epon
                                                                            MAC <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="gpon_mac_address" id="gpon_mac_address" class="form-control" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">

                                                                        <label for="olt_interface"
                                                                               class="col-sm-5 control-label">OLT Interface <span class="text-danger"></span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="olt_interface" id="olt_interface" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">

                                                                        <label for="receive_power"
                                                                               class="col-sm-5 control-label">Receiver Power <span class="text-danger"></span></label>

                                                                        <div class="col-sm-7">
                                                                            <div class="input-group">
                                                                                <input type="text" name="receive_power" id="receive_power"  class="form-control text-right">
                                                                                <div class="input-group-addon">dbm</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="connection_mode" class="col-sm-5 control-label">Connectivity Type <span  class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <select class="form-control" name="connection_mode" id="connection_mode" required>
                                                                                <option value="1">Active</option>
                                                                                <option value="0">De-active</option>
                                                                                <option value="2">Locked</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="cable_id" class="col-sm-5 control-label">Cable Type <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control" name="cable_id"
                                                                                    id="cable_id" required>
                                                                                @foreach($cable_types as $row)
                                                                                    <option value="{{ $row->id }}">{{ $row->cable_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="required_cable"
                                                                               class="col-sm-5 control-label">Required
                                                                            Cable </label>

                                                                        <div class="col-sm-7">
                                                                            <div class="input-group">
                                                                                <input type="text" name="required_cable" id="required_cable" class="form-control text-right">

                                                                                <div class="input-group-addon">meter</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="user_and_fiber_status"
                                                                               class="col-sm-5 control-label">User end
                                                                            Fiber Status </label>

                                                                        <div class="col-sm-7">
                                                                            <textarea name="user_and_fiber_status"
                                                                                      id="user_and_fiber_status"
                                                                                      class="form-control"></textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="payment_alert_sms"
                                                                               class="col-sm-6 control-label">Payment
                                                                            Alert SMS</label>

                                                                        <div class="col-sm-6">
                                                                            <input type="radio" name="payment_alert_sms"
                                                                                   id="payment_alert_sms_1" value="1"
                                                                                   checked> Yes
                                                                            <input type="radio" name="payment_alert_sms"
                                                                                   id="payment_alert_sms_0" value="0">
                                                                            No
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="payment_conformation_sms"
                                                                               class="col-sm-6 control-label">Payment
                                                                            Conformation SMS</label>

                                                                        <div class="col-sm-6">
                                                                            <input type="radio"
                                                                                   name="payment_conformation_sms"
                                                                                   id="payment_conformation_sms_1"
                                                                                   value="1" checked> Yes
                                                                            <input type="radio"
                                                                                   name="payment_conformation_sms"
                                                                                   id="payment_conformation_sms_0"
                                                                                   value="0"> No
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="picture"
                                                                               class="col-sm-5 control-label">Picture</label>

                                                                        <div class="col-sm-7">
                                                                            <div class="picture"></div>
                                                                            <input type="file" name="picture"
                                                                                   id="picture" class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="payment_id"
                                                                               class="col-sm-5 control-label">Payment
                                                                            Method <span
                                                                                    class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control"
                                                                                    name="payment_id" id="payment_id">
                                                                                @foreach($payment_method as $row)
                                                                                    <option value="{{ $row->id }}">{{ $row->payment_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row hideEdit">
                                                                        <label class="col-sm-5 control-label"></label>

                                                                        <div class="col-sm-7">
                                                                            <label for="custom_bill"
                                                                                   class="control-label">
                                                                                <input type="checkbox"
                                                                                       name="custom_bill"
                                                                                       id="custom_bill"> Generate full
                                                                                month bill
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="signup_fee"
                                                                               class="col-sm-5 control-label">Signup Fee
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="signup_fee"
                                                                                   id="signup_fee"
                                                                                   class="form-control text-right"
                                                                                   required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="permanent_discount"
                                                                               class="col-sm-5 control-label">Permanent
                                                                            Discount </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="permanent_discount"
                                                                                   id="permanent_discount"
                                                                                   class="form-control text-right">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row hideEdit">
                                                                        <label for="discount"
                                                                               class="col-sm-5 control-label">Discount </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="discount"
                                                                                   id="discount"
                                                                                   class="form-control text-right">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row hideEdit">
                                                                        <label for="payable_amount"
                                                                               class="col-sm-5 control-label">Payable
                                                                            Amount </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="payable_amount"
                                                                                   id="payable_amount"
                                                                                   class="form-control text-right"
                                                                                   readonly>
                                                                            <i><b class="text-danger bill_msg"></b></i>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row hideEdit">
                                                                        <label for="receive_amount"
                                                                               class="col-sm-5 control-label">Receive
                                                                            Amount <span
                                                                                    class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="receive_amount"
                                                                                   id="receive_amount"
                                                                                   class="form-control text-right"
                                                                                   required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row hideEdit">
                                                                        <label for="receive_date"
                                                                               class="col-sm-5 control-label">Receive
                                                                            Date <span
                                                                                    class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="receive_date"
                                                                                   id="receive_date"
                                                                                   class="form-control text-left   datepicker"
                                                                                   required>
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

                                                                <div class="col-md-12">
                                                                    <div class="pull-right">
                                                                        <label><input type="checkbox" name="welcome_sms" value="1"> SMS</label>
                                                                        <label><input type="checkbox" name="welcome_email" value="1" checked> Email</label>
                                                                    </div>
                                                                    <div style="clear:both;"></div>
                                                                    <button type="submit"
                                                                            class="btn pull-right btn-primary mt-1 mb-0 save">Save
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>


                                                    </div>
                                                </div>

                                            </div>
                                            <div role="tabpanel" class="tab-pane" id="importExport" aria-expanded="true" aria-labelledby="base-tab3">
                                                <div class="text-center">
                                                    <h3>Import Excel File</h3>

                                                    <a href="{{ asset("app-assets/files/isp_client_sheet.xlsx") }}" download="isp client sheet"><i class="ft-download"></i> Excel Demo Download</a>
                                                    <br>
                                                    <br>
                                                    <form action="{{ route('isp-client-import') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                                                            <img class="excel_img" style="display: none;width: 50px;float: left;" src="{{ asset("app-assets/images/excel-icon.png") }}">
                                                            <span class="fileName" style="float: left;"></span>

                                                            <div class="custom-file text-left">
                                                                <input  onchange="return Validate(this)" type="file" id="file" name="file" class="custom-file-input" required="" autocomplete="off">
                                                                <label  class="custom-file-label" for="customFile">Choose file</label>
                                                            </div>
                                                            <br>
                                                            <br>
                                                            <i style="color:red;">Note: <u>Network Station, PoP,Zone, Node, Box</u> table cannot be blank. </i>
                                                        </div>

                                                        <button class="btn btn-primary">Import data</button>
                                                    </form>
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
                    <input type="hidden" value="catv" name="client_type">
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

            var table;


            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ url('save_client') }}",
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
                        console.log(request.responseText);
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

            $(document).on('click', '.addnew', function () {
                $("#action").val(1);
                $("#id").val("");
                $("#client_username")
                $("#client_password").attr("required", "required").val("");
                $("#receive_amount").attr("required", "required");
                $("#receive_date").attr("required", "required");
                $(".operation_type").text("Add New");
                $("#DataForm").trigger("reset");
                $(".hideEdit").show();
                $("#signup_fee").prop("disabled", false);
                _clientId();
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

            $(document).on('click', '.update', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var info = 'id=' + del_id + "&_token={{csrf_token()}}";
                $(".edit" + del_id).html('<i class="ft-loader"></i>').prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{url('client_update')}}",
                    data: info,
                    success: function (response) {
                        $(".edit" + del_id).html('<i class="ft-edit"></i> Edit').prop("disabled", false);
                        if (response != 0) {
                            $(".hideEdit").hide();
                            $("#receive_amount").removeAttr("required", "required");
                            $("#receive_date").removeAttr("required", "required");
                            $("#tabOption").text("Update Client");
                            $("[href='#operation']").tab("show");
                            var json = JSON.parse(response);
                            $("#action").val(2);
                            $("#id").val(json.id);
                            $("#client_password").removeAttr("required").val("");
                            $("#client_username").val(json.client_id).prop("disabled", true);
                            $("#client_name").val(json.client_name);
                            $("#zone_id").val(json.zone_id).trigger("change");
                            $("#box_id").val(json.box_id).trigger("change");
                            $("#node_id").val(json.node_id).trigger("change");
                            $("#network_id").val(json.network_id).trigger("change");
                            $("#pop_id").val(json.pop_id).trigger("change");
                            $("#package_id").val(json.package_id).trigger("change");
                            $("#payment_dateline").val(json.payment_dateline).trigger("change");
                            $("#termination_date").val(json.termination_date);
                            $("#billing_date").val(json.billing_date);
                            $("#cell_no").val(json.cell_no);
                            $("#technician_id").val(json.technician_id).trigger("change");
                            $("#company_id").val(json.company_id).trigger("change");
                            $("#payment_id").val(json.payment_id).trigger("change");
                            $("#signup_fee").val(json.signup_fee).prop("disabled", true);
                            $("#permanent_discount").val(json.permanent_discount);
                            $("#alter_cell_no_1").val(json.alter_cell_no_1);
                            $("#alter_cell_no_2").val(json.alter_cell_no_2);
                            $("#alter_cell_no_3").val(json.alter_cell_no_3);
                            $("#alter_cell_no_4").val(json.alter_cell_no_4);
                            $("#address").val(json.address);
                            $("#thana").val(json.thana);
                            $("#join_date").val(json.join_date);
                            $("#occupation").val(json.occupation);
                            $("#email").val(json.email);
                            $("#nid").val(json.nid);
                            $("#previous_isp").val(json.previous_isp);
                            $("#client_type_id").val(json.client_type_id).trigger("change");
                            $("#connectivity_id").val(json.connectivity_id).trigger("change");
                            $("#ip_address").val(json.ip_address);
                            $("#mac_address").val(json.mac_address);
                            $("#gpon_mac_address").val(json.gpon_mac_address);
                            $("#olt_interface").val(json.olt_interface);
                            $("#connection_mode").val(json.connection_mode).trigger("change");
                            $("#cable_id").val(json.cable_id).trigger("change");
                            $("#required_cable").val(json.required_cable);
                            $("#user_and_fiber_status").val(json.user_and_fiber_status);
                            if (json.picture) {
                                $(".picture").html("<img src='" + json.picture + "' style='width:100px; height: 100px;'>");
                            } else {
                                $(".picture").html("");
                            }
                            if (json.payment_conformation_sms == 1) {
                                $("#payment_conformation_sms_1").attr('checked', true).val(1);
                                $("#payment_conformation_sms_0").attr('checked', false).val(0);
                            } else {
                                $("#payment_conformation_sms_1").attr('checked', false).val(1);
                                $("#payment_conformation_sms_0").attr('checked', true).val(0);
                            }
                            if (json.payment_alert_sms == 1) {
                                $("#payment_alert_sms_1").attr('checked', true).val(1);
                                $("#payment_alert_sms_0").attr('checked', false).val(0);
                            } else {
                                $("#payment_alert_sms_1").attr('checked', false).val(1);
                                $("#payment_alert_sms_0").attr('checked', true).val(0);
                            }

                            $("#note").val(json.note);
                            _clientCount();
                        } else {
                            toastr.warning('Server Error. Try aging!', 'Warning');
                        }
                    }
                });
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
                                _clientId();
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

            $(document).on('change', '#custom_bill', function () {
                _payableFee();
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

            $(document).on('change', '#package_id', function () {
                _payableFee();
            });
            $(document).on('keyup input', '#signup_fee', function () {
                _payableFee();
            });
            $(document).on('keyup input', '#permanent_discount', function () {
                _payableFee();
            });
            $(document).on('keyup input', '#discount', function () {
                _payableFee();
            });
            $(document).on('keyup input change', '#billing_date', function () {
                _payableFee();
            });
        });

        function __startup() {
            clientData();
            _pop($('#network_id'));
            _zone($('#network_id'));
            _clientId();
            _payableFee();
        }

        function clientData(){
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
                    "data": {_token: "{{csrf_token()}}",company_id:  $("#search_company_id").val()},
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
//                            jsonData.data[i][8] = '<div class="btn-group align-middle clientId' + jsonData.data[i][0] + '" role="group">' +
//                                    '<button id="' + jsonData.data[i][0] + '" class="send_sms edit' + jsonData.data[i][0] + ' btn btn-success btn-sm badge">' +
//                                    '<span class="ft-message-circle"></span> SMS</button>' +
//                                    '<button  id="' + jsonData.data[i][0] + '" is_locked="' + jsonData.data[i][6] + '" class="lockCl btn btn-info btn-sm badge">' +
//                                    '<span class="ft-lock"></span> ' + lock + '</button>' +
//                                    '<button id="' + jsonData.data[i][0] + '" class="update edit' + jsonData.data[i][0] + ' btn btn-primary btn-sm badge">' +
//                                    '<span class="ft-edit"></span> Edit</button>' +
//                                    '<button  id=' + jsonData.data[i][0] + ' class="deleteData btn btn-danger btn-sm badge">' +
//                                    '<span class="ft-delete"></span> Del</button>' +
//                                    '</div>';
                            jsonData.data[i][8] =
                                    '<button class="btn btn-outline-purple btn-sm dropdown-toggle clientId' + jsonData.data[i][0] + '" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                    'Action'+
                                    '</button>'+
                                    '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">'+
                                    '<a id="' + jsonData.data[i][0] + '" class="send_sms dropdown-item text-success" href="#"><span class="ft-message-circle"></span> SMS</a>'+
                                    '<a id="' + jsonData.data[i][0] + '" class="lockCl dropdown-item text-info" href="#" is_locked="' + jsonData.data[i][6] + '"><span class="ft-lock"></span> ' + lock + '</a>'+
                                    '<a id="' + jsonData.data[i][0] + '" class="update dropdown-item text-primary" href="#"><span class="ft-edit"></span> Edit</a>'+
                                    '<a id="' + jsonData.data[i][0] + '" class="deleteData dropdown-item text-danger" href="#"><span class="ft-trash"></span> Del</a>'+
                                    '</div>';

                            jsonData.data[i][6] = status;
                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                    }
                }
            });

            table.on('order.dt search.dt', function () {
                table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }

        function _payableFee() {
            var price = Number($('#package_id :selected').attr("data"));
            var signup_fee = Number($('#signup_fee').val());
            var permanent_discount = Number($('#permanent_discount').val());
            var discount = Number($('#discount').val());
            var billing_date = "{{ date("Y-m-") }}" + $('#billing_date').val();
            var lastDayOfMonth = new Date();
            lastDayOfMonth.setDate(30);
            if (billing_date != "") {
                var days = datediff(parseDate(billing_date), parseDate(lastDayOfMonth));
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

            var payable_amount = (total + signup_fee) - (permanent_discount + discount);

            $('#payable_amount').val(Math.round(payable_amount));
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

        function _clientId() {
            var info = "_token={{csrf_token()}}";
            $.ajax({
                type: "POST",
                url: "{{ url('get_client_id') }}",
                data: info,
                success: function (response) {
                    if (response !== 0) {
                        $("#client_username").val(response);
                    } else {
                        toastr.warning('Failed to fetch client id. Try aging!', 'Warning');
                    }
                },
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
