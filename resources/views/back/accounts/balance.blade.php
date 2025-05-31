@extends('layouts.app')

@section('title', 'Account Balance')

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
                                                                <label>Account Name</label>
                                                                <select class="form-control select2" name="auth_id" id="auth_id">
                                                                    <option value="">All Employee</option>
                                                                    @foreach($employees as $row)
                                                                        @if($row->emp_name)
                                                                            <option value="{{ $row->auth_id }}">{{ $row->emp_id }}-{{ $row->emp_name }}</option>
                                                                        @else
                                                                            <option value="{{ $row->admin_id }}">{{ $row->email }}-{{ $row->name }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
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

                                                <div class="col-md-12 text-center" style="clear:both; overflow: hidden;">
                                                    <h2>Collection</h2>
                                                </div>

                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Sl</th>
                                                            <th class="text-center">Month-Year</th>
                                                            <th class="text-center">Name</th>
                                                            <th class="text-center">Collection</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="collectionTable"></tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th class="text-right" colspan="3">Total</th>
                                                            <th class="text-right total_collection"></th>
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

                $.ajax({
                    type: "POST",
                    url: "{{ route('search_account_balance') }}",
                    data: {
                        _token      : "{{ csrf_token() }}" ,
                        collected_by: $("#auth_id").val(),
                    },
                    success: function (response)
                    {
                        unblockLoad();

                        $(".search").text("Search").prop("disabled", false);
                        console.log(response)
                        var collection = response;
                        $(".auth_id").val($("#auth_id").val());
                        if(collection!=0){
                            $(".expense_show").show();
                            $(".collectionTable").empty();
                            var total_collection=0;
                            var total_due=0;
                            var html="";
                            $.each(collection, function(key,value){
                                total_collection+=Number(value.receive_amount);
                                html+='<tr>' +
                                        '<td class="text-center">'+(key+1)+'</td>' +
                                        '<td class="text-center">'+value.bill_month+'-'+value.bill_year+'</td>' +
                                        '<td>'+value.id+'-'+value.name+'</td>' +
                                        '<td class="text-right">'+decimal(value.receive_amount)+'</td>' +
                                        '</tr>';
                            });
                            $(".collectionTable").html(html);
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