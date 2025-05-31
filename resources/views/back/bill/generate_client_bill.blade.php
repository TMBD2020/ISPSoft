@extends('layouts.app')

@section('title', 'Generate ISP Client Bill')

@section('content')
<style>
    .table td,.table th {
        font-size: 13px;
        vertical-align: middle;
    }
    .table td{
        padding: 1px 5px;
    }
    .table th {
        padding:5px;
    }
    .table td span {
        font-size: 18px;
    }
</style>
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title"><span id="tabOption">Generate ISP Bill</span></h3>
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
                                <div class="card-body card-dashboard" style="padding: 0; padding-bottom: 1.5rem;">

                                    <div class="tab-content pt-1">
                                        <div class="tab-pane active" id="operation" aria-labelledby="base-tab2">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
                                                        <form id="DataForm" method="post" >
                                                            {{ csrf_field() }}
                                                            <div class="">
                                                                <input type="hidden" id="action" name="action">
                                                                <input type="hidden" id="id" name="id">
                                                                @if(auth()->user()->user_type != "reseller")
                                                                <div class="row">
                                                                    <label for="company_id" class="col-sm-4 control-label">Company/Reseller<span class="text-danger">*</span></label>
                                                                    <div class="col-sm-8">
                                                                        <select class="form-control select2" id="company_id" name="company_id">
                                                                            <option value="{{ auth()->user()->company_id }}"> {{ auth()->user()->name }} (O)</option>
                                                                            @foreach($companies as $row)
                                                                                <option value="{{ $row->auth_id }}">{{ $row->reseller_id }} :: {{ $row->reseller_name }} (R) </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                @endif

                                                                <div class="row">
                                                                    <label for="package_id" class="col-sm-4 control-label">Month-Year <span class="text-danger">*</span></label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" value="{{ date("F-Y") }}" class="form-control" readonly>
                                                                    </div>
                                                                </div>

                                                                {{-- <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <label><input type="checkbox" name="payment_confirm_sms" value="1" checked> SMS</label>
                                                                        <label><input type="checkbox" name="payment_confirm_email" value="1" checked> Email</label>
                                                                    </div>
                                                                    <div style="clear:both;"></div>
                                                                </div> --}}

                                                            </div>
                                                            <button type="submit" class="btn btn-primary mt-1 mb-0 save">Generate Bill Preview</button>
                                                        </form>
                                                    
                                                    </div>
                                                    
                                                    <div class="col-lg-12 col-xs-12  col-md-12 col-sm-12">    <div id="console"></div></div>
                                                </div>
                                            </div>

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

<style>
    form .row{
        margin-bottom: 10px;
    }
    .taka{
        font-size: 16px;;
    }
</style>
@endsection
@section("page_script")
    <script type="text/javascript">
        var table_unpaid_bill, table_today_collection, table_all_collection,table_all_bill;


        $(document).ready(function () {

            $(document).on('submit', "#DataForm", function (e) {
                e.preventDefault();

                $('#console').empty();
                $(".save").text("Generating...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('generate-isp-client-bill-preview') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                     //   console.log(response)
                        $('#console').html(response);
                        $(".save").text("Generate Bill Preview").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(".save").text("Generate Bill Preview").prop("disabled", false);
                       
                $('#console').empty();
                    }
                });
            });

            
            $(document).on('submit', "#billGenerate", function (e) {
                e.preventDefault();


                if(!confirm("Are you sure to generate bill?")){
                    return false;
                }

                $(".save").text("Generating...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('generate-isp-client-bill-save') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        console.log(response)
                        $('#console').html(response);
                        $(".save").text("Generate Bill Preview").prop("disabled", false);
                        // if (response.status == 'success') {
                        //     toastr.success('Data Saved Successfully!','Success');
                        // }else if(response == 101){
                        //     toastr.warning( 'Bill already created!', 'Warning');
                        // }
                        // else {
                        //   toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                        // }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(".save").text("Generate Bill Preview").prop("disabled", false);
                    }
                });
            });

        });

    </script>


@endsection
