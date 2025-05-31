@extends('layouts.app')

@section('title', 'Add SMS Balance')

@section('content')


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

                                    <div class="card">
                                       
                                        <div class="card-body">
                                            <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
                                                <form id="DataForm" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="">
                                                        <div class="form-group">
                                                            <label for="company_id">Company <span
                                                                class="text-danger">*</span></label>
                                                            <select name="company_id" id="company_id" class="form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($companies as $item)
                                                                <option value="{{ $item->id}}" bal="{{$item->sms_balance}}" mask="{{$item->masking_rate}}" non_mask="{{$item->non_masking_rate}}">{{ $item->name}} </option>
                                                                @endforeach
                                                                
                                                              
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="sms_type">SMS Type <span
                                                                class="text-danger">*</span></label>
                                                            <select name="sms_type" id="sms_type" class="form-control">
                                                               
                                                                <option value="non-masking">Non-masking</option>
                                                                <option value="masking">Masking</option>
                                                              
                                                                
                                                              
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label for="sms_rate">SMS Rate </label>
                                                            <input type="number" class="form-control" name="sms_rate" readonly
                                                                id="sms_rate">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="current_balance">Available Balance </label>
                                                            <input type="number" class="form-control" readonly
                                                                id="current_balance">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="current_sms_qty">Available SMS Qty </label>
                                                            <input type="number" class="form-control" readonly
                                                                id="current_sms_qty">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="new_balance">New Balance <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="number" step=".01" class="form-control" name="new_balance"
                                                                id="new_balance" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="new_sms_qty">New SMS Qty</label>
                                                            <input type="number" class="form-control" name="new_sms_qty"
                                                                id="new_sms_qty" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="transaction_date">Date <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" name="transaction_date" value="{{date('Y-m-d')}}"
                                                                id="transaction_date" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="note">Note </label>
                                                            <input type="text" class="form-control" name="note"
                                                                id="note">
                                                        </div>

                                                        
                                                    </div>
                                                    <button type="submit"
                                                        class="btn btn-primary mt-1 mb-0 save">Save</button>
                                                </form>
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

@endsection

@section('page_script')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('submit', "#DataForm", function(e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('super.sms_add_balance') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(response) {
                        console.log(response);
                        if (response.id) {
                            $("#DataForm").trigger("reset");
                            toastr.success('Data Saved Successfully!', 'Success');
                           
                        } else {
                            var msg = ''
                            if (response) {
                                $.each(response, function(k, v) {
                                    msg += (v) + "\n"
                                })
                            }
                            if (!msg) {
                                msg = 'Data Cannot Saved. Try aging!'
                            }
                            toastr.warning(msg, 'Warning');
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
            $(document).on('change', "#company_id", function() {
                var sms_rate = $(this).val() == 'masking' ? $("#company_id").find(':selected').attr('mask') : $("#company_id").find(':selected').attr('non_mask');
                $("#sms_rate").val(sms_rate);
                var balance = Number($("#company_id").find(':selected').attr('bal'));
                var sms_qty= balance/Number(sms_rate);
                $("#current_sms_qty").val(Math.floor(sms_qty));
                $("#current_balance").val(balance);
            });
            $(document).on('change', "#sms_type", function() {
                var sms_rate = $(this).val() == 'masking' ? $("#company_id").find(':selected').attr('mask') : $("#company_id").find(':selected').attr('non_mask');
                $("#sms_rate").val(sms_rate);
                var balance = Number($("#company_id").find(':selected').attr('bal'));
                var sms_qty= balance/Number(sms_rate);
                $("#current_sms_qty").val(Math.floor(sms_qty));
                $("#current_balance").val(balance);
            });
            
            $(document).on('change keyup', "#new_balance", function() {
                var sms_rate = Number($("#sms_rate").val()) ;
                var current_balance = Number($("#current_balance").val());
                var new_balance = Number($(this).val());
                var new_sms_qty= (current_balance+new_balance)/sms_rate;
                $("#new_sms_qty").val(Math.floor(new_sms_qty));
            });

        });
    </script>
@endsection
