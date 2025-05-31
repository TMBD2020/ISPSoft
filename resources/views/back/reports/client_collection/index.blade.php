@extends('layouts.app')

@section('title', 'Client Collection')

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


                                        <div id="collapseB2" class="" aria-labelledby="headingBTwo">
                                            <div class="card-body" style="padding: 6px 10px;">
                                                <form  method="post" novalidate>
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
                                                                        <option value="{{ $row->auth_id }}">{{ $row->emp_name }}</option>
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





                                        <div class="card inner-card expense_show" style="display:none;">
                                            <div class="card-body" style="padding: 6px 10px;">
                                                <div class="pull-right" style="clear:both; overflow: hidden;">
                                                    <form id="OPform" action="{{ route("client-collection-pdf") }}" method="post" target="_blank">
                                                        @csrf
                                                        <input type="hidden" name="date_to" class="to_date" />
                                                        <input type="hidden" name="date_from" class="from_date" />
                                                        <input type="hidden" name="zone_id" class="zone_id" />
                                                        <input type="hidden" name="collected_by" class="collected_by" />
                                                        <input type="submit" class="btn grey btn-primary" name="operation" value="Download PDF">
                                                        <input type="submit" class="btn grey btn-success" name="operation" value="Print">
                                                    </form>
                                                </div>
                                                <div class="col-md-12 text-center" style="clear:both; overflow: hidden;">
                                                    <h2>Collection</h2>
                                                    <h6>For the period from <big class="date_from"></big> to <big class="date_to"></big></h6>
                                                    <h6 class="zoneName" style="display: none;">Zone: <span></span></h6>
                                                    <h6 class="receiverName" style="display: none;">Collected by: <span ></span></h6>
                                                </div>

                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Sl</th>
                                                            <th class="text-center">Date</th>
                                                            <th class="text-center selectedZone"> Zone</th>
                                                            <th>Client</th>
                                                            <th class="text-center">Discount</th>
                                                            <th class="text-center">Amount</th>
                                                            <th class="selectedReceive">Received By</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="collectionTable"></tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <th class="text-right totalCols" colspan="4">Total</th>
                                                        <th class="text-right total_discount"></th>
                                                        <th class="text-right total_collection"></th>
                                                        <th class=""></th>
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

        var block_ele = $('.card .card');
        $(document).ready(function () {

            //search();
            $(document).on('click', ".search", function () {
                search();
            });

        });

        function search(){

            blockLoad();
            if($("#date_from").val()!=""  && $("#date_to").val()!=""){
                $(".search").text("Searching...").prop("disabled", true);

                var colsp=4;
                if($("#zone_id").val()==0)
                {
                    $(".selectedZone").show();
                    $(".zoneName").hide();
                }else{
                    colsp=3;
                    $(".zoneName").show().find("span").html($("#zone_id :selected").text());
                    $(".selectedZone").hide();
                }
                if($("#collected_by").val()==0){
                    $(".selectedReceive").show();
                    $(".receiverName").hide();
                    $("tfoot th:last-child").show();
                }else{
                    colsp=4;
                    $(".receiverName").show().find("span").html($("#collected_by :selected").text());
                    $(".selectedReceive").hide();
                    $("tfoot th:last-child").hide();
                }
                $(".totalCols").attr("colspan",colsp);

                $.ajax({
                    type: "POST",
                    url: "{{ route('search_collection_report') }}",
                    data: {
                        _token      : "{{ csrf_token() }}" ,
                        date_from   :$("#date_from").val(),
                        date_to     :$("#date_to").val(),
                        collected_by:$("#collected_by").val(),
                        zone_id     :$("#zone_id").val()
                    },
                    success: function (response)
                    {
                        unblockLoad();

                        $(".search").text("Search").prop("disabled", false);
                        $(".expense_show").show();
                       var json = JSON.parse(response);
                       // console.log(response);
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
                                var discount = Number(value.discount_amount) + Number(value.permanent_discount);
                                total_discount+=discount;
                                html+='<tr>' +
                                        '<td class="text-center">'+(key+1)+'</td>' +
                                        '<td class="text-center">'+dateFormat(value.receive_date)+'</td>' ;
                                if($("#zone_id").val()==0)
                                {
                                    html +='<td class="text-left">' + value.zone_name_en + '</td>';
                                }
                                html+=
                                        '<td>'+value.client_id+'-'+value.client_name+'</td>' +
                                        '<td class="text-right">'+decimal(discount)+'</td>' +
                                        '<td class="text-right">'+decimal(value.receive_amount)+'</td>' ;
                                if($("#collected_by").val()==0){
                                    html+='<td>'+ (value.receiver_id!=null ? value.receiver_id+'-'+value.receive_name:"") +'</td>' ;
                                }
                                html+='</tr>';
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
                        unblockLoad();
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