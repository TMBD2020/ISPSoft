@extends('layouts.app')

@section('title', 'Update Client')

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
                    <h3 class="content-header-title" id="tabOption">Update Client</h3>
                </div>
                <div class="content-header-right col-md-8 col-12">

                    <ul class="nav nav-tabs float-md-right">
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route("isp-clients") }}" >All Clients</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("isp-queue") }}" >Add Queue</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route("isp-pppoe") }}" >Add PPPoE</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Update Client</a>
                        </li>
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
                                                <div class="card">
                                                    <div class="card-body" style="padding:0;">
                                                        <form id="DataForm" method="post" class="form-horizontal">
                                                            {{ csrf_field() }}

                                                            <input type="hidden" value="2" name="action" >
                                                            <input type="hidden" value="{{ $clients->id }}" name="id">

                                                            <div class="row">
                                                                <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">

                                                                    <div class="row">
                                                                        <label for="company_id" class="col-sm-5 control-label">Company/Reseller<span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2" name="company_id" id="company_id" required>
                                                                                 <option value="{{ auth()->user()->company_id }}" {{ auth()->user()->company_id ==$clients->company_id ? 'selected' : '' }}> {{ auth()->user()->name }} (O)</option>
                                                                                
                                                                                @foreach($companies as $row)
                                                                                    <option value="{{ $row->id }}" {{ $row->id==$clients->company_id ? 'selected' : '' }}>@if($row->reseller_type != 0)  {{ $row->reseller_id }} :: @endif {{ $row->reseller_name }} @if($row->reseller_type != 0) (R) @else {{"(O)"}} @endif</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="network_id" class="col-sm-5 control-label">Network<span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2" name="network_id" id="network_id" required>
                                                                                @foreach($networks as $row)
                                                                                    <option value="{{ $row->id }}" {{ $row->id==$clients->network_id ? 'selected' : '' }}>{{ $row->network_name }}</option>
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
                                                                                    <option value="{{ $row->id }}" {{ $clients->package_id == $row->id ? 'selected' :'' }}
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
                                                                        <label for="client_name" class="col-sm-5 control-label">Client Name <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" value="{{ $clients->client_name }}" autocomplete="off" name="client_name" id="client_name" class="form-control" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="client_username" class="col-sm-5 control-label">Client ID<span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" autocomplete="off" value="{{ $clients->client_id }}" readonly name="client_username" id="client_username" class="form-control" required>
                                                                        </div>
                                                                    </div>

                                                                    {{--<div class="row">--}}
                                                                        {{--<label for="client_password" class="col-sm-5 control-label">Password<span class="text-danger">*</span></label>--}}
                                                                        {{--<div class="col-sm-7">--}}
                                                                            {{--<input type="text" autocomplete="off" name="client_password" id="client_password" class="form-control">--}}
                                                                        {{--</div>--}}
                                                                    {{--</div>--}}

                                                                    <div class="row">
                                                                        <label for="payment_dateline" class="col-sm-5 control-label">PaymentDeadline <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <select name="payment_dateline" id="payment_dateline" class="form-control select2" required>
                                                                                @for($i=1;$i<=31;$i++)
                                                                                    <option value="{{ $i }}" {{ $clients->payment_dateline == $i ? 'selected' :'' }}>{{ $i }}</option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="termination_date" class="col-sm-5 control-label">Termination Date</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" autocomplete="off" name="termination_date" value="{{ date("d/m/Y", strtotime($clients->termination_date)) }}" class="form-control  text-center datepicker">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="billing_date" class="col-sm-5 control-label">Billing Date <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <select name="billing_date" id="billing_date" class="form-control select2" required>
                                                                                @for($i=1;$i<=31;$i++)
                                                                                    <option value="{{ $i }}" {{ $clients->billing_date == $i ? 'selected' :'' }}>{{ $i }}</option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="join_date" class="col-sm-5 control-label">Join Date<span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" autocomplete="off" name="join_date" id="join_date" value="{{ date("d/m/Y", strtotime($clients->join_date)) }}" class="form-control  datepicker text-center" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="cell_no" class="col-sm-5 control-label">Cell No<span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="cell_no" id="cell_no" class="form-control" value="{{ $clients->cell_no }}" required>
                                                                            <span class="text-danger cellMsg"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="technician_id" class="col-sm-5 control-label">Technician</label>
                                                                        <div class="col-sm-7">
                                                                            <select class="form-control select2" name="technician_id" id="technician_id" >
                                                                                @foreach($technicians as $row)
                                                                                    <option value="{{ $row->id }}" {{ $clients->technician_id == $row->id ? 'selected' :'' }}>{{ $row->emp_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="alter_cell_no_1" class="col-sm-5 control-label">Alternative Cell No 1 </label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="alter_cell_no_1" value="{{ $clients->alter_cell_no_1 }}" id="alter_cell_no_1" class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="alter_cell_no_2" class="col-sm-5 control-label">Alternative Cell No 2</label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="alter_cell_no_2" value="{{ $clients->alter_cell_no_2 }}"
                                                                                   id="alter_cell_no_2"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="alter_cell_no_3"
                                                                               class="col-sm-5 control-label">Alternative
                                                                            Cell No 3 </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="alter_cell_no_3" value="{{ $clients->alter_cell_no_3 }}"
                                                                                   id="alter_cell_no_3"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">

                                                                        <label for="alter_cell_no_4"
                                                                               class="col-sm-5 control-label">Alternative
                                                                            Cell No 4 </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="alter_cell_no_4"  value="{{ $clients->alter_cell_no_4 }}"
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
                                                                                      required>{{$clients->address }}</textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="thana"
                                                                               class="col-sm-5 control-label">Thana
                                                                            <span class="text-danger">*</span></label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="thana" id="thana" value="{{$clients->thana }}"
                                                                                   class="form-control" required>
                                                                        </div>
                                                                    </div>



                                                                </div>

                                                                <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
                                                                    <div class="row">
                                                                        <label for="occupation"
                                                                               class="col-sm-5 control-label">Occupation </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="occupation" value="{{$clients->occupation }}"
                                                                                   id="occupation" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="email"
                                                                               class="col-sm-5 control-label">Email
                                                                            Address </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="email" id="email" value="{{$clients->email }}"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="nid" class="col-sm-5 control-label">NID </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="nid" id="nid" value="{{$clients->nid }}"
                                                                                   class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="previous_isp"
                                                                               class="col-sm-5 control-label">Previous
                                                                            ISP </label>

                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="previous_isp"
                                                                                   id="previous_isp"
                                                                                   class="form-control" value="{{ $clients->previous_isp }}">
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
                                                                                    <option value="{{ $row->id }}" {{ $clients->client_type_id==$row->id ? "selected":'' }}>{{ $row->client_type_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    @if($clients->ip_address)
                                                                        <div class="row">
                                                                            <label for="ip_address" class="col-sm-5 control-label">IP Address <span class="text-danger">*</span></label>
                                                                            <div class="col-sm-7">
                                                                                <input type="text" name="ip_address" readonly id="ip_address" class="form-control" required value="{{ $clients->ip_address }}">
                                                                            </div>
                                                                        </div>


                                                                        <input type="hidden" name="client" value="queue">

                                                                    @else
                                                                        <div class="row">
                                                                            <label for="pppoe_username" class="col-sm-5 control-label">PPPoE Username <span class="text-danger">*</span></label>
                                                                            <div class="col-sm-7">
                                                                                <input type="text" name="pppoe_username" id="pppoe_username" class="form-control" required readonly value="{{ $clients->pppoe_username }}">
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <label for="pppoe_password" class="col-sm-5 control-label">PPPoE Password <span class="text-danger">*</span></label>
                                                                            <div class="col-sm-7">
                                                                                <input type="text" name="pppoe_password" id="pppoe_password" class="form-control" value="{{ $clients->pppoe_password }}">
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden" name="client" value="pppoe">
                                                                        @endif
                                                                    <div class="row">
                                                                        <label for="mac_address" class="col-sm-5 control-label">Dynamic MAC Address</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="mac_address" id="mac_address" class="form-control"  value="{{ $clients->mac_address }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="gpon_mac_address" class="col-sm-5 control-label">GPON/Epon MAC <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="gpon_mac_address" id="gpon_mac_address" class="form-control" required value="{{ $clients->gpon_mac_address }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="olt_interface" class="col-sm-5 control-label">OLT Interface <span class="text-danger"></span></label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="olt_interface" id="olt_interface" class="form-control" value="{{ $clients->olt_interface }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="receive_power" class="col-sm-5 control-label">Receiver Power <span class="text-danger"></span></label>
                                                                        <div class="col-sm-7">
                                                                            <div class="input-group">
                                                                                <input type="text" name="receive_power" id="receive_power"  class="form-control text-right" value="{{ $clients->receive_power }}">
                                                                                <div class="input-group-addon">dbm</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="connection_mode" class="col-sm-5 control-label">Connectivity Type <span  class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <select class="form-control" name="connection_mode" id="connection_mode" required >
                                                                                <option value="1" {{ $clients->connection_mode==1 ? 'selected' : '' }}>Active</option>
                                                                                <option value="0" {{ $clients->connection_mode==0 ? 'selected' : '' }}>In-active</option>
                                                                                <option value="2" {{ $clients->connection_mode==2 ? 'selected' : '' }}>Locked</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="required_cable"
                                                                               class="col-sm-5 control-label">Required
                                                                            Cable </label>

                                                                        <div class="col-sm-7">
                                                                            <div class="input-group">
                                                                                <input type="text" name="required_cable" id="required_cable" class="form-control text-right" value="{{ $clients->required_cable }}">
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
                                                                                      class="form-control">{{ $clients->user_and_fiber_status }}</textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="payment_alert_sms"
                                                                               class="col-sm-6 control-label">Payment
                                                                            Alert SMS</label>

                                                                        <div class="col-sm-6">
                                                                            <input type="radio" name="payment_alert_sms" id="payment_alert_sms_1" value="1" {{ $clients->payment_alert_sms==1 ? 'checked' : '' }}>Yes
                                                                            <input type="radio" name="payment_alert_sms" id="payment_alert_sms_0" value="0" {{ $clients->payment_alert_sms==0 ? 'checked' : '' }}>No
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="payment_conformation_sms" class="col-sm-6 control-label">Payment Conformation SMS</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="radio" name="payment_conformation_sms" id="payment_conformation_sms_1" value="1" {{ $clients->payment_conformation_sms==1 ? 'checked' : '' }}> Yes
                                                                            <input type="radio" name="payment_conformation_sms" id="payment_conformation_sms_0" value="0" {{ $clients->payment_conformation_sms==0 ? 'checked' : '' }}> No
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="picture" class="col-sm-5 control-label">Picture</label>
                                                                        <div class="col-sm-7">
                                                                            <div class="picture">
                                                                                @if($clients->picture)
                                                                                    <img src="{{ $clients->picture}}">
                                                                                    @endif
                                                                            </div>
                                                                            <input type="file" name="picture" id="picture" class="form-control">
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
                                                                                    <option value="{{ $row->id }}"  {{ $clients->billing_responsible==$row->id ? 'checked' : '' }}>{{ $row->emp_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="payment_id" class="col-sm-5 control-label">PaymentMethod <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <select class="form-control"
                                                                                    name="payment_id" id="payment_id">
                                                                                @foreach($payment_method as $row)
                                                                                    <option value="{{ $row->id }}" {{ $clients->payment_id==$row->id ? 'checked' : '' }}>{{ $row->payment_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="signup_fee" class="col-sm-5 control-label">Signup Fee<span class="text-danger">*</span></label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="signup_fee" id="signup_fee" class="form-control text-right" required value="{{ $clients->signup_fee }}" readonly>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="permanent_discount" class="col-sm-5 control-label">Permanent Discount </label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="permanent_discount" id="permanent_discount" class="form-control text-right" value="{{ $clients->permanent_discount }}" readonly>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="note" class="col-sm-5 control-label">Note </label>
                                                                        <div class="col-sm-7">
                                                                            <textarea name="note" id="note" class="form-control">{{ $clients->note }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12">

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
                       // console.log(response)
                        unblockLoad()
                        if (response == 1) {
                            toastr.success('Data Saved Successfully!', 'Success');
                        }
                        else {
                            toastr.warning('Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(".save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        unblockLoad()
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        $(".save").text("Save").prop("disabled", false);
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
            _pop($('#network_id'));
            _zone($('#network_id'));
            _payableFee();
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
                url: "{{ route('pop_by_network') }}",
                data: info,
                success: function (response) {
                    //console.log(response)
                    if (response != 0) {
                        var json = JSON.parse(response);
                        $("#pop_id").empty();
                        var html = "";
                        var pop_id= "{{ $clients->pop_id }}";
                        $.each(json, function (key, value) {
                            html += "<option value='" + value.id + "' "+(pop_id==value.id ? 'selected':'')+">" + value.pop_name + "</option>";
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
                        var zone_id= "{{ $clients->zone_id }}";
                        var html = "";
                        $.each(json, function (key, value) {
                            html += "<option value='" + value.id + "' "+(zone_id==value.id ? 'selected':'')+">" + value.zone_name_en + "</option>";
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
                        var node_id= "{{ $clients->node_id }}";
                        var html = "";
                        $.each(json, function (key, value) {
                            html += "<option value='" + value.id + "' "+(node_id==value.id ? 'selected':'')+">" + value.node_name + "</option>";
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
                        var box_id= "{{ $clients->box_id }}";
                        $("#box_id").empty();
                        var html = "";
                        $.each(json, function (key, value) {
                            html += "<option value='" + value.id + "' "+(box_id==value.id ? 'selected':'')+">" + value.box_name + "</option>";
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

    </script>

@endsection