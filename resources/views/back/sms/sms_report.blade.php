@extends('layouts.app')

@section('title', 'SMS Report')

@section('content')
    <style>
        #sms_history_table td,
        #sms_history_table th {
            font-size: 12px !important;
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
                <div class="content-header-right col-md-10 col-12">


                </div>
            </div>
            <div class="content-body">
                <section id="configuration">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content collapse show">
                                    <div class="card-body card-dashboard">

                                        <div class="card inner-card">
                                            <div class="card-body" style="padding: 6px 10px;">
                                                <form action="{{ route('sms-report') }}" method="post" novalidate>
                                                    {{ csrf_field() }}
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <select class="form-control select2" name="report_type"
                                                                    id="report_type">
                                                                  
                                                                    <option
                                                                        @if ($report_type == 'Monthly') selected @endif
                                                                        value="Monthly">Monthly</option>
                                                                    <option
                                                                        @if ($report_type == 'Daily') selected @endif
                                                                        value="Daily">Daily</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input type="date" name="date_from" id="date_from"
                                                                    class="form-control" value="{{ $date_from }}"
                                                                    required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input type="date" name="date_to" id="date_to"
                                                                    class="form-control" value="{{ $date_to }}"
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
                                                <button type="button" class="btn btn-sm btn-danger mb-0 cancelSMS"
                                                    style="display: none;">Cancel Pending SMS</button>
                                            </div>
                                        </div>

                                        <div class="tab-content pt-1"> 
                                            <form id="TableForm">
                                                @csrf
                                                <table id="" class="table table-bordered table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th  class='text-center'>SL</th>
                                                            <th class='text-center'>Date</th>
                                                            <th  class='text-center'>SMS Count</th>
                                                            <th  class='text-center'>SMS Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php 
                                                        $total=0;
                                                        @endphp
                                                        @foreach ($sms_history as $key=>$sms)
                                                            @php 
                                                            $total+=$sms->sms_qty;
                                                            @endphp
                                                            <tr>
                                                                <td class='text-center'>{{ $key+1 }}</td>
                                                                @if ($report_type == 'Daily')
                                                                <td class='text-center'> {{ date('d-m-Y',strtotime($sms->sms_schedule_time)) }}</td>
                                                                @else
                                                                <td class='text-center'>{{ date('F Y',strtotime($sms->sms_schedule_time)) }}</td>
                                                                @endif
                                                                <td class='text-center'>{{ $sms->sms_count }}</td>
                                                                <td class='text-center'>{{ $sms->sms_qty }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3" class='text-center'>Total=</th>
                                                            <th  class='text-center'>{{$total}}</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </form>
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
@endsection
@section('page_script')
    <script type="text/javascript">
        $(document).ready(function() {


            $("#sms_history_table").DataTable({
               
                "bProcessing": true,
                "responsive": true,
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false
                }]
            });


            $(document).on('change', 'input[name="checkAll"]', function(e) {
                if ($(this).is(':checked')) {
                    $('input[name="sms_id[]"]').prop('checked', true);
                    if ($('input[name="sms_id[]"]').is(':checked')) {
                        $(".cancelSMS").show();
                    }
                } else {
                    $(".cancelSMS").hide();
                    $('input[name="sms_id[]"]').prop('checked', false);
                }
            });
            $(document).on('change', 'input[name="sms_id[]"]', function(e) {
                if ($('input[name="sms_id[]"]').is(':checked')) {
                    $(".cancelSMS").show();
                } else {
                    $(".cancelSMS").hide();
                }
            });
            $(document).on('click', ".cancelSMS", function(e) {
                if (confirm("Are you sure you want to cancel send SMS?")) {
                    $("#TableForm").trigger('submit');
                }
            });
            $(document).on('submit', "#TableForm", function(e) {
                e.preventDefault();

                $(".cancelSMS").text("Please Wait...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('sms-update') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(response) {
                        console.log(response);
                        if (response == 1) {
                            $.each($('input[name="sms_id[]"]'), function(k, v) {
                                if ($(v).is(':checked')) {
                                    $(v).hide();
                                }
                            });
                            $(".cancelSMS").hide();
                            toastr.success('Successfully done!', 'Success');
                        } else {
                            toastr.warning('Something is wrong! Try again!', 'Warning');
                        }
                        $(".cancelSMS").text("Cancel Pending SMS").prop("disabled", false);
                    },
                    error: function(request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try again!', 'Warning');
                        $(".cancelSMS").text("Cancel Pending SMS").prop("disabled", false);
                    }
                });
            });



        });
    </script>

@endsection
