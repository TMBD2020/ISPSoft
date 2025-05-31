@extends('layouts.app')

@section('title', 'Generate Salary Sheet')

@section('content')


    <style>
        h4 span{
            padding-right: 10px;
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
                                                <form id="SalarySearchForm" method="post" novalidate>
                                                    <div class="row">
                                                        {{csrf_field()}}
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="">Department <code>*</code></label>
                                                                <select class="form-control select2"  name="department_id" id="department_id" onchange="employee_list()" required>

                                                                    <option value="">Select Department</option>
                                                                    @foreach($department_list as $row)
                                                                        <option value="{{$row->id}}">{{$row->department_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="">Designation <code>*</code></label>
                                                                <select class="form-control select2"  name="designation_id" id="designation_id" onchange="employee_list()" required>

                                                                    <option value="">Select Designation</option>
                                                                    @foreach($designation_list as $row)
                                                                        <option value="{{$row->id}}">{{$row->designation_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="">Employee <code>*</code></label>
                                                                <div class="emp_list">
                                                                    <select class="form-control select2"  name="emp_id" id="emp_id" required>
                                                                        <option value="">Select Employee</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3"></div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="">Month <code>*</code></label>
                                                                <select class="form-control select2"  name="salary_month" id="salary_month" required>
                                                                    <option value="1" {{date('m')== 1 ? 'selected' : ""}}>January</option>
                                                                    <option value="2" {{date('m')== 2 ? 'selected' : ""}}>February</option>
                                                                    <option value="3" {{date('m')== 3 ? 'selected' : ""}}>March</option>
                                                                    <option value="4" {{date('m')== 4 ? 'selected' : ""}}>April</option>
                                                                    <option value="5" {{date('m')== 5 ? 'selected' : ""}}>May</option>
                                                                    <option value="6" {{date('m')== 6 ? 'selected' : ""}}>June</option>
                                                                    <option value="7" {{date('m')== 7 ? 'selected' : ""}}>July</option>
                                                                    <option value="8" {{date('m')== 8 ? 'selected' : ""}}>August</option>
                                                                    <option value="9" {{date('m')== 9 ? 'selected' : ""}}>September</option>
                                                                    <option value="10" {{date('m')== 10 ? 'selected' : ""}}>October</option>
                                                                    <option value="11" {{date('m')== 11 ? 'selected' : ""}}>November</option>
                                                                    <option value="12" {{date('m')== 12 ? 'selected' : ""}}>December</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="">Year <code>*</code></label>
                                                                <select class="form-control select2"  name="salary_year" id="salary_year" required>
                                                                    @php
                                                                    $earliest_year = 2017;
                                                                    $currently_selected = date('Y');
                                                                    $latest_year = date('Y');
                                                                    @endphp
                                                                    @foreach ( range( $latest_year, $earliest_year ) as $i )
                                                                        <option value='{{$i}}' {{$i == $currently_selected ? ' selected' : ''}}>{{$i}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group" style="margin-top: 25px">
                                                                <button type="submit" class="btn btn-primary mb-0 search">Search</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>


                                        <div class="card inner-card salary_show" style="display: none;">
                                            <div class="card-body" style="padding: 6px 10px;">
                                                <form id="SalaryDistributionForm" action="{{ route('save_monthly_salary') }}" method="post" novalidate>
                                                    <div class="row">
                                                        {{csrf_field()}}
                                                        <input type="hidden" id="salary_emp_id" name="salary_emp_id">
                                                        <input type="hidden" id="salary_month_input" name="salary_month">
                                                        <input type="hidden" id="salary_year_input" name="salary_year">
                                                        <input type="hidden" id="action" name="action" value="1">
                                                        <input type="hidden" name="salary_id" class="salary_id">
                                                        <div class="inputs"></div>
                                                        <div class="col-md-8 ">
                                                            <!--div class="pull-right">
                                                                <button type="button" class="btn btn-primary mt-1 mb-0 print"><i class="fa fa-print"></i> Print</button>
                                                            </div-->
                                                            <div  style="clear:both !important;"></div>
                                                            <table>
                                                                <tr>
                                                                    <th>Employee ID</th>
                                                                    <th>:</th>
                                                                    <td class="id"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Employee Name</th>
                                                                    <th>:</th>
                                                                    <td class="name"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Department</th>
                                                                    <th>:</th>
                                                                    <td class="department"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Designation</th>
                                                                    <th>:</th>
                                                                    <td class="designation"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Month/Year</th>
                                                                    <th>:</th>
                                                                    <td class="month_year"></td>
                                                                </tr>
                                                            </table>
                                                            <table class="table table-bordered">
                                                                <tbody id="salaryBody"></tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-md-2"></div>
                                                        <div class="col-md-2"></div>
                                                        <div class="col-md-8">
                                                            <div class="form-group" style="margin-top: 25px">
                                                                <!--button type="button" class="btn btn-primary mt-1 mb-0 print"><i class="fa fa-print"></i> Print</button-->
                                                                <button type="submit" class="btn btn-primary mt-1 mb-0 save">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <form id="print-form" target="_blank" action="{{ route('salary-sheet-print') }}" method="POST" style="display: none;">
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="salary_id" class="salary_id">
                                                </form>
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
        #DataForm .row{
            margin-top: 10px;
        }
        #DataForm .row label{
            text-align: left;
        }
    </style>

@endsection
@section("page_script")

    <script type="text/javascript">


        $(document).ready(function () {

            $(document).on('submit', "#SalarySearchForm", function (e) {
                e.preventDefault();
                if($("#department_id").val()!=""  && $("#designation_id").val()!=""){
                    $(".search").text("Searching...").prop("disabled", true);

                    $.ajax({
                        type: "POST",
                        url: "{{ route("search_individual_salary") }}",
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function (response)
                        {

                            $(".search").text("Search").prop("disabled", false);
                            var json = JSON.parse(response);

                            var action,salary_id,salary_info,salary_data,emp_late_fine,emp_absent_fine,others_fine,advanced_salary;
                            if(json.salary==0){//new salary
                                salary_info = json.salary_info;
                                action = 1;
                                salary_id = emp_late_fine = emp_absent_fine = others_fine = 0;
                                salary_data = salary_info.salary;
                                get_adv_salary($("#emp_id").val());
                            }else{
                                salary_info = json.salary_info;
                                action = 2;
                                salary_data = json.salary;
                                salary_id = salary_data.id;
                                emp_late_fine = salary_data.emp_late_fine;
                                emp_absent_fine = salary_data.emp_absent_fine;
                                others_fine = salary_data.emp_others_fine;
                                advanced_salary = salary_data.advanced_salary;

                            }


                            $("#action").val(action);
                            $(".salary_show").show();
                            $(".salary_id").val(salary_id);
                            $("#salary_emp_id").val($("#emp_id").val());
                            $("#salary_month_input").val($("#salary_month").val());
                            $("#salary_year_input").val($("#salary_year").val());
                            $(".id").html($("#emp_id option:selected").text().split("::")[0]);
                            $(".name").html($("#emp_id option:selected").text().split("::")[1]);
                            $(".designation").html($("#designation_id option:selected").text());
                            $(".department").html($("#department_id option:selected").text());
                            $(".month_year").html($("#salary_month option:selected").text()+"/"+$("#salary_year").val());

                            var additional = "";
                            var emp_salary = salary_info.salary.split(",");
                            var additional_total=0;

//                            console.log(salary_info.salary);
//                            console.log(emp_salary);
                            if(json.salary==0){
                                if(emp_salary.length>1){
                                    $.each(emp_salary, function(key,value){
                                        var values = value.split("|");


                                        additional+='<tr>'+
                                                '<td class="text-center">'+(key+1)+'</td>'+
                                                '<td>'+values[1]+' ('+values[2]+'%)</td>' +
                                                '<input type="hidden" name="title[]" value="'+values[1]+'">'+
                                                '<input type="hidden" name="percent[]" value="'+values[2]+'">'+
                                                '<input type="hidden" name="amount[]" value="'+values[3]+'">'+
                                                '<td class="text-center">'+
                                                '<input type="number" name="emp_basic" id="emp_basic" value="'+values[3]+'" class="text-right" readonly tabindex="-1">'+
                                                '</td>'+
                                                '</tr>';
                                        additional_total+=Number(values[3]);
                                    });
                                }else{
                                    additional+='<tr>'+
                                            '<td class="text-center">1</td>'+
                                            '<td>Salary</td>'+
                                            '<td class="text-center">'+
                                            '<input type="hidden" name="title[]" value="Salary">'+
                                            '<input type="hidden" name="percent[]" value="100">'+
                                            '<input type="hidden" name="amount[]" value="'+salary_info.gross+'">'+
                                            '<input type="number" name="emp_basic" id="emp_basic" value="'+salary_info.gross+'" class="text-right" readonly tabindex="-1">'+
                                            '</td>'+
                                            '</tr>';
                                    additional_total+=Number(salary_info.gross);
                                }
                            }else{
                                if(emp_salary.length>1){
                                    $.each(emp_salary, function(key,value){
                                        var values = value.split("|");

                                        additional+='<tr>'+
                                                '<td class="text-center">'+(key+1)+'</td>'+
                                                '<td>'+values[0]+' ('+values[2]+'%)</td>' +
                                                '<input type="hidden" name="title[]" value="'+values[1]+'">'+
                                                '<input type="hidden" name="percent[]" value="'+values[2]+'">'+
                                                '<input type="hidden" name="amount[]" value="'+values[3]+'">'+
                                                '<td class="text-center">'+
                                                '<input type="number" name="emp_basic" id="emp_basic" value="'+values[3]+'" class="text-right" readonly tabindex="-1">'+
                                                '</td>'+
                                                '</tr>';
                                        additional_total+=Number(values[3]);
                                    });
                                }else{
                                    var values = emp_salary[0].split("|");
                                    additional+='<tr>'+
                                            '<td class="text-center">1</td>'+
                                            '<td>'+values[0]+'</td>'+
                                            '<td class="text-center">'+
                                            '<input type="hidden" name="title[]" value="'+values[0]+'">'+
                                            '<input type="hidden" name="percent[]" value="'+values[1]+'">'+
                                            '<input type="hidden" name="amount[]" value="'+values[2]+'">'+
                                            '<input type="number" name="emp_basic" id="emp_basic" value="'+values[2]+'" class="text-right" readonly tabindex="-1">'+
                                            '</td>'+
                                            '</tr>';
                                    additional_total+=Number(values[2]);
                                }
                            }

                            var sl = emp_salary.length;
                            var abent = sl+1;
                            var abentF = abent+1;
                            var adv = abentF+1;
                            var otherF = adv+1;


                            var html =
                                    '<tr style="background: #f1ecec">'+
                                    '<th  class="text-center" colspan="2">Addition</th>'+
                                    '<th class="text-center">Taka</th>'+
                                    '</tr>'+
                                    additional+
                                    '<tr>'+
                                    '<th colspan="2" class="text-center">Total</th>'+
                                    '<td class="text-center">'+
                                    '<input type="number" id="additional_total" value="'+decimal(additional_total)+'" class="text-right" tabindex="-1" readonly>'+
                                    '</td>'+
                                    '</tr>'+
                                    '<tr style="background: #f1ecec">'+
                                    '<th class="text-center" colspan="2">Deduction</th>'+
                                    '<td></td>'+
                                    '</tr>'+

                                    '<tr>'+
                                    '<td class="text-center">'+abent+'</td>'+
                                    '<td>Late Fine</td>'+
                                    '<td class="text-center">'+
                                    '<input type="number" min="0" name="emp_late_fine" id="emp_late_fine" onkeyup="salary_calculations()" onchange="salary_calculations()" value="0" class="text-right">'+
                                    '</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                    '<td class="text-center">'+abentF+'</td>'+
                                    '<td>Absent Fine</td>'+
                                    '<td class="text-center">'+
                                    '<input type="number" name="emp_absent_fine" id="emp_absent_fine" class="text-right" min="0" onkeyup="salary_calculations()" onchange="salary_calculations()" value="0">'+
                                    '</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                    '<td class="text-center">'+adv+'</td>'+
                                    '<td>Advanced Salary</td>'+
                                    '<td class="text-center">'+
                                    '<input type="number" name="emp_advanced_salary" id="emp_advanced_salary" class="text-right" readonly value="0"  onkeyup="salary_calculations()">'+
                                    '</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                    '<td class="text-center">'+otherF+'</td>'+
                                    '<td>Others Fine</td>'+
                                    '<td class="text-center">'+
                                    '<input type="number" name="emp_others_fine" id="others_fine" min="0" onkeyup="salary_calculations()" onchange="salary_calculations()" class="text-right" value="0">'+
                                    '</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                    '<th colspan="2" class="text-center">Total</th>'+
                                    '<td class="text-center">'+
                                    '<input type="number" name="deduction_total" id="deduction_total" class="text-right" readonly tabindex="-1">'+
                                    '</td>'+
                                    '</tr>'+
                                    '<tr style="background: #f1ecec">'+
                                    '<th colspan="2" class="text-center">Net Salary</th>'+
                                    '<th class="text-center">'+
                                    '<input type="number" name="net_total" id="net_total" class="text-right" readonly tabindex="-1">'+
                                    '</th>'+
                                    '</tr>';
                            $("#salaryBody").html(html);

                            $("#emp_late_fine").val(emp_late_fine);
                            $("#emp_absent_fine").val(emp_absent_fine);
                            $("#others_fine").val(others_fine);
                            if(json.salary!=0){
                                $("#emp_advanced_salary").val(advanced_salary);
                            }
                            salary_calculations();

                        },
                        error: function (request, status, error) {
                            console.log(request.responseText);
                            toastr.warning( 'Server Error. Try aging!', 'Warning');
                            $(".search").text("Search").prop("disabled", false);
                        }
                    });
                }

            });

            $(document).on('submit', "#SalaryDistributionForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route("save_monthly_salary") }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        if(response==1){
                            toastr.success( 'Data Successfully Saved!', 'Success');
                            $("#SalaryDistributionForm").trigger("reset");
                            $("#SalarySearchForm").trigger("reset");
                            $(".salary_show").hide();
                            $("#department_id").trigger("change");
                        }else{
                            toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(".save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(".save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('click', '.print', function () {
                $('#print-form').submit();
            });

        });

        function employee_list(){
            var department_id = $("#department_id").val();
            var designation_id = $("#designation_id").val();
            var data = 'department_id=' + department_id + '&designation_id=' + designation_id + '&_token={{csrf_token()}}';

            $.ajax({
                type: "POST",
                url: "{{ route("employee_list") }}",
                data: data,
                success: function (response) {
                    $("#emp_id").empty();
                    if (response != 0) {
                        var json = JSON.parse(response);
                        $.each(json,function(key,value){
                            var html = "<option value='"+value.id+"'>"+value.emp_id+" :: "+value.emp_name+"</option>";
                            $("#emp_id").append(html);
                        });
                    }
                },
                error: function (request, status, error) {
                    console.log(request.responseText);
                    toastr.warning( 'Server Error. Try aging!', 'Warning');
                }
            });

        }

        function salary_calculations(){
            var additional_total = Number($("#additional_total").val());

            var late_fine       = Number($("#emp_late_fine").val());
            var absent_fine     = Number($("#emp_absent_fine").val());
            var adv_salary      = Number($("#emp_advanced_salary").val());
            var others_fine     = Number($("#others_fine").val());

            var deduction_total = absent_fine+late_fine+adv_salary+others_fine;

            $("#deduction_total").val(decimal(deduction_total));

            var net_total = additional_total-deduction_total;
            $("#net_total").val(decimal(net_total));
        }

        function get_adv_salary(emp_id){
            var data = 'emp_id=' + emp_id + '&_token={{csrf_token()}}';
            $.ajax({
                type: "POST",
                url: "{{ route("get_adv_salary") }}",
                data: data,
                success: function (response) {
                    var json = JSON.parse(response);

                    json = json[0];

                    var value = Number(json.advance_amount)/Number(json.installment_time);
                    value = Math.round(value);
                    $("#emp_advanced_salary").val(value).trigger("keyup");
                    var html = '<input type="hidden" name="expense_id" value="'+json.ref_expense_id+'">' +
                            '<input type="hidden" name="installment_time" value="'+json.installment_time+'">' ;
                    $("#SalaryDistributionForm .inputs").html(html);
                },
                error: function (request, status, error) {
                    console.log(request.responseText);
                }
            });
        }

        function calculate(percent,gross){
            var value = Number(gross)*Number(percent)/100;
            return value.toFixed(2)
        }
        function decimal(num){
            var value = Number(num)*100/100;
            return value.toFixed(2)
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
    </style>
@endsection