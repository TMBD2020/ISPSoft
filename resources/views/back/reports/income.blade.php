@extends('layouts.app')

@section('title', 'Income Statement')

@section('content')


    <style>
        h4 span {
            padding-right: 10px;
        }

        .table td,
        .table th,
        .table {

            color: #000;
        }

        .table tr td:first-child,
        .table tr th:first-child {
            width: 10%;
        }

        .table tr td:nth-child(2),
        .table tr th:nth-child(2) {
            width: 70%;
        }

        .table tr td:nth-child(3),
        .table tr th:nth-child(3) {
            width: 20%;
            text-align: center;
        }

        .table tr td:last-child,
        .table tr th:last-child {
            width: 20%;
            text-align: center;
        }

        .form-group {
            margin: 0 !important;
        }

        .head_count {
            float: right;
        }
    </style>
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    <h3 class="content-header-title">@yield('title')</h3>
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
                                                <form id="" method="post" action="{{ route('search_income_statement') }}">
                                                    <div class="row">
                                                        {{ csrf_field() }}
                                                        <div class="col-md-2">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input type="date" name="date_from" id="date_from"
                                                                    class="form-control" value="{{ $date_from }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input type="date" name="date_to" id="date_to"
                                                                    class="form-control "value="{{ $date_to }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <button type="submit"
                                                                    class="btn btn-primary mb-0 search">Search</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    @if($data)
                                        <div class="card inner-card income_show">
                                            <div class="card-body" style="padding: 6px 10px;">
                                                <div class="col-md-12 text-center">
                                                    <h2>Income Statement</h2>
                                                    <h6>For the period from <big class="date_from">01.02.2020</big> to <big
                                                            class="date_to">29.02.2020</big></h6>
                                                </div>

                                                <table class="table table-bordered incomeTable">
                                                    <table class="table table-bordered incomeTable">
                                                        <tr class="">
                                                            <th colspan="4" class="text-center">
                                                                <h3 style="margin:0;">Opening Balance: <span
                                                                        class="badge badge-primary">{{ number_format($opening_balance,2) }} </span></h3>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th style="border-color: #fff; border-bottom: 1px; border-top: 1px;"
                                                                colspan="3"></th>
                                                        </tr>
                                                        <tr style="background: #e6e1dc">
                                                            <th colspan="2">Revenue</th>
                                                            <th>Taka</th>
                                                            <th>#</th>
                                                        </tr>
                                                      
                                                        @php
                                                            $total_revenue = 0;
                                                            $total_expense = 0;
                                                        @endphp
                                                        @foreach ($revenue_list as $item)
                                                            @php
                                                                $total_revenue += $item->amount;
                                                            @endphp
                                                            <tr>
                                                                <td style="border:0;"></td>
                                                                <td>{{ $item->title}} </td>
                                                                <td>{{ number_format($item->amount,2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                        @endforeach
                                                     
                                                        <tr>
                                                            <th colspan="2">Total Revenues</th>
                                                            <th class="text-center">{{ number_format($total_revenue,2) }} </th>
                                                            <th></th>
                                                        </tr>
                                                        <tr>
                                                            <th style="border-color: #fff; border-bottom: 1px; border-top: 1px;"
                                                                colspan="3"></th>
                                                        </tr>
                                                        <tr class="expense_list">
                                                            <th colspan="2">Expense</th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        <tr>
                                                            <td style="border:0;"></td>
                                                            <td>Loan Payment </td>
                                                            <td>0.00</td>
                                                            <td></td>
                                                        </tr>
                                                        <tr></tr>
                                                        <tr>
                                                            <th colspan="2">Total Expenses</th>
                                                            <th class="total_expense text-center">0.00</th>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <th style="border-color: #fff; border-bottom: 1px; border-top: 1px;"
                                                                colspan="3"></th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="2">Net Income</th>
                                                            <th class="text-center">{{ number_format(($total_revenue+$opening_balance)-$total_expense,2)}}</th>
                                                            <th></th>
                                                        </tr>
                                                        <tr>
                                                            <th style="border-color: #fff; border-bottom: 1px; border-top: 1px;"
                                                                colspan="3"></th>
                                                        </tr>
                                                        <tr class="">
                                                            <th colspan="4" class="text-center">
                                                                <h3 style="margin:0;">Closing Balance: <span
                                                                        class="badge badge-primary">{{ number_format(($total_revenue+$opening_balance)-$total_expense,2)}} </span></h3>
                                                            </th>
                                                        </tr>
                                                    </table>
                                                </table>
                                            </div>
                                        </div>

                                        @endif
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
    <!-- Modal -->
    <div class="modal fade text-left" id="infoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog model-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary white">
                    <h4 class="modal-title white" id="modelTitle"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="col-md-12">
                        <div class="row">
                            <table style="width: 100%; border-collapse: collapse" border="1" cellpadding="5">
                                <thead>
                                    <tr>
                                        <th class="text-center">Sl</th>
                                        <th class="text-center">Expense By</th>
                                        <th class="text-center">Approved By</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Note</th>
                                    </tr>
                                </thead>
                                <tbody class="expenseBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_script')

    <script type="text/javascript">
        $(document).ready(function() {

            $(document).on('click', ".info_btn", function() {
                $("#infoModal").modal("show");
                var id = $(this).attr("id");
                var title = $(this).attr("title");
                var date_from = $("#date_from").val();
                var date_to = $("#date_to").val();
                $("#modelTitle").html(title + " (" + date_from + "-" + date_to + ")");

                var data = "id=" + id + "&date_from=" + date_from + "&date_to=" + date_to +
                    "&_token={{ csrf_token() }}";

                $.ajax({
                    type: "POST",
                    url: "{{ route('expense_details') }}",
                    data: data,
                    success: function(response) {
                        //console.log(response);
                        if (response != 0) {
                            var json = JSON.parse(response);
                            $(".expenseBody").empty();
                            $.each(json, function(key, value) {
                                var html = '' +
                                    '<tr>' +
                                    '<td class="text-center">' + (key + 1) + '</td>' +
                                    '<td>' + value.expensed_by + '</td>' +
                                    '<td>' + value.approved_by + '</td>' +
                                    '<td class="text-right">' + decimal(value
                                        .expense_amount) + '</td>' +
                                    '<td>' + value.note + '</td>' +
                                    '</tr>';
                                $(".expenseBody").append(html);

                            });
                        } else {
                            toastr.warning('Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(".save").text("Save").prop("disabled", false);
                    },
                    error: function(request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        $(".save").text("Save").prop("disabled", false);
                    }
                });
            });

        });

        

        function decimal(num) {
            var value = Number(num) * 100 / 100;
            return value.toFixed(2);
        }
    </script>

    <style>
        .card.inner-card {
            box-shadow: none !important;
            border: 1px solid rgba(222, 223, 241, 0.3) !important;
        }

        input[readonly] {
            background: #f5f4f4;
            border: 1px solid #bfb6b6;
        }
    </style>
@endsection
