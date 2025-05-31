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
                                            <span class="d-block mb-1 font-medium-1">Total Due</span>
                                            <h1 class="text-white mb-0 total_due">0</h1>
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
                                            <span class="d-block mb-1 font-medium-1">Last Transaction</span>
                                            <h1 class="text-white mb-0 receive_amount">0</h1>
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
                        $(".total_due").html(json.total_due);
                        $(".receive_amount").html(json.receive_amount);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                    }
                });
            }, 2000);
        });
    </script>
@endsection