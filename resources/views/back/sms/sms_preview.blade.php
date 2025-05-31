@extends('layouts.app')

@section('title', 'SMS Preview')

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title">@yield("title")</h3>
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
                                    <div class="tab-pane active" id="DataList" aria-labelledby="base-tab2">
                                        <form action="{{ route("save_sms") }}" method="post">
                                            @csrf
                                            <input type="hidden" value="{{ $panel }}" name="client_type">
                                            <table id="sms_history_table" class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th class='text-center'>#</th>
                                                    <th class='text-center'>Sl. No.</th>
                                                    <th class='text-center'>Client Name</th>
                                                    <th class='text-center'>Cell No</th>
                                                    <th class='text-center'>SMS Text</th>
                                                    <th class='text-center'>SMS Count</th>
                                                    <th class='text-center'>From</th>
                                                    <th class='text-center'>
                                                        @if($sms_data)
                                                            @if($filter_by == 1)
                                                                Termination Date
                                                            @elseif($filter_by == 2)
                                                                Payment Date
                                                            @elseif($filter_by == 3)
                                                                Billing Date
                                                            @endif
                                                        @endif
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $total_sms = 0;
                                                    @endphp
                                                @foreach($sms_data as $key=>$sms)
                                                    @php
                                                        $total_sms += $sms["sms_count"];
                                                    @endphp
                                                    <tr>
                                                        <input type="hidden" value="due bill" name="sms_from[]">
                                                        <input type="hidden" value="tariqul" name="sms_sender[]">
                                                        <td class='text-center'>
                                                            <input type="checkbox" name="sms_value[]" value="{{ $sms["cell_no"] }}^{{ $sms["sms_text"]}}^{{ $sms["schedule_time"] }}" checked>
                                                        </td>
                                                        <td class='text-center'>{{ $key+1 }}</td>
                                                        <td class='text-left'>{!! $sms["client_name"] !!}</td>
                                                        <td class='text-center'>{{ $sms["cell_no"] }}</td>
                                                        <td class='text-center' id="sms_template_text">{{ $sms["sms_text"] }}</td>
                                                        <td class='text-center'>{{ $sms["sms_count"] }}</td>
                                                        <td class='text-center'>Due Bill</td>
                                                        <td class='text-center'>
                                                            @if($sms["filter_by"] == 1)                                                     
                                                            {{ $sms["termination_date"] }}
                                                            @elseif($sms["filter_by"] == 2)  
                                                            {{ $sms["payment_dateline"] }}        
                                                            @elseif($sms["filter_by"] == 3)
                                                                {{ $sms["billing_date"] }}
                                                            @endif
                                                        </td>
                                                    </tr>

                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="5" class='text-right'>Total SMS Qty=</th>
                                                        <th class='text-center'>{{ $total_sms}} </th>
                                                        <th class='text-center'></th>
                                                        <th class='text-center'></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <br>
                                            <div class="col-md-12">
                                                <div class="btn-group pull-right" role="group">
                                                    <a class="btn btn-info" href="{{ route("send-sms","isp") }}"><i class="ft-arrow-left"></i> Back</a>
                                                    <button class="btn btn-primary" type="submit">Confirm Send</button>

                                                </div>
                                            </div>
                                            <div style="clear:both; overflow: hidden;"></div>
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
@section("page_script")
    <script type="text/javascript">

        $(document).ready(function () {

            $("#sms_history_table").DataTable(
                    {
                        "lengthChange": false,
                        "bPaginate": false,
                        "bLengthChange": false,
                        "bFilter": true,
                        "bInfo": false,
                        "bProcessing": true,
                        "responsive": true,
                        "scrollX": true,
                        "scrollCollapse": true, }
            );

        });


    </script>

@endsection
