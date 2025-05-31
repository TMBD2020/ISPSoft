@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<style>
    h4 span {
        padding-right: 10px;
    }
    table th,table td{
        padding: 0!important;
        vertical-align: middle !important;
    }
    table th{
        text-align: center !important;
    }
</style>
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title" id="tabOption">All Clients</h3>
            </div>
            <div class="content-header-right col-md-8 col-12">

                <ul class="nav nav-tabs float-md-right">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route("isp-clients") }}" >All Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route("isp-queue") }}" >Add Queue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route("isp-pppoe") }}" >Add PPPoE</a>
                    </li>
                    {{--<li class="nav-item">--}}
                        {{--<a class="nav-link" href="#">Import/Export</a>--}}
                    {{--</li>--}}
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

                                    @if(session()->has('msg'))
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        {{ session()->get('msg') }}

                                    </div>
                                @endif

                                    @if(Auth::user()->user_type=='admin')
                                        <div class="card inner-card" style="margin:0;">
                                            <div class="card-body" style="padding: 6px 10px;">
                                                <div class="row" style="margin:0;">
                                                    <div class="col-md-3">
                                                        <label for="search_company_id"
                                                               class="control-label">Company/Reseller
                                                        </label>

                                                        <select class="form-control select2" id="search_company_id" required>
                                                            <option value="{{ auth()->user()->company_id }}"> {{ auth()->user()->name }} (O)</option>
                                                            @foreach($companies as $row)
                                                                <option value="{{ $row->auth_id }}">

                                                                    {{ $row->reseller_id }} ::

                                                                    {{ $row->reseller_name }}
                                                                    (R) </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="search_company_id" class="control-label">Status</label>

                                                        <select class="form-control select2" id="status" required>
                                                            <option value="">All</option>
                                                            <option value="active">Active</option>
                                                            <option value="inactive">Inactive</option>
                                                            <option value="locked">Locked</option>
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
                                        <input type="hidden" value="{{ Settings::company_id() }}" id="search_company_id">
                                    @endif

                                    <div class="text-center client_summery">
                                        <h4>
                                            <span>Total: <i
                                                        class="text-primary total">{{ $total_client }}</i></span>
                                            <span>Active: <i
                                                        class="text-success actived">{{ $active_client }}</i></span>
                                            <span>Inactive: <i
                                                        class="text-danger inactived">{{ $inactive_client }}</i></span>
                                            <span>Lock: <i
                                                        class="text-warning locked">{{ $locked_client }}</i></span>
                                            <a href="{{route('printpdf')}}" class="btn btn-primary btn-sm pull-right">Download PDF</a>
                                        </h4>

                                    </div>


                                    <table id="datalist"
                                           class="table table-striped table-bordered table-hovered"
                                           style="width: 100%; position: static !important">
                                        <thead>
                                        <tr>
                                            <th >SL</th>
                                            <th >ID/Name/Cell</th>
                                            <th >Zone/Address</th>
                                            <th >Package</th>
                                            <th >Joining</th>
                                            <th >Payment<br>Deadline</th>
                                            <th >Status</th>
                                            <th >Action</th>
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
<!-- END: Content-->

<style>
    .dt-scroll-body{
        position: static !important;
    }
</style>

@include("back.clients.isp.widget")
@endsection

