@extends('layouts.app')

@section('title', 'CATV Collection Summery')

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
                                                                <button type="button" style="margin-top:25px;" class="btn btn-primary mb-0 search">Search</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>


                                        <div class="card inner-card expense_show" style="display: none;">
                                            <div class="card-body" style="padding: 6px 10px;">
                                                <div class="pull-right" style="clear:both; overflow: hidden;">
                                                    <form id="OPform" action="{{ route("catv-collection-summery-pdf") }}" method="post" target="_blank">
                                                        @csrf
                                                        <input type="hidden" name="date_to" class="to_date" />
                                                        <input type="hidden" name="date_from" class="from_date" />
                                                        <input type="hidden" name="zone_id" class="zone_id" />
                                                        <input type="submit" class="btn grey btn-primary" name="operation" value="Download PDF">
                                                        <input type="submit" class="btn grey btn-success" name="operation" value="Print">
                                                    </form>
                                                </div>
                                                <div class="col-md-12 text-center" style="clear:both; overflow: hidden;">
                                                    <h2>Collection</h2>
                                                    <h6>For the period from <big class="date_from"></big> to <big class="date_to"></big></h6>
                                                </div>

                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5%" class="text-center">Sl</th>
                                                            <th style="width: 50%" class="text-center">Zone</th>
                                                            <th style="width: 15%" class="text-center">Total Collection</th>
                                                            <th style="width: 15%" class="text-center">Total OTC</th>
                                                            <th style="width: 15%" class="text-center">Net Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="collectionTable"></tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <th class="text-right" colspan="2">Total</th>
                                                        <th class="text-right total_collection"></th>
                                                        <th class="text-right total_conn_fee"></th>
                                                        <th class="text-right net_total"></th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
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
                    url: "{{ route('search_catv_collection_summery') }}",
                    data: {
                        _token      : "{{ csrf_token() }}" ,
                        date_from   :$("#date_from").val(),
                        date_to     :$("#date_to").val(),
                        zone_id     :$("#zone_id").val()
                    },
                    success: function (response)
                    {
                        $(".search").text("Search").prop("disabled", false);
                        $(".expense_show").show();
                       var json = JSON.parse(response);
                        console.log(response);
                        var collection = json.collection;
                        var zones = json.zones;
                        var dates = json.dates;
                        $(".date_from").html(dates.date_from);
                        $(".date_to").html(dates.date_to);
                        $(".from_date").val(dates.date_from);
                        $(".to_date").val(dates.date_to);
                        $(".zone_id").val($("#zone_id").val());
                        if(collection!=0){
                            $(".collectionTable").empty();
                            var total_collection=0;
                            var total_conn_fee=0;
                            var net_total=0;
                            var html="";
                            $.each(collection, function(key,value){
                                total_collection+=Number(value.collections);
                                total_conn_fee+=Number(value.conn_fee);
                                var total=Number(value.collections)+Number(value.conn_fee);
                                net_total+=total;
                                var zone_name="";
                                $.each(zones, function(key2,zone){
                                    if(zone.id==value.zone_id){
                                        zone_name=zone.zone_name_en;
                                    }
                                });

                                html+='<tr>' +
                                        '<td class="text-center">'+(key+1)+'</td>' +
                                        '<td class="text-left">'+zone_name+'</td>' +
                                        '<td class="text-right">'+decimal(value.collections)+'</td>' +
                                        '<td class="text-right">'+decimal(value.conn_fee)+'</td>' +
                                        '<td class="text-right">'+decimal(total)+'</td>' +
                                        '</tr>';
                            });
                            $(".collectionTable").html(html);
                            $(".total_collection").html(decimal(total_collection));
                            $(".total_conn_fee").html(decimal(total_conn_fee));
                            $(".net_total").html(decimal(net_total));

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