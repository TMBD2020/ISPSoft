@extends('layouts.app')
@section("title","Dashboard")

@section("dashboard_script")
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/charts/chartist.css")}}">

    <link rel="stylesheet" type="text/css" href="app-assets/fonts/simple-line-icons/style.min.css">
<!-- BEGIN: Page Vendor JS-->
    <script src="app-assets/vendors/js/charts/chart.min.js" type="text/javascript"></script>
    <script src="app-assets/js/scripts/charts/chartjs/bar/column.js" type="text/javascript"></script>
<!-- END: Page Vendor JS-->


@endsection
@section('content')
        <!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>

           @if(Permission::sub_module(48,'read_access'))
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    <h3 class="content-header-title">ISP Summery</h3>
                    <div id="google_translate_element"></div>
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
            @endif
           @if(Permission::sub_module(49,'read_access'))

            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    <div class="col-12  mb-1">
                        <h3 class="content-header-title" style="color:#464855 !important;">CATV Summery</h3>
                    </div>
                </div>
            </div>

            <div class="content-body"><!-- Revenue, Hit Rate & Deals -->

                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-xs-6">
                        <div class="card bg-gradient-directional-warning">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-white text-left align-self-bottom ">
                                            <span class="d-block mb-1 font-medium-1">Active Client</span>
                                            <h1 class="text-white mb-0 catv_active_client">0</h1>
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
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-white text-left align-self-bottom ">
                                            <span class="d-block mb-1 font-medium-1">Inactive Client</span>
                                            <h1 class="text-white mb-0 catv_inactive_client">0</h1>
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
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-white text-left align-self-bottom ">
                                            <span class="d-block mb-1 font-medium-1">Total Client</span>
                                            <h1 class="text-white mb-0 catv_total_client">0</h1>
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
                        <div class="card bg-gradient-directional-info">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-white text-left align-self-bottom ">
                                            <span class="d-block mb-1 font-medium-1">New Client</span>
                                            <h1 class="text-white mb-0 catv_new_client">0</h1>
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
                                            <h1 class="text-white mb-0 catv_open_tickets">0</h1>
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
                                            <h1 class="text-white mb-0 catv_expenses">0</h1>
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
                                            <span class="d-block mb-1 font-medium-1">Bill Collection</span>
                                            <h1 class="text-white mb-0 catv_bills">0</h1>
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
                                            <span class="d-block mb-1 font-medium-1">Reseller Collection</span>
                                            <h1 class="text-white mb-0">0</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column Stacked Chart -->
                {{--<div class="row">--}}
                    {{--<div class="col-6">--}}
                        {{--<div class="card">--}}
                            {{--<div class="card-header">--}}
                                {{--<h4 class="card-title">Income Expense</h4>--}}
                                {{--<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>--}}
                                {{--<div class="heading-elements">--}}
                                    {{--<ul class="list-inline mb-0">--}}
                                        {{--<li><a data-action="collapse"><i class="ft-minus"></i></a></li>--}}
                                        {{--<li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>--}}
                                        {{--<li><a data-action="expand"><i class="ft-maximize"></i></a></li>--}}
                                        {{--<li><a data-action="close"><i class="ft-x"></i></a></li>--}}
                                    {{--</ul>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="card-content collapse show">--}}
                                {{--<div class="card-body">--}}
                                    {{--<div class="height-400">--}}
                                        {{--<canvas id="column-chart"></canvas>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

            </div>
            @endif


            @if(Permission::sub_module(48,'read_access') ==false or Permission::sub_module(49,'read_access') == false)
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    {{--<h3 class="content-header-title">Summery</h3>--}}
                </div>
            </div>
            <div class="content-body"><!-- Revenue, Hit Rate & Deals -->

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-xs-12">
                        <div class="card bg-gradient-directional-success">
                            <div class="card-content" style="cursor:pointer;">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-white text-center align-self-bottom ">
                                            <span class="d-block mb-1 font-medium-4">Welcome To</span>
                                            <h3 class="text-white mb-0"> ISP Billing Automation System</h3>
                                            <h6 class="text-white mt-1"> (Please use the right navigation for your operations)</h6>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
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
            @endif
    </div>
</div>
<!-- END: Content-->
@endsection
@section("page_script")
    <script type="text/javascript">

        $(document).ready(function () {

            setTimeout(function(){
                $.ajax({
                    type: "POST",
                    url: "{{ route('dashboard_data') }}",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        var json = JSON.parse(response);
//                        console.log(json)
                        $(".isp_active_client").html(json.isp_active_client);
                        $(".isp_inactive_client").html(json.isp_inactive_client);
                        $(".isp_total_client").html(json.isp_total_client);
                        $(".isp_new_client").html(json.isp_new_client);
                        $(".isp_open_tickets").html(json.catv_open_tickets);
                        $(".isp_expenses").html(json.catv_expenses);
                        $(".previous_isp_bills").html(json.previous_isp_bills == null?0:json.previous_isp_bills);
                        $(".present_isp_bills").html(json.present_isp_bills == null?0:json.present_isp_bills);

                        $(".catv_total_client").html(json.catv_total_client);
                        $(".catv_active_client").html(json.catv_active_client);
                        $(".catv_inactive_client").html(json.catv_inactive_client);
                        $(".catv_new_client").html(json.catv_new_client);
                        $(".catv_open_tickets").html(json.catv_open_tickets);
                        $(".catv_expenses").html(json.catv_expenses);
                        $(".catv_bills").html(json.catv_bills == null?0:json.catv_bills);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                    }
                });
            }, 2000);





        });
        function getMemberOfStatus(status){
            localStorage.setItem("isp_client_status",status);
            window.location.href="/isp-clients";
        }
    </script>
@endsection