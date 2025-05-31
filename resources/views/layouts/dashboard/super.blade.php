@extends('layouts.app')
@section("title","Dashboard")

@section("dashboard_script")
    <link rel="stylesheet" type="text/css" href="app-assets/fonts/simple-line-icons/style.min.css">
@endsection
@section('content')
        <!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>

            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    <h3 class="content-header-title">ISP Summery</h3>
                </div>
            </div>
            <div class="content-body"><!-- Revenue, Hit Rate & Deals -->

                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-xs-6">
                        <div class="card bg-gradient-directional-warning">
                            <div class="card-content" onclick="getMemberOfStatus('active')" style="cursor:pointer;">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-white text-left align-self-bottom ">
                                            <span class="d-block mb-1 font-medium-1">Active Client</span>
                                            <h1 class="text-white mb-0 isp_active_client">0</h1>
                                        </div>
                                        <div class="align-self-top">
                                            <i class="icon-users icon-opacity text-white font-large-2 float-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-xs-6">
                        <div class="card bg-gradient-directional-success">
                            <div class="card-content" onclick="getMemberOfStatus('inactive')" style="cursor:pointer;">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-white text-left align-self-bottom ">
                                            <span class="d-block mb-1 font-medium-1">Inactive Client</span>
                                            <h1 class="text-white mb-0 isp_inactive_client">0</h1>
                                        </div>
                                        <div class="align-self-top">
                                            <i class="icon-users icon-opacity text-white font-large-2 float-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="card bg-gradient-directional-danger">
                            <div class="card-content" onclick="getMemberOfStatus('total')" style="cursor:pointer;" >
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-white text-left align-self-bottom ">
                                            <span class="d-block mb-1 font-medium-1">Total Client</span>
                                            <h1 class="text-white mb-0 isp_total_client">0</h1>
                                        </div>
                                        <div class="align-self-top">
                                            <i class="icon-users icon-opacity text-white font-large-2 float-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div >
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="card bg-gradient-directional-info">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-white text-left align-self-bottom ">
                                            <span class="d-block mb-1 font-medium-1">New Client</span>
                                            <h1 class="text-white mb-0 isp_new_client">0</h1>
                                        </div>
                                        <div class="align-self-top">
                                            <i class="icon-users icon-opacity text-white font-large-2 float-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="card bg-gradient-x-purple-blue">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="align-self-top">
                                            <i class="icon-tag icon-opacity text-white font-large-2 float-left"></i>
                                        </div>
                                        <div class="media-body text-white text-right align-self-bottom ">
                                            <span class="d-block mb-1 font-medium-1">Open Ticket</span>
                                            <h1 class="text-white mb-0 isp_open_tickets">0</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="card bg-gradient-x-purple-red">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="align-self-top">
                                            <i class="icon-wallet icon-bar-chart text-white font-large-2 float-left"></i>
                                        </div>
                                        <div class="media-body text-white text-right align-self-bottom ">
                                            <span class="d-block mb-1 font-medium-1">Today Expense</span>
                                            <h1 class="text-white mb-0 isp_expenses">0</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="card bg-gradient-x-blue-green">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="align-self-top">
                                            <i class="icon-wallet icon-opacity text-white font-large-2 float-left"></i>
                                        </div>
                                        <div class="media-body text-white text-right align-self-bottom ">
                                            <span class="d-block mb-1 font-medium-1">Previous Collection</span>
                                            <h1 class="text-white mb-0 previous_isp_bills">0</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="card bg-gradient-x-orange-yellow">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="align-self-top">
                                            <i class="icon-wallet icon-opacity text-white font-large-2 float-left"></i>
                                        </div>
                                        <div class="media-body text-white text-right align-self-bottom ">
                                            <span class="d-block mb-1 font-medium-1">Current Collection</span>
                                            <h1 class="text-white mb-0 present_isp_bills">0</h1>
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
<!-- END: Content-->
@endsection