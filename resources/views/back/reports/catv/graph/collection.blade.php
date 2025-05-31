@extends('layouts.app')

@section('title', 'CATV Collection')

@section('content')


    <style>
        h4 span{
            padding-right: 10px;
        }
        .table td,.table th, .table{
            color: #000;
        }
        .table th{
            font-weight: bold;
        }
        .form-group{
            margin:0 !important;
        }

    </style>
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    <h3 class="content-header-title">@yield("title")</h3>
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

                                        <div class="card inner-card">
                                            <div class="card-body" style="padding: 6px 10px;">
                                                <form id="IncomeSearchForm" method="post" novalidate>
                                                    {{csrf_field()}}
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Zone</label>
                                                                <select class="form-control select2" name="zone_id" id="zone_id">
                                                                    <option value="0">All Zone</option>
                                                                    @foreach($zones as $row)
                                                                        <option value="{{ $row->id }}">{{ $row->zone_name_en }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">

                                                                <label>Employee</label>
                                                                <select class="form-control select2" name="collected_by" id="collected_by">
                                                                    <option value="0">All Employee</option>
                                                                    @foreach($employees as $row)
                                                                        <option value="{{ $row->id }}">{{ $row->emp_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>From</label>
                                                                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $date_from }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>To</label>
                                                                <input type="date" name="date_to" id="date_to" class="form-control " value="{{ $date_to }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group" >
                                                                <button type="button" style="margin-top:25px;"  class="btn btn-primary mb-0 search">Search</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>


                                        <div class="card inner-card expense_show" style="display: none;">
                                            <div class="card-body" style="padding: 6px 10px;">
                                                <script src="https://code.highcharts.com/highcharts.js"></script>
                                                <script src="https://code.highcharts.com/modules/variwide.js"></script>
                                                <script src="https://code.highcharts.com/modules/exporting.js"></script>
                                                <script src="https://code.highcharts.com/modules/export-data.js"></script>
                                                <script src="https://code.highcharts.com/modules/accessibility.js"></script>

                                                <figure class="highcharts-figure">
                                                    <div id="mygraph"></div>
                                                </figure>

                                                <script>
                                                    Highcharts.chart('mygraph', {

                                                        chart: {
                                                            type: 'variwide'
                                                        },

                                                        title: {
                                                            text: 'Collection of 2020'
                                                        },

//                                                        subtitle: {
//                                                            text: 'Source: <a href="http://ec.europa.eu/eurostat/web/' +
//                                                            'labour-market/labour-costs/main-tables">eurostat</a>'
//                                                        },

                                                        xAxis: {
                                                            type: 'category'
                                                        },

                                                        caption: {
                                                            text: 'Collection graph of 2020'
                                                        },

                                                        legend: {
                                                            enabled: false
                                                        },

                                                        series: [{
                                                            name: 'Labor Costs',
                                                            data: [
                                                                ['January', 50, 335504],
                                                                ['February', 42, 277339],
                                                                ['March', 39.2, 421611],
                                                                ['April', 38, 462057],
                                                                ['May', 35.6, 2228857],
                                                                ['June', 34.3, 702641],
                                                                ['July', 33.2, 215615],
                                                                ['August', 33.0, 3144050],
                                                                ['September', 32.7, 349344],
                                                                ['October', 30.4, 275567],
                                                                ['November', 27.8, 1672438],
                                                                ['December', 26.7, 2366911]
                                                            ],
                                                            dataLabels: {
                                                                enabled: true,
                                                                format: '€{point.y:.0f}'
                                                            },
                                                            tooltip: {
                                                                pointFormat: '<b>{point.y}</b>'
                                                            },
                                                            colorByPoint: true
                                                        }]

                                                    });

                                                </script>
                                                <style>
                                                    #container {
                                                        height: 500px;
                                                    }

                                                    .highcharts-figure, .highcharts-data-table table {
                                                        min-width: 320px;
                                                        max-width: 800px;
                                                        margin: 1em auto;
                                                    }

                                                    .highcharts-data-table table {
                                                        font-family: Verdana, sans-serif;
                                                        border-collapse: collapse;
                                                        border: 1px solid #EBEBEB;
                                                        margin: 10px auto;
                                                        text-align: center;
                                                        width: 100%;
                                                        max-width: 500px;
                                                    }
                                                    .highcharts-data-table caption {
                                                        padding: 1em 0;
                                                        font-size: 1.2em;
                                                        color: #555;
                                                    }
                                                    .highcharts-data-table th {
                                                        font-weight: 600;
                                                        padding: 0.5em;
                                                    }
                                                    .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
                                                        padding: 0.5em;
                                                    }
                                                    .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
                                                        background: #f8f8f8;
                                                    }
                                                    .highcharts-data-table tr:hover {
                                                        background: #f1f7ff;
                                                    }

                                                </style>

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

@endsection
@section("page_script")

    <script type="text/javascript">
        $(document).ready(function () {

            search();
            $(document).on('click', ".search", function () {
                search();
            });

        });

        function search(){
            if($("#date_from").val()!=""  && $("#date_to").val()!=""){
                $(".search").text("Searching...").prop("disabled", true);

                $.ajax({
                    type: "POST",
                    url: "{{ url('search_catv_collection_rpt') }}",
                    data: {
                        _token      : "{{ csrf_token() }}" ,
                        date_from   :$("#date_from").val(),
                        date_to     :$("#date_to").val(),
                        collected_by:$("#collected_by").val(),
                        zone_id     :$("#zone_id").val()
                    },
                    success: function (response)
                    {
                        $(".search").text("Search").prop("disabled", false);
                        $(".expense_show").show();
                       var json = JSON.parse(response);
                        console.log(response);
                        var collection = json.collection;
                        var dates = json.dates;
                        $(".date_from").html(dates.date_from);
                        $(".date_to").html(dates.date_to);
                        $(".from_date").val(dates.date_from);
                        $(".to_date").val(dates.date_to);
                        $(".collected_by").val($("#collected_by").val());
                        $(".zone_id").val($("#zone_id").val());
                        if(collection!=0){
                            $(".collectionTable").empty();
                            var total_collection=0;
                            var total_discount=0;
                            var html="";
                            $.each(collection, function(key,value){
                                total_collection+=Number(value.receive_amount);
                                var discount = Number(value.discount_amount);
                                total_discount+=discount;
                                html+='<tr>' +
                                        '<td class="text-center">'+(key+1)+'</td>' +
                                        '<td class="text-center">'+dateFormat(value.receive_date)+'</td>' +
                                        '<td class="text-left">'+value.zone_name_en+'</td>' +
                                        '<td>'+value.client_id+'-'+value.client_name+'</td>' +
                                        '<td class="text-right">'+decimal(discount)+'</td>' +
                                        '<td class="text-right">'+decimal(value.receive_amount)+'</td>' +
                                        '<td>'+ (value.receiver_id!=null ? value.receiver_id+'-'+value.receive_name:"") +'</td>' +
                                        '</tr>';
                            });
                            $(".collectionTable").html(html);
                            $(".total_discount").html(decimal(total_discount));
                            $(".total_collection").html(decimal(total_collection));

                    }else{
                            $(".collectionTable").empty();
                            $(".expense_show").hide();
                        }
                    },
                    error: function (request, status, error) {
                        $(".expense_show").hide();
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(".search").text("Search").prop("disabled", false);
                    }
                });
            }

        }

        function decimal(num){
            var value = Number(num)*100/100;
            return value.toFixed(2);
        }
        function dateFormat(date){
            var value = date.split("-");
            value = value[2]+"/"+value[1]+"/"+value[0];
            return value;
        }
    </script>

    <style>
        .card.inner-card {
            box-shadow: none !important;
            border: 1px solid  rgba(222, 223, 241,0.3)  !important;
        }
        input[readonly]{
            background: #f5f4f4;
            border: 1px solid #bfb6b6;
        }
        form .row{
            margin-bottom: 5px;
        }
    </style>
@endsection