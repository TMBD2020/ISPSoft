@extends('layouts.app')

@section('title', 'Employees')

@section('content')

    <!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title"><span id="tabOption">Employees</span></h3>
            </div>
            <div class="content-header-right col-md-8 col-12">

                <ul class="nav nav-tabs float-md-right">
                    <li class="nav-item">
                        <a class="nav-link active" onclick="document.getElementById('tabOption').innerHTML='Employees'" id="base-tab1" data-toggle="tab" aria-controls="DataList" href="#DataList" aria-expanded="true">Show @yield("title")</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link addnew" onclick="document.getElementById('tabOption').innerHTML='Add New Employees'" id="base-tab2" data-toggle="tab" aria-controls="operation" href="#operation" aria-expanded="false">Add New Employee</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="content-body"><!-- Zero configuration table -->
            <section id="configuration">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">

                                    <div class="tab-content pt-1">
                                        <div role="tabpanel" class="tab-pane active" id="DataList" aria-expanded="true" aria-labelledby="base-tab1">

                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Filter Employee</h4>
                                                </div>
                                                <div class="card-body" style="    padding-top: 0;">
                                                    <form id="empFilter" method="post">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label for="filter_department">Department</label>
                                                                    <select class="form-control select2" id="filter_department">
                                                                        <option></option>
                                                                        @foreach($department_list as $row)
                                                                            <option value="{{$row->id}}">{{$row->department_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label for="filter_designation">Designation </label>
                                                                    <select class="form-control select2" id="filter_designation">
                                                                        <option></option>
                                                                        @foreach($designation_list as $row)
                                                                            <option value="{{$row->id}}">{{$row->designation_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label for="filter_resign">Status </label>
                                                                    <select class="form-control" id="filter_resign">
                                                                        <option value="0">Active</option>
                                                                        <option value="1">Resign</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <button type="submit" class="btn btn-primary mb-0 search" style="margin-top: 25px">Search</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="datalist" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>ID/Name</th>
                                                        <th>Department</th>
                                                        <th>Designation</th>
                                                        <th>Contact</th>
                                                        <th>Joining</th>
                                                        <th class="wd-20p">Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="operation" aria-labelledby="base-tab2">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="heading-elements">
                                                        <ul class="list-inline mb-0">
                                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-body" >
                                                    <div class="col-lg-12 col-xs-12  col-md-12 col-sm-12">
                                                        <form id="DataForm" method="post" class="steps-validation">
                                                            {{csrf_field()}}

                                                            <input type="hidden" id="action" name="action">
                                                            <input type="hidden" id="employee_id" name="emp_id">
                                                            <!-- Step 1 -->
                                                            <h6>Personal Information</h6>
                                                            <fieldset>

                                                                <div class="col-md-8 col-xs-12 col-md-offset-1">
                                                                    <div class="row">
                                                                        <label for="" class="col-sm-4">Name <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"  name="emp_name" id="emp_name" required autofocus>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for=""class="col-sm-4">Father's Name <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"  name="emp_father" id="emp_father" required >
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="" class="col-sm-4">Mother's Name <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"  name="emp_mother" id="emp_mother" required >
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="" class="col-sm-4">Mobile Number <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"  name="emp_mobile" id="emp_mobile" required >
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="" class="col-sm-4">Email </label>
                                                                        <div class="col-sm-8">
                                                                            <input type="email" class="form-control"  name="emp_email" id="emp_email" >
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="" class="col-sm-4">Present Address <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <textarea class="form-control"  name="emp_present_address" id="emp_present_address" required ></textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="" class="col-sm-4">Permanent Address <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <textarea class="form-control"  name="emp_permanent_address" id="emp_permanent_address" required ></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="" class="col-sm-4">Photo</label>
                                                                        <div class="col-sm-8">
                                                                            <div id="emp_photo_frame"></div>
                                                                            <input type="file" class="form-control"  name="emp_photo" id="emp_photo"  >
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="" class="col-sm-4">Username <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" readonly name="emp_username" id="emp_username" autocomplete="off" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="" class="col-sm-4">Password <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" value="123456"  name="emp_password" id="emp_password" autocomplete="off" required >
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="" class="col-sm-4">Department <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control"  name="emp_department_id" id="emp_department_id">
                                                                                @foreach($department_list as $row)
                                                                                    <option value="{{$row->id}}">{{$row->department_name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="" class="col-sm-4">Designation <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control"  name="emp_designation_id" id="emp_designation_id">
                                                                                @foreach($designation_list as $row)
                                                                                    <option value="{{$row->id}}">{{$row->designation_name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="" class="col-sm-4">Join Date <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control text-center datepicker"   name="emp_join_date" id="emp_join_date" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="" class="col-sm-4">Status <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control"  name="is_resign" id="is_resign">
                                                                                <option value="0">Active</option>
                                                                                <option value="1">Resign</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row resign_date" style="display: none;">
                                                                        <label for="" class="col-sm-4">Resign Date <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control text-center datepicker"   name="emp_resign_date" id="emp_resign_date">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </fieldset>

                                                            <!-- Step 2 -->
                                                            <h6>Education Qualification</h6>
                                                            <fieldset>
                                                                <div class="row">
                                                                    <table class="table table-bordered educationTble">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>SL</th>
                                                                            <th>Exam Title</th>
                                                                            <th>College/University</th>
                                                                            <th>Passing Year</th>
                                                                            <th>Result</th>
                                                                            <th>Certificate</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <tr>
                                                                            <td>1</td>
                                                                            <td><input type="text" class="form-control" id="exam1" name="exam[]"/></td>
                                                                            <td><input type="text" class="form-control" id="college1" name="college[]"/></td>
                                                                            <td><input type="number" class="form-control text-center" min="0" id="passyear1" name="passyear[]"/></td>
                                                                            <td><input type="number" class="form-control text-center" min="0" id="result1" name="result[]"/></td>
                                                                            <td><input type="file" class="form-control" id="certificate_image1" name="certificate_image[]"/></td>
                                                                            <input type="hidden" id="educationId1" name="educationId[]">
                                                                        </tr>
                                                                        <tr>
                                                                            <td>2</td>
                                                                            <td><input type="text" class="form-control" id="exam2" name="exam[]"/></td>
                                                                            <td><input type="text" class="form-control" id="college2" name="college[]"/></td>
                                                                            <td><input type="number" class="form-control text-center" min="0" id="passyear2" name="passyear[]"/></td>
                                                                            <td><input type="number" class="form-control text-center" min="0" id="result2" name="result[]"/></td>
                                                                            <td><input type="file" class="form-control" id="certificate_image2" name="certificate_image[]"/></td>
                                                                            <input type="hidden" id="educationId2" name="educationId[]">
                                                                        </tr>
                                                                        <tr>
                                                                            <td>3</td>
                                                                            <td><input type="text" class="form-control" id="exam3" name="exam[]"/></td>
                                                                            <td><input type="text" class="form-control" id="college3" name="college[]"/></td>
                                                                            <td><input type="number" class="form-control text-center" min="0" id="passyear3" name="passyear[]"/></td>
                                                                            <td><input type="number" class="form-control text-center" min="0" id="result3" name="result[]"/></td>
                                                                            <td><input type="file" class="form-control" id="certificate_image3" name="certificate_image[]"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>4</td>
                                                                            <td><input type="text" class="form-control" id="exam4" name="exam[]"/></td>
                                                                            <td><input type="text" class="form-control" id="college4" name="college[]"/></td>
                                                                            <td><input type="number" class="form-control text-center" min="0" id="passyear4" name="passyear[]"/></td>
                                                                            <td><input type="number" class="form-control text-center" min="0" id="result4" name="result[]"/></td>
                                                                            <td><input type="file" class="form-control" id="certificate_image4" name="certificate_image[]"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>5</td>
                                                                            <td><input type="text" class="form-control" id="exam5" name="exam[]"/></td>
                                                                            <td><input type="text" class="form-control" id="college5" name="college[]"/></td>
                                                                            <td><input type="number" class="form-control text-center" min="0" id="passyear5" name="passyear[]"/></td>
                                                                            <td><input type="number" class="form-control text-center" min="0" id="result5" name="result[]"/></td>
                                                                            <td><input type="file" class="form-control" id="certificate_image5" name="certificate_image[]"/></td>
                                                                         </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                            </fieldset>

                                                            <!-- Step 3 -->
                                                            <h6>Employed History</h6>
                                                            <fieldset>
                                                                <div class="row">
                                                                    <table class="table table-bordered employedTbl">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>SL</th>
                                                                            <th>Previous Company</th>
                                                                            <th>Designation</th>
                                                                            <th>Join Date</th>
                                                                            <th>Resign Date</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <tr>
                                                                            <td>1</td>
                                                                            <td><input type="text" class="form-control" id="company1" name="company[]"/></td>
                                                                            <td><input type="text" class="form-control" id="designation1" name="designation[]"/></td>
                                                                            <td><input type="text" class="form-control datepicker text-center" id="joined1" name="joined[]"/></td>
                                                                            <td><input type="text" class="form-control datepicker text-center" id="resigned1" name="resigned[]"/></td>

                                                                            <input type="hidden" id="employedId1" name="employedId[]">
                                                                        </tr>
                                                                        <tr>
                                                                            <td>2</td>
                                                                            <td><input type="text" class="form-control" id="company2" name="company[]"/></td>
                                                                            <td><input type="text" class="form-control" id="designation2" name="designation[]"/></td>
                                                                            <td><input type="text" class="form-control datepicker text-center" id="joined2" name="joined[]"/></td>
                                                                            <td><input type="text" class="form-control datepicker text-center" id="resigned2" name="resigned[]"/></td>
                                                                            <input type="hidden" id="employedId2" name="employedId[]">
                                                                        </tr>
                                                                        <tr>
                                                                            <td>3</td>
                                                                            <td><input type="text" class="form-control" id="company3" name="company[]"/></td>
                                                                            <td><input type="text" class="form-control" id="designation3" name="designation[]"/></td>
                                                                            <td><input type="text" class="form-control datepicker text-center" id="joined3" name="joined[]"/></td>
                                                                            <td><input type="text" class="form-control datepicker text-center" id="resigned3" name="resigned[]"/></td>
                                                                            <input type="hidden" id="employedId3" name="employedId[]">
                                                                        </tr>
                                                                        <tr>
                                                                            <td>4</td>
                                                                            <td><input type="text" class="form-control" id="company4" name="company[]"/></td>
                                                                            <td><input type="text" class="form-control" id="designation4" name="designation[]"/></td>
                                                                            <td><input type="text" class="form-control datepicker text-center" id="joined4" name="joined[]"/></td>
                                                                            <td><input type="text" class="form-control datepicker text-center" id="resigned4" name="resigned[]"/></td>
                                                                            <input type="hidden" id="employedId4" name="employedId[]">
                                                                        </tr>
                                                                        <tr>
                                                                            <td>5</td>
                                                                            <td><input type="text" class="form-control" id="company5" name="company[]"/></td>
                                                                            <td><input type="text" class="form-control" id="designation5" name="designation[]"/></td>
                                                                            <td><input type="text" class="form-control datepicker text-center" id="joined5" name="joined[]"/></td>
                                                                            <td><input type="text" class="form-control datepicker text-center" id="resigned5" name="resigned[]"/></td>
                                                                            <input type="hidden" id="employedId5" name="employedId[]">
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                            </fieldset>

                                                            <!-- Step 4 -->
                                                            <h6>Emergency Contact</h6>
                                                            <fieldset>
                                                                <div class="col-md-8 col-xs-12">
                                                                    <div class="row">
                                                                        <label for="relative_name" class="col-sm-4">Contact Person Name<span class="danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="relative_name" name="relative_name" required/>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="relative_mobile" class="col-sm-4">Mobile Number <span class="danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="relative_mobile" name="relative_mobile" required/>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="relative_nid" class="col-sm-4">NID </label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="relative_nid" name="relative_nid"/>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label for="relative_relation" class="col-sm-4">Relation  <span class="danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="relative_relation" name="relative_relation" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="relative_present_add" class="col-sm-4">Present Address  <span class="danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="relative_present_add" name="relative_present_add" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label for="relative_permanent_add" class="col-sm-4">Permanent Address </label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="relative_permanent_add" name="relative_permanent_add"/>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                            </fieldset>


                                                            <!-- Step 5 -->
                                                            <h6>Salary Information</h6>
                                                            <fieldset>
                                                                <div class="col-md-8 col-xs-8 col-md-offset-1">
                                                                    <div class="row">
                                                                        <label for="gross" class="col-sm-5">
                                                                            Gross Salary
                                                                            <span class="danger">*</span>
                                                                        </label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" class="form-control required text-right" id="gross" name="gross" required>
                                                                        </div>
                                                                    </div>
                                                                    @php
                                                                        $available = 0;
                                                                    @endphp
                                                                    @if(count($salary_settings)>0)
                                                                        <input type="hidden" name="ck_salary_distribution" value="1">
                                                                    @else
                                                                        <input type="hidden" name="ck_salary_distribution" value="0">
                                                                    @endif
                                                                    @foreach($salary_settings as $key=>$row)
                                                                        @php
                                                                            $available += $row->percentage;
                                                                        @endphp
                                                                    <div class="row">
                                                                        <label for="basic" class="col-sm-5">
                                                                            {{ $row->title }} [{{ $row->percentage }}%]
                                                                            <span class="danger">*</span>
                                                                        </label>
                                                                        <div class="col-sm-7">
                                                                            <input type="hidden" class="form-control text-right" value="{{ $row->id }}" name="salary_id[]">
                                                                            <input type="hidden" class="form-control text-right" value="{{ $row->title }}" name="salary_title[]">
                                                                            <input type="hidden" class="form-control text-right" value="{{ $row->percentage }}" name="salary_percent[]">
                                                                            <input type="text" class="form-control text-right" id="row{{ $row->id }}" name="row{{ $row->id }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                    @php
                                                                        $available = 100-$available;
                                                                    @endphp
                                                                    @if($available!=100)
                                                                        <div class="row">
                                                                            <label for="basic" class="col-sm-5">
                                                                                Other [{{ $available }}%]
                                                                                <span class="danger">*</span>
                                                                            </label>
                                                                            <div class="col-sm-7">
                                                                                <input type="text" class="form-control text-right" id="row{{ 0 }}" name="other" readonly>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                </div>
                                                            </fieldset>


                                                        </form>
                                                    </div>
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
    .employedTbl td,.educationTble td{
        padding: 0;
    }
    .employedTbl td:first-child,.educationTble td:first-child{
        text-align: center;
    }
    form .row{
        margin-top: 10px;
    }
</style>


@endsection
@section("page_script")
    <script src="{{ asset('app-assets/vendors/js/extensions/jquery.steps.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        var table;
        filter();
        function filter(){
            table = $('#datalist').DataTable
            ({

                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [[0, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[4,5,6,7],
                    "orderable": false
                },{
                    "targets":[0,4,5,6,7],
                    className: "text-center"
                },
                    {
                        "targets": [ 0 ],
                        "visible": false,
                        "searchable": false
                    }],
                "ajax": {
                    url: "{{ route("employee_datalist") }}",
                    type: "post",
                    "data":{
                        _token          : "{{csrf_token()}}",
                        _department     : $("#filter_department").val().trim(),
                        _designation    : $("#filter_designation").val().trim(),
                        _resign         : $("#filter_resign").val().trim()
                    },
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
                        for (var i = 0, len = jsonData.data.length; i < len; i++) {

                            
                            if(jsonData.data[i][6]==1){
                                status = "<img src='{{ asset("app-assets/images/active_icon.png") }}' style='width: 20px; height: 20px;' title='Active'> ";
                            }else if(jsonData.data[i][6]==0){
                                status = "<img src='{{ asset("app-assets/images/deactive_icon.png") }}' style='width: 20px; height: 20px;' title='Deactive'> ";
                            }
                            jsonData.data[i][6] = status;
                        }

                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        //alert("error")
                    }
                }
            });

        }
        $(document).ready(function () {

            _empId();

            $(document).on('submit', '#empFilter', function (e) {

                e.preventDefault();
                $(".search").html('Searching').prop("disabled",true);
                table.destroy();
                filter();
                $(".search").html('Search').prop("disabled",false);
            });

            $(document).on('click', '.deleteData', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var data = 'id=' + del_id + '&_token={{csrf_token()}}';

                if (confirm("Are you sure you want to delete this?"))
                {
                    $.ajax({
                        type: "POST",
                        url: "{{route("employee_delete")}}",
                        data: data,
                        success: function (response) {
                            if(response == 1){
                                toastr.success('Data removed Successfully!','Success');
                                element.parents("tr").animate({backgroundColor: "#003"}, "slow").animate({opacity: "hide"}, "slow");
                            }
                            else{
                                toastr.warning( 'Data Cannot Removed. Try aging!', 'Warning');
                            }
                        },
                        error: function (request, status, error) {
                            console.log(request.responseText);
                            toastr.warning( 'Server Error. Try aging!', 'Warning');
                        }
                    });
                }
                return false;
            });

            var form = $(".steps-validation").show();
            $(".steps-validation").steps({
                headerTag: "h6",
                bodyTag: "fieldset",
                transitionEffect: "fade",
                titleTemplate: '<span class="step">#index#</span> #title#',
                labels: {
                    finish: "Submit"
                },
                onStepChanging: function(e, t, i) {
                    return i < t || !(3 === i && Number($("#age-2").val()) < 18)
                            && (t < i && (form.find(".body:eq(" + i + ") label.error").remove(),
                                    form.find(".body:eq(" + i + ") .error").removeClass("error")),
                                    form.validate().settings.ignore = ":disabled,:hidden",
                                    form.valid())
                },
                onFinishing: function(e, t) {
                    return form.validate().settings.ignore = ":disabled", form.valid()
                },
                onFinished: function(e, t) {
                    console.log("Submitted!");
                    var element  = $("a[href='#finish']");
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: "{{ route("save_employee") }}",
                        data: new FormData(document.getElementById("DataForm")),
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function (response)
                        {
                            //console.log(response);
                            if (response == 1) {
                                $("#DataForm").trigger("reset");
                                toastr.success('Data Saved Successfully!','Success');
                                $("[href='#DataList']").tab("show");
                                table.ajax.reload();
                            }
                            else {
                                toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                            }

                            $(element).text("Submit").prop("disabled", false);
                        },
                        error: function (request, status, error) {
                            console.log(request.responseText);
                            toastr.warning( 'Server Error. Try aging!', 'Warning');
                            $(element).text("Submit").prop("disabled", false);
                        }
                    });
                }
            }), $(".steps-validation").validate({
                ignore: "input[type=hidden]",
                errorClass: "danger",
                successClass: "success",
                highlight: function(e, t) {
                    $(e).removeClass(t)
                },
                unhighlight: function(e, t) {
                    $(e).removeClass(t)
                },
                errorPlacement: function(e, t) {
                    e.insertAfter(t)
                },
                rules: {
                    email: {
                        email: !0
                    }
                }
            });


            $(document).on('change', '#is_resign', function () {
               if($(this).val()==1){
                   $(".resign_date").show();
                   $("#emp_resign_date").attr("required","required");
               }else{
                   $(".resign_date").hide();
                   $("#emp_resign_date").val("").removeAttr("required","required");
               }
            });

            $(document).on('click', '.addnew', function () {
                $("#action").val(1);
                $("#employee_id").val("");
                $(".operation_type").text("Add New");
                $("#DataForm").trigger("reset");
                $("#emp_photo_frame").html("");
                _empId();
            });

            $(document).on('click', '.update', function () {
                var element = $(this);
                var del_id = element.attr("id");
                var info = 'id=' + del_id +"&_token={{csrf_token()}}";
                $(".edit"+del_id).html('<i class="ft-loader"></i>').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{route('employee_update')}}",
                    data: info,
                    success: function (response) {
                        $(".edit"+del_id).html('Edit').prop("disabled",false);
                        $("#tabOption").html('Update Employee');
                        if(response!=0){
                            $(".operation_type").text("Update");
                            $("[href='#operation']").tab("show");
                            $(".steps").find("li").addClass("done").removeClass("disabled");
                            //$(".steps li:first-child").addClass("current").removeClass("done")
                              //  .find("a").append("<span class=\"current-info audible\">current step: </span>");

                            var json = (response);
                            var emp_data = json.emp_data;
                            var emp_salary = json.emp_salary;

                            $("#action").val(2);
                            $("#employee_id").val(emp_data.id);
                            $("#emp_username").val(emp_data.emp_id);
                            $("#emp_password").val("").removeAttr("required");
                            $("#emp_name").val(emp_data.emp_name);
                            $("#emp_father").val(emp_data.emp_father);
                            $("#emp_mother").val(emp_data.emp_mother);
                            $("#emp_mobile").val(emp_data.emp_mobile);
                            $("#emp_email").val(emp_data.emp_email);
                            $("#emp_present_address").val(emp_data.emp_present_address);
                            $("#emp_permanent_address").val(emp_data.emp_permanent_address);
                            $("#emp_join_date").val(emp_data.emp_join_date);
                            $("#emp_resign_date").val(emp_data.emp_resign_date);
                            $('#emp_designation_id').val(emp_data.emp_designation_id).trigger('change');
                            $('#emp_department_id').val(emp_data.emp_department_id).trigger('change');
                            $('#is_resign').val(emp_data.is_resign).trigger('change');

                            if(emp_data.emp_photo){
                                $('#emp_photo_frame').html("<img src='"+emp_data.emp_photo+"' width='150' height='150'>");
                            }

                            $('#relative_name').val(emp_data.relative_name);
                            $('#relative_mobile').val(emp_data.relative_mobile);
                            $('#relative_nid').val(emp_data.relative_nid);
                            $('#relative_relation').val(emp_data.relative_relation);
                            $('#relative_present_add').val(emp_data.relative_present_add);
                            $('#relative_permanent_add').val(emp_data.relative_permanent_add);

                        

                            if(emp_data.educational_qualification){
                                var educational_qualification = emp_data.educational_qualification.split("^");
                                for(var j=0;j<educational_qualification.length;j++){
                                    var track = j+1;
                                    var dt = educational_qualification[j].split("+");
                                    $("#exam"+track).val(dt[0]);
                                    $("#college"+track).val(dt[1]);
                                    $("#passyear"+track).val(dt[2]);
                                    $("#result"+track).val(dt[3]);
                                }
                            }

                            if(emp_data.employed_history){
                                var employed_history = emp_data.employed_history.split("^");
                                for(var k=0;k<employed_history.length;k++){
                                    var key = k+1;
                                    var dd = employed_history[k].split("+");
                                    $("#company"+key).val(dd[0]);
                                    $("#designation"+key).val(dd[1]);
                                    $("#joined"+key).val(dd[2]);
                                    $("#resigned"+key).val(dd[3]);
                                }
                            }

                            $('#gross').val(emp_salary.gross).trigger("change");

                            var sal = emp_salary.salary.split(",");

                            if(sal.length>0){
                                for(var i=0; i<sal.length;i++){
                                var rows = sal[i];
                                var abrows = rows.split("|");
                                    $("#row"+abrows[0]).val(abrows[3]);
                                }
                            }else{
                                $("#row0").val(emp_salary.gross);
                            }
                        }else{
                            toastr.warning( 'Server Error. Try aging!', 'Warning');
                        }

                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(".edit"+del_id).html('Edit').prop("disabled",false);
                    }
                });
            });



            $(document).on('input keyup', '#gross', function () {
                salary();
            });

        });

        function salary(){
            var gross = Number($("#gross").val().trim());

            @foreach($salary_settings as $row)
                var row = gross*{{ $row->percentage }}/100;
                $("#row{{ $row->id }}").val(decimal(row));
            @endforeach
            @if($available>0)
            var rows = gross*{{ $available }}/100;
            $("#row0").val(decimal(rows));
            @endif
        }
        function decimal(num){
            var value = num*100/100;
            return value.toFixed(2)
        }

        function _empId(){
            var info = "_token={{csrf_token()}}";
            $.ajax({
                type: "POST",
                url: "{{ route("next_emp_id") }}",
                data: info,
                success: function (response) {
                    if(response!=0){
                        $("#emp_username").val(response);
                    }else{
                        toastr.warning( 'Failed to fetch employee username. Try aging!', 'Warning');
                    }
                },
                error: function (request, status, error) {
                    console.log(request.responseText);
                }
            });
        }
    </script>

    <style>
        .card.inner-card {
            box-shadow: none !important;
            border: 1px solid  rgba(222, 223, 241,0.3)  !important;
        }
    </style>

@endsection
