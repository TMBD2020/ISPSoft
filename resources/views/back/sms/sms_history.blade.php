@extends('layouts.app')

@section('title', 'SMS History')

@section('content')
        <style>
           #sms_history_table td, #sms_history_table th{
               font-size: 12px !important;
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
                                            <form action="{{ route("sms-history") }}" method="post" novalidate>
                                                {{csrf_field()}}
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2" name="sms_status" id="sms_status">
                                                                <option value="">All</option>
                                                                <option @if($sms_status=='Pending')selected @endif value="Pending">Pending</option>
                                                                <option @if($sms_status=='Sent')selected @endif value="Sent">Sent</option>
                                                                <option @if($sms_status=='Receiver Error')selected @endif value="Receiver Error">Receiver Error</option>
                                                                <option @if($sms_status=='Failed')selected @endif value="Failed">Failed</option>
                                                                <option @if($sms_status=='Cancel')selected @endif value="Cancel">Cancel</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $date_from }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $date_to }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group" >
                                                            <button type="submit" class="btn btn-primary mb-0 search">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger mb-0 cancelSMS" style="display: none;">Cancel Pending SMS</button>
                                        </div>
                                    </div>

                                    <div class="tab-content pt-1">


                                            <form id="TableForm">
                                                @csrf
                                                <table id="sms_history_table" class="table table-bordered table-responsive">
                                                    <thead>
                                                    <tr>
                                                        <th style="width:5%" class='text-center'><input type="checkbox" name="checkAll"></th>
                                                        <th style="width:5%" class='text-center'>Cell No</th>
                                                        <th style="width:55%" class='text-center'>SMS Text</th>
                                                        <th style="width:5%" class='text-center'>SMS Count</th>
                                                        <th style="width:5%" class='text-center'>Sender</th>
                                                        <th style="width:10%" class='text-center'>Shcedule Time</th>
                                                        <th style="width:10%" class='text-center'>Sent Time</th>
                                                        <th style="width:5%" class='text-center'>Status</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                 
                                                    @foreach($sms_history as $sms)
                                                  
                                                        <tr>
                                                            <td class='text-center'>@if($sms->sms_status=='Pending')<input class="pending" value="{{$sms->id}}" name="sms_id[]" type="checkbox">@endif</td>
                                                   
                                                            <td>{{ $sms->sms_receiver }}</td>
                                                            <td>{{ $sms->sms_text }}</td>
                                                                <td class='text-center'>{{ $sms->sms_count }}</td>
                                                            <td class='text-center'>{{ $sms->sms_sender }}</td>
                                                            <td class='text-center'>{{ $sms->sms_schedule_time }}</td>
                                                            <td class='text-center'>{{ $sms->sent_time }}</td>
                                                            <td class='text-center'>{{ $sms->sms_status }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <th style="width:5%" class='text-center'></th>
                                                        <th style="width:5%" class='text-center'></th>
                                                        <th style="width:55%" class='text-center'></th>
                                                        <th style="width:5%" class='text-center'></th>
                                                        <th style="width:5%" class='text-center'></th>
                                                        <th style="width:10%" class='text-center'></th>
                                                        <th style="width:10%" class='text-center'></th>
                                                        <th style="width:5%" class='text-center'></th>
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
@section("page_script")

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function () {

           
            $("#sms_history_table").DataTable({
                "aaSorting"     : [[0, 'desc']],
                "bProcessing"   : true,
                "responsive"    : true,
                "columnDefs": [ {
                    "targets":[0,1,2,3,4],
                    "orderable": false
                }],
                // dom: 'lBfrtip',
				// buttons: [
				// 	{
				// 		extend: 'print',
				// 		text: 'Print All Data',
				// 		title: `<div class="title"><h3></h3><h5>Customer List</h5></div>`,
				// 		exportOptions: {
				// 			columns: ':visible', // Print only visible columns
				// 			modifier: {
				// 				search: 'applied', // Print only searched results
				// 				order: 'applied',
				// 				page: 'all' // Print **ALL PAGES**
				// 			}
				// 		},
				// 		customize: function (win) {
				// 			$(win.document.body).find('.title').css({
				// 				'text-align': 'center'
				// 			});
				// 		}
				// 	},
					
				// ],
            });


           $(document).on('change', 'input[name="checkAll"]', function (e) {
                if($(this).is(':checked')){
                    $('input[name="sms_id[]"]').prop('checked',true);
                    if($('input[name="sms_id[]"]').is(':checked')){
                        $(".cancelSMS").show();
                    }    
                }else{
                    $(".cancelSMS").hide();
                    $('input[name="sms_id[]"]').prop('checked',false);
                }
            });
            $(document).on('change', 'input[name="sms_id[]"]', function (e) {
                if($('input[name="sms_id[]"]').is(':checked')){
                    $(".cancelSMS").show();
                }else{
                    $(".cancelSMS").hide();
                }
            });
            $(document).on('click', ".cancelSMS", function (e) {
                if(confirm("Are you sure you want to cancel send SMS?")){
                    $("#TableForm").trigger('submit');
                }
            });
            $(document).on('submit', "#TableForm", function (e) {
                e.preventDefault();

                $(".cancelSMS").text("Please Wait...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('sms-update') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        console.log(response);
                        if (response == 1) {
                            $.each($('input[name="sms_id[]"]'),function(k,v){
                                if($(v).is(':checked')){
                                    $(v).hide();
                                }
                            });
                            $(".cancelSMS").hide();
                            toastr.success('Successfully done!','Success');
                        }
                        else {
                            toastr.warning( 'Something is wrong! Try again!', 'Warning');
                        }
                        $(".cancelSMS").text("Cancel Pending SMS").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try again!', 'Warning');
                        $(".cancelSMS").text("Cancel Pending SMS").prop("disabled", false);
                    }
                });
            });



        });

    </script>

@endsection
