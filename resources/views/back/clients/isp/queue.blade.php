@extends('layouts.app')

@section('title', 'Queue Client')

@section('content')
    <style>
        h4 span {
            padding-right: 10px;
        }

        input.loading {
            background: url(http://www.xiconeditor.com/image/icons/loading.gif) no-repeat right center;
        }
    </style>
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    <h3 class="content-header-title" id="tabOption">Add Queue</h3>
                </div>
                <div class="content-header-right col-md-8 col-12">

                    <ul class="nav nav-tabs float-md-right">
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route("isp-clients") }}" >All Clients</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route("isp-queue") }}" >Add Queue</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route("isp-pppoe") }}" >Add PPPoE</a>
                        </li>
                        {{--<li class="nav-item">--}}
                            {{--<a class="nav-link" href="#">Import/Export</a>--}}
                        {{--</li>--}}
                    </ul>
                </div>
            </div>
            <div class="content-body">
                <!-- Zero configuration table -->
                <section id="configuration">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content collapse show">
                                    <div class="card-body card-dashboard">

                                        <div class="tab-content pt-1">
                                            <div class="tab-pane active" id="operation" aria-labelledby="base-tab2">

                                                @if(session()->has('msg'))
                                                    <div class="alert alert-success alert-dismissible">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        {{ session()->get('msg') }}

                                                    </div>
                                                @endif

                                                <div class="card collapse-icon accordion-icon-rotate right">

                                                    <div class="card-header text-right" id="headingBTwo" style="    padding-top: 0; padding-bottom: 0;">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseB2" aria-expanded="false" aria-controls="collapseB2">Import Excel File</button>
                                                        </h5>
                                                    </div>
                                                    <div id="collapseB2" class="collapse" aria-labelledby="headingBTwo">
                                                        <div class="card-body" style="border: 1px solid #ddd;border-radius: 10px;">
                                                            <div class="text-center">

                                                                <a href="{{ asset("app-assets/files/isp_queue_client_sheet.xlsx") }}" download="ISP Queue Client Sheet"><i class="ft-download"></i> Excel Demo Download</a>
                                                                <br>
                                                                <br>
                                                                <form action="{{ route('isp-client-import') }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="hidden" value="queue" name="client_type">
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


                                                    <div class="card-body" style="padding:0;">
                                                        <form id="DataForm" method="post" class="form-horizontal">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" value="queue" name="client">
                                                            <input type="hidden" value="1" name="action">

                                                            <div class="row">
                                                                <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">

                                                                    @if(auth()->user()->user_type != "reseller")
                                                                        <div class="row">
                                                                            <label for="company_id" class="col-sm-5 control-label">Company/Reseller <span class="text-danger">*</span></label>
                                                                            <div class="col-sm-7">
                                                                                <select class="form-control select2" name="company_id" id="company_id" required>
                                                                                    <option value="{{ auth()->user()->company_id }}"> {{ auth()->user()->name }} (O)</option>
                                                                                    @foreach($companies as $row)
                                                                                        <option value="{{ $row->auth_id }}">{{ $row->reseller_id }} :: {{ $row->reseller_name }} (R) </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <input type="hidden" name="company_id" value="{{ Settings::company_id() }}">
                                                                    @endif
                                                                    <div class="row">
                                                                        <label for="network_id" class="col-sm-5 control-label">Network<span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2" name="network_id" id="network_id" required>
                                                                                @foreach($networks as $row)
                                                                                    <option value="{{ $row->id }}">{{ $row->network_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="pop_id" class="col-sm-5 control-label">POP <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2" name="pop_id" id="pop_id" required>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="zone_id" class="col-sm-5 control-label">Zone Name<span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2" name="zone_id" id="zone_id" required>
                                                                                <option>Select Zone</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="node_id"
                                                                               class="col-sm-5 control-label">Node <span class="text-danger">*</span></label>

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
                                                                               class="col-sm-5 control-label">Sub Node <span
                                                                                    class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2"
                                                                                    name="box_id" id="box_id" required>
                                                                                <option>Select Sub Node</option>
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
                                                                            <div class="input-group">
                                                                                <input type="text" autocomplete="off"
                                                                                       name="client_username"
                                                                                       id="client_username"
                                                                                       class="form-control" required>

                                                                                <select id="id_prefix" name="prefix_id" onchange="_clientId()">
                                                                                    @foreach($id_prefixs as $prefix)
                                                                                        <option value="{{ $prefix->id }}">{{ $prefix->id_prefix_name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
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
                                                                        <label for="billing_date" class="col-sm-5 control-label">Billing Date <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <select name="billing_date" id="billing_date" class="form-control select2" required>
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
                                                                        <label for="ip_address" class="col-sm-5 control-label">IP Address <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="ip_address" id="ip_address" class="form-control" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="mac_address" class="col-sm-5 control-label">Dynamic MAC Address</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="mac_address" id="mac_address" class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="gpon_mac_address" class="col-sm-5 control-label">GPON/Epon MAC <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="gpon_mac_address" id="gpon_mac_address" class="form-control" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="olt_interface" class="col-sm-5 control-label">OLT Interface <span class="text-danger"></span></label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="olt_interface" id="olt_interface" class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="receive_power" class="col-sm-5 control-label">Receiver Power <span class="text-danger"></span></label>
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
                                                                                <option value="0">In-active</option>
                                                                                <option value="2">Locked</option>
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
                                                                               class="col-sm-5 control-label">User end Fiber Status </label>
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
                                                                        <label for="billing_responsible"
                                                                               class="col-sm-5 control-label">Billing Responsible
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2"
                                                                                    name="billing_responsible"
                                                                                    id="billing_responsible" required>
                                                                                @foreach($technicians as $row)
                                                                                    <option value="{{ $row->auth_id }}">{{ $row->emp_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="payment_id" class="col-sm-5 control-label">Payment Method <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <select class="form-control"
                                                                                    name="payment_id" id="payment_id">
                                                                                @foreach($payment_method as $row)
                                                                                    <option value="{{ $row->id }}">{{ $row->payment_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row ">
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
                                                                        <label for="signup_fee" class="col-sm-5 control-label">Signup Fee<span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <input  type="number"  min="0" name="signup_fee" id="signup_fee" class="form-control text-right" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="previous_bill" class="col-sm-5 control-label"> Previous Bill </label>
                                                                        <div class="col-sm-7">
                                                                            <input  type="number"  min="0" name="previous_bill" id="previous_bill" class="form-control text-right">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="permanent_discount" class="col-sm-5 control-label">Permanent Discount </label>
                                                                        <div class="col-sm-7">
                                                                            <input  type="number"  min="0" name="permanent_discount" id="permanent_discount" class="form-control text-right">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row ">
                                                                        <label for="discount" class="col-sm-5 control-label">Discount </label>

                                                                        <div class="col-sm-7">
                                                                            <input  type="number"  min="0" name="discount" id="discount" class="form-control text-right">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row ">
                                                                        <label for="payable_amount" class="col-sm-5 control-label">Payable Amount </label>
                                                                        <div class="col-sm-7">
                                                                            <input  type="number"  min="0" name="payable_amount" id="payable_amount" class="form-control text-right" readonly>
                                                                            <i><b class="text-danger bill_msg"></b></i>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row ">
                                                                        <label for="receive_amount" class="col-sm-5 control-label">Receive Amount</label>
                                                                        <div class="col-sm-7">
                                                                            <input  type="number"  min="0" name="receive_amount" id="receive_amount" class="form-control text-right">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row ">
                                                                        <label for="receive_date" class="col-sm-5 control-label">Receive Date <span class="text-danger"></span></label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="receive_date" id="receive_date" class="form-control text-left datepicker">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="note" class="col-sm-5 control-label">Note </label>
                                                                        <div class="col-sm-7">
                                                                            <textarea name="note" id="note" class="form-control"></textarea>
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
        #DataForm .row {
            margin-top: 10px;
        }

        #DataForm .row label {
            text-align: left;
        }

    </style>
@endsection

@section("page_script")
    <script type="text/javascript">

        $(document).ready(function () {
            __startup();

            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_client') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response) {
                        console.log(response); $(".save").text("Save").prop("disabled", false);
                        if (response == 1) {
                           // $("#DataForm").trigger("reset");
                            toastr.success('Data Saved Successfully!', 'Success');
                            _clientId();
                        }
                        else {
                            toastr.warning('Data Cannot Saved. Try aging!', 'Warning');
                        }
                       
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        $(".save").text("Save").prop("disabled", false);
                    }
                });
            });
            $(document).on('keyup', '#1client_username', function(e) {
                var $t = $(e.currentTarget);
                $t.addClass('loading');
                $.ajax({
                    url: $t.data('ajax'),
                    success: function(data) {
                        //dostuff
                        $t.removeClass('loading');
                    }
                });
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
                                _clientId();
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
                $("#thana").val( $(":selected",this).attr("data"));
                _node(element);
            });

            $(document).on('change', '#box_id', function () {
                $("#address").val( $(":selected",this).attr("data"));
            });

            $(document).on('change', '#package_id', function () {
                _payableFee();
            });
            $(document).on('keyup input', '#signup_fee', function () {
                _payableFee();
            });
            $(document).on('keyup input', '#previous_bill', function () {
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
            _pop($('#network_id'));
            _zone($('#network_id'));
            _clientId();
            _payableFee();
        }
        function _payableFee() {
            var price = Number($('#package_id :selected').attr("data"));
            var previous_bill = Number($('#previous_bill').val());
            var signup_fee = Number($('#signup_fee').val());
            var permanent_discount = Number($('#permanent_discount').val());
            var discount = Number($('#discount').val());
            var billing_date = new Date();
            billing_date.setDate($('#billing_date').val());

            //console.log(30-$('#billing_date').val())
            var lastDayOfMonth = new Date();
            lastDayOfMonth.setDate(30);

            if (billing_date != "") {

                //var days = datediff(billing_date, lastDayOfMonth);
                var days = 31-$('#billing_date').val();
                //console.log(days);

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

            var payable_amount = (total + signup_fee + previous_bill) - (permanent_discount + discount);

            $('#payable_amount').val(Math.round(payable_amount));
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
                            if(key==0){
                                $("#thana").val(value.zone_thana)
                            }
                            html += "<option value='" + value.id + "' data='"+value.zone_thana+"'>" + value.zone_name_en + "</option>";
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
                            if(key==0){
                                $("#address").val(value.box_location)
                            }
                            html += "<option value='" + value.id + "' data='"+value.box_location+"'>" + value.box_name + "</option>";
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
            var prefix= $("#id_prefix").val();
            var info = "prefix="+prefix+"&_token={{csrf_token()}}";
            $.ajax({
                type: "POST",
                url: "{{ route('get_client_id') }}",
                data: info,
                success: function (response) {
//console.log(response)
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
           // var Difference_In_Time = second.getTime() - first.getTime();

// To calculate the no. of days between two dates
            //var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

            //return Difference_In_Days
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