@extends('layouts.app')
@section("title","Dashboard")

@section("dashboard_script")

    <link rel="stylesheet" type="text/css" href="app-assets/fonts/simple-line-icons/style.min.css">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/pareto.js"></script>

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
                </div>
            </div>
            <div class="content-body"><!-- Revenue, Hit Rate & Deals -->

                <div class="row">
                    <div class="col-md-9">
                       <div class="row">
                           <div class="col-xl-3 col-lg-6 col-xs-6">
                               <div class="card bg-gradient-directional-success">
                                   <div class="card-content" onclick="getMemberOfStatus('active')" style="cursor:pointer;">
                                       <div class="card-body">
                                           <div class="media d-flex">
                                               <div class="media-body text-white text-left align-self-bottom ">
                                                   <span class="d-block mb-1 font-medium-1">Active Client</span>
                                                   <h1 class="text-white mb-0 isp_active_client">0</h1>
                                               </div>
                                               <div class="align-self-top">
                                                   <i class="icon-users icon-opacity text-white font-large-1 float-right"></i>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <div class="col-xl-3 col-lg-6 col-xs-6">
                               <div class="card   bg-gradient-directional-danger">
                                   <div class="card-content" onclick="getMemberOfStatus('inactive')" style="cursor:pointer;">
                                       <div class="card-body">
                                           <div class="media d-flex">
                                               <div class="media-body text-white text-left align-self-bottom ">
                                                   <span class="d-block mb-1 font-medium-1">Inactive Client</span>
                                                   <h1 class="text-white mb-0 isp_inactive_client">0</h1>
                                               </div>
                                               <div class="align-self-top">
                                                   <i class="icon-users icon-opacity text-white font-large-1 float-right"></i>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>

                           <div class="col-xl-3 col-lg-6 col-sm-6">
                               <div class="card   bg-gradient-directional-success">
                                   <div class="card-content" onclick="getMemberOfStatus('total')" style="cursor:pointer;" >
                                       <div class="card-body">
                                           <div class="media d-flex">
                                               <div class="media-body text-white text-left align-self-bottom ">
                                                   <span class="d-block mb-1 font-medium-1">Total Client</span>
                                                   <h1 class="text-white mb-0 isp_total_client">0</h1>
                                               </div>
                                               <div class="align-self-top">
                                                   <i class="icon-users icon-opacity text-white font-large-1 float-right"></i>
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
                                                   <i class="icon-users icon-opacity text-white font-large-1 float-right"></i>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>

                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="row">
                           <div class="col-xl-12 col-lg-12 col-sm-12">
                               <div class="card ">
                                   <div class="card-content">
                                       <div class="card-body">
                                           <div id="piechartclient"></div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                    </div>
                    <div class="col-md-6">
                       <div class="row">
                           <div class="col-xl-12 col-lg-12 col-sm-12">
                               <div class="card ">
                                   <div class="card-content">
                                       <div class="card-body">
                                           <div id="ispCollection"></div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                    </div>
                 </div>

                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                           
                            <div class="col-xl-3 col-lg-6 col-sm-6">
                                <div class="card bg-gradient-x-purple-blue">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="">
                                                <h5 class="text-bold-400 text-white  mb-2">Tickets <i class="icon-tag float-right"></i></h5>
                                            </div>
                                            <h6 class="text-white">Open <span class="isp_open_tickets text-black float-right"></span></h6>
                                            <h6 class="text-white">Close <span class="isp_close_tickets text-black float-right"></span></h6>
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
                                                    <i class="icon-wallet icon-bar-chart text-white font-large-1 float-left"></i>
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
                                    <div class="">
                                       <h5 class="text-bold-400 text-white  mb-2">Client Collection <i class="icon-wallet float-right"></i></h5>
                                    </div>
                                         <h6 class="text-white">Previous <span class="previous_isp_bills text-black float-right"></span></h6>
                                            <h6 class="text-white">Current <span class="present_isp_bills text-black float-right"></span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-sm-6">
                                <div class="card bg-gradient-x-orange-yellow">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="">
                                                <h5 class="text-bold-400 text-white  mb-2">Reseller Collection <i class="icon-wallet float-right"></i></h5>
                                            </div>
                                            <h6 class="text-white">Previous <span class="previous_isp_res_bills text-black float-right">0</span></h6>
                                            <h6 class="text-white">Current <span class="present_isp_res_bills text-black float-right">0</span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-3"></div>
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
                        <div class="card bg-gradient-directional-success">
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
                        <div class="card bg-gradient-directional-danger">
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
                        <div class="card bg-gradient-directional-success">
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
    <style>
       .row .card{
            min-height: 132px;
        }
       .row .card .card-body{
           padding-bottom: 5px;
        }
    </style>
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
                        $(".present_isp_bills").html(json.isp_present_bills == null?0:json.isp_present_bills);

                        $(".catv_total_client").html(json.catv_total_client);
                        $(".catv_active_client").html(json.catv_active_client);
                        $(".catv_inactive_client").html(json.catv_inactive_client);
                        $(".catv_new_client").html(json.catv_new_client);
                        $(".catv_open_tickets").html(json.catv_open_tickets);
                        $(".catv_expenses").html(json.catv_expenses);
                        $(".catv_bills").html(json.catv_bills == null?0:json.catv_bills);
                        ispClientGraph(json);
                        ispCollection(json);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                    }
                });
            }, 2000);





        });

        function ispCollection(json){
            Highcharts.chart('ispCollection', {
                chart: {
                    renderTo: 'ispCollection',
                    type: 'column'
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: 'ISP Collection'
                },
                tooltip: {
                    shared: true
                },
                xAxis: {
                    categories: [
                        'Client Previous',
                        'Client Current',
                        'Reseller Previous',
                        'Reseller Current',
                    ],
                    crosshair: true
                },
                yAxis: [{
                    title: {
                        text: ''
                    }
                }, {
                    title: {
                        text: ''
                    },
                    minPadding: 0,
                    maxPadding: 0,
                    max: 100,
                    min: 0,
                    opposite: true,
                    labels: {
                        format: "{value}%"
                    }
                }],
                series: [{
                    type: 'pareto',
                    name: 'Pareto',
                    yAxis: 1,
                    zIndex: 10,
                    baseSeries: 1,
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: '%'
                    }
                }, {
                    name: 'Collection',
                    type: 'column',
                    zIndex: 2,
                    data: [json.previous_isp_bills, json.isp_present_bills, 0, 0]
                }]
            });
        }
       function ispClientGraph(json){

            Highcharts.chart('piechartclient', {
                chart: {
                    renderTo: 'chart',
                    margin: 0,
                    defaultSeriesType: 'areaspline',
                    events: {
                        load: function(event) {
                           // console.log(event)
                            event.target.reflow();
                        }
                    },
                    //width:"500",
                    height:200,
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                credits: {
                    enabled: false
                },
                title: {text:"ISP Clients", align: 'left',},
                tooltip: {
                    headerFormat: '',
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> {point.name}: <b>{point.y}</b>'
                },
                exporting: {
                    enabled: false
                },

                plotOptions: {

                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
//                                        align: 'center',
                            //  enabled: false,
                            //  connectorWidth: 0,
                            distance: 10
                        }
                    }
                },
                series: [{
                    minPointSize: 10,
                    innerSize: '20%',
                    zMin: 0,
                    name: 'Clients',

                    data: [{
                        name: 'New',
                        y: json.isp_new_client,
                        color: "#1e9fc4"
                    }, {
                        name: 'Active',
                        y: json.isp_active_client,
                        color: "#63d457"
                    }, {
                        name: 'Inactive',
                        y: json.isp_inactive_client,
                        color: "#f95058"
                    }, {
                        name: 'Total',
                        y: json.isp_total_client,
                        color: "#9fe797"
                    }]
                }]
            });
        }
        function getMemberOfStatus(status){
            localStorage.setItem("isp_client_status",status);
            window.location.href="/isp-clients";
        }
    </script>
@endsection