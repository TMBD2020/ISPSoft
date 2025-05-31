@extends('layouts.app')

@section('title', 'Expense Report')

@section('content')


    <style>
        h4 span{
            padding-right: 10px;
        }
        .table td,.table th, .table{
            color: #000;
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
                                                                <select class="form-control select2" name="expense_head" id="expense_head">
                                                                    <option value="0">All Expense Head</option>
                                                                    @foreach($expense_heads as $row)
                                                                    <option value="{{ $row->id }}">{{ $row->expense_head_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">

                                                                <select class="form-control select2" name="expense_by" id="expense_by">
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
                                                                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $date_from }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input type="date" name="date_to" id="date_to" class="form-control " value="{{ $date_to }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group" >
                                                                <button type="button" class="btn btn-primary mb-0 search">Search</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>


                                        <div class="card inner-card expense_show" style="display: none;">
                                            <div class="card-body" style="padding: 6px 10px;">
                                                <div class="pull-right" style="clear:both; overflow: hidden;">
                                                    <form id="OPform" action="{{ route("download-expense-pdf") }}" method="post" target="_blank">
                                                        @csrf
                                                        <input type="hidden" name="date_to" class="to_date" />
                                                        <input type="hidden" name="date_from" class="from_date" />
                                                        <input type="hidden" name="expense_head" class="expense_head" />
                                                        <input type="hidden" name="expense_by" class="expense_by" />
                                                        <input type="submit" class="btn grey btn-primary" name="operation" value="Download PDF">
                                                        <input type="submit" class="btn grey btn-success" name="operation" value="Print">
                                                    </form>
                                                </div>
                                                <div class="col-md-12 text-center" style="clear:both; overflow: hidden;">
                                                    <h2>Expense</h2>
                                                    <h6>For the period from <big class="date_from"></big> to <big class="date_to"></big></h6>
                                                </div>

                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5%" class="text-center">SL</th>
                                                            <th style="width: 5%">Date</th>
                                                            <th style="width: 20%">Expense Head</th>
                                                            <th style="width: 15%">Expense By</th>
                                                            <th style="width: 15%">Approved By</th>
                                                            <th style="width: 10%">Amount</th>
                                                            <th style="width: 30%">Note</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="expenseTable"></tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <th class="text-right" colspan="5">Total</th>
                                                        <th class="text-right total_expense"></th>
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
                    url: "{{ route('search_expense_report') }}",
                    data: {
                        _token      : "{{ csrf_token() }}" ,
                        date_from   :$("#date_from").val(),
                        date_to     :$("#date_to").val(),
                        expense_by  :$("#expense_by").val(),
                        expense_head:$("#expense_head").val()
                    },
                    success: function (response)
                    {
                        $(".search").text("Search").prop("disabled", false);
                        $(".expense_show").show();
                       var json = JSON.parse(response);
                        console.log(response);
                        var expense = json.expense;
                        var dates = json.dates;
                        $(".date_from").html(dates.date_from);
                        $(".date_to").html(dates.date_to);
                        $(".from_date").val(dates.date_from);
                        $(".to_date").val(dates.date_to);
                        $(".expense_by").val($("#expense_by").val());
                        $(".expense_head").val($("#expense_head").val());
                        if(expense!=0){
                            $(".expenseTable").empty();
                            var total_expense=0;
                            var html="";
                            $.each(expense, function(key,value){
                                total_expense+=Number(value.expense_amount);
                                html+='<tr>' +
                                        '<td class="text-center">'+(key+1)+'</td>' +
                                        '<td>'+value.expense_date+'</td>' +
                                        '<td>'+value.expense_name+'</td>' +
                                        '<td>'+value.expense_by+'</td>' +
                                        '<td>'+value.approved_by+'</td>' +
                                        '<td class="text-right">'+decimal(value.expense_amount)+'</td>' +
                                        '<td>'+value.expense_note+'</td>' +
                                        '</tr>';
                            });
                            $(".expenseTable").html(html);
                            $(".total_expense").html(decimal(total_expense));

                    }else{
                            $(".expense_show").hide();
                            $(".expenseTable").empty();
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