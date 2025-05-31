@extends('layouts.app')

@section('title', 'Client Custom Edit')

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
                    <h3 class="content-header-title"><span id="tabOption">@yield("title")</span></h3>
                </div>
                <div class="content-header-right col-md-8 col-12">

                </div>
            </div>
            <div class="content-body"><!-- Zero configuration table -->
                <section id="configuration">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content collapse show">
                                    <div class="card-body card-dashboard">

                                        <div class="tab-content">
                                            <div class="card">
                                                <div class="card-body">


                                                    <form method="post" action="{{ route("custom-edit") }}">
                                                        {{ csrf_field() }}

                                                        <input type="hidden" id="action" name="action">
                                                        <input type="hidden" id="id" name="id">

                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="client_type" class="control-label">Client
                                                                    Type <span class="text-danger">*</span></label>
                                                                <select class="form-control select2" name="client_type"
                                                                        id="client_type" required>
                                                                    <option value="1">ISP Clients</option>
                                                                    <option value="2">CATV Clients</option>
                                                                </select>
                                                            </div>
                                                            <div class="col">
                                                                <label for="zone_id" class="control-label">Zone
                                                                    Name</label>
                                                                <select class="form-control select2" name="zone_id"
                                                                        id="zone_id">
                                                                    <option value="">Select None</option>
                                                                    @foreach($zones as $row)
                                                                        <option value="{{ $row->id }}" @if($zone_id == $row->id) selected @endif>{{ $row->zone_name_en }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col">
                                                                <label for="client_id" class="control-label">Client
                                                                    ID</label>
                                                                <input placeholder="type with comma" type="text"
                                                                       name="client_id" class="form-control"
                                                                       value="{{ $client_id }}">
                                                            </div>
                                                            <div class="col">

                                                                <button type="submit" class="btn btn-primary save"
                                                                        style="margin-top: 26px !important;">Search
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </form>

                                                </div>

                                                @if(Session::has('msg'))
                                                    <p class="alert alert-info">{{ Session::get('msg') }}</p>
                                                    @endif
                                                @if($clients)
                                                    <form action="{{ route("custom-client-edit-save") }}" method="post">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" value="{{ $client_type }}" name="client_type">
                                                        <input type="hidden" value="{{ $zone_id }}" name="zone_id">
                                                        <input type="hidden" value="{{ $client_id }}" name="client_id">
                                                        <div class="card-body" style="padding: 0!important;">
                                                            <div style="height: 350px; overflow-y: scroll; overflow-x: hidden">
                                                                <table class="customTable" border="1" cellpadding="5">
                                                                    <thead>
                                                                    <tr>
                                                                        <th class="text-center">SL</th>
                                                                        <th>Client</th>
                                                                        <th class="text-center">Mobile</th>
                                                                        <th class="text-center">Billing</th>
                                                                        <th class="text-center">Technician</th>
                                                                        <th class="text-center">Lat,Long</th>
                                                                        <th class="text-center">Interface</th>
                                                                        <th class="text-center">MAC</th>
                                                                        <th class="text-center">Fiber Length,Power</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($clients as $key=>$client)
                                                                        <tr>
                                                                            <td class="text-center">{{ $key+1 }}
                                                                                <input type="hidden"
                                                                                       value="{{ $client->id }}"
                                                                                       name="id[]"></td>
                                                                            <td style="font-size:12px;">{{ $client->client_id }}
                                                                                <br> {{ $client->client_name }}</td>
                                                                            <td><input type="text" name="cell_no[]"
                                                                                       class="text-center" size="12"
                                                                                       value="{{ $client->cell_no }}"></td>
                                                                            <td>
                                                                                <select name="billing_responsible[]" style="width: 80px">
                                                                                    @foreach($employees as $emp)
                                                                                        <option value="{{ $emp->auth_id }}" @if($client->billing_responsible == $emp->auth_id) selected @endif>{{ $emp->emp_name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <select name="technician_id[]" style="width: 80px;">
                                                                                    @foreach($employees as $emp)
                                                                                        <option value="{{ $emp->auth_id }}" @if($client->technician_id == $emp->auth_id) selected @endif>{{ $emp->emp_name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td><input type="text" class="text-center"
                                                                                       name="lat_long[]"
                                                                                       value="{{ $client->lat_long }}"></td>
                                                                            <td><input type="text" class="text-center"
                                                                                       name="olt_interface[]" size="10"
                                                                                       value="{{ $client->olt_interface }}">
                                                                            </td>
                                                                            <td><input type="text" class="text-center"
                                                                                       name="mac_address[]" size="10"
                                                                                       value="{{ $client->mac_address }}">
                                                                            </td>
                                                                            <td class="text-center">
                                                                                L <input type="text" name="required_cable[]"
                                                                                         value="{{ $client->required_cable }}"
                                                                                         size="10"><br>
                                                                                P <input type="text" name="receive_power[]"
                                                                                         value="{{ $client->receive_power }}"
                                                                                         size="10">
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>

                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-center">
                                                            <button type="submit" style="    width: 30%;" onclick="return confirm('Are you sure to update this?');"
                                                                    class="btn btn-info">Save
                                                            </button>
                                                        </div>
                                                    </form>
                                                @endif
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
        .customTable thead tr {
            position: sticky;
            top: -1px;
            z-index: 1;
            background-color: #EBEBEB
        }
    </style>
@endsection
