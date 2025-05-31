@extends('layouts.app')

@section('title', 'SMS')

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-2 col-12 mb-2">
                <h3 class="content-header-title" id="tabOption">@yield("title")</h3>
            </div>
            <div class="content-header-right col-md-10 col-12">
                <ul class="nav nav-tabs float-md-right">
				   <li class="nav-item">
                        <a  class="nav-link @if($type=='') active @endif" id="base-tab3" data-toggle="tab" aria-controls="generalSMS" href="#generalSMS" aria-expanded="true">General SMS</a>
                    </li>
                    <li class="nav-item">
                        <a  class="nav-link @if($type=='isp') active @endif" id="base-tab1" data-toggle="tab" aria-controls="DataList" href="#ispsms" aria-expanded="true">ISP SMS</a>
                    </li>
                    <li class="nav-item">
                        <a   class="nav-link @if($type=='catv') active @endif" id="base-tab2" data-toggle="tab" aria-controls="catvsms" href="#catvsms" aria-expanded="false">CATV SMS</a>
                    </li>
                </ul>

            </div>
        </div>
        <div class="content-body">
            <section id="configuration">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">
                                    <div class="tab-content pt-1">
<div role="tabpanel" class="tab-pane @if($type=='') active @endif" id="generalSMS" aria-expanded="true" aria-labelledby="base-tab3">

                                            <form id="myGenerelSms" method="post" >
                                                        {{ csrf_field() }}

                                                  <div class="card">
                                                      <div class="card-body">
                                                          <div class="row">
                                                              <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">

                                                                  <div class="">

                                                                      <div class="row">

                                                                          <div class="col-sm-12">
                                                                              <label for="sms_text" class="control-label pull-left">SMS Recipient<code>*</code></label>
                                                                              <textarea class="form-control" rows="2" name="sms_receiver" placeholder="018xxxxxxxx,017xxxxxxxx" required></textarea>
                                                                          </div>
                                                                      </div>
                                                                      <div class="row">

                                                                          <div class="col-sm-12">
                                                                              <label for="sms_text" class="control-label pull-left">Message<code>*</code>
                                                                                  <button class="btn btn-info" id="chooseTemplate" type="button" style="    padding: 2px;   font-size: 11px;">Choose from template</button></label>

                                                                              <label class="sms_count badge badge-warning" style="font-size: 11px;float: right;">0</label>
                                                                              <textarea autofocus class="form-control" rows="10" name="sms_text" id="sms_text" required>@if(Session::get('sms_text')){{ Session::get('sms_text') }}@endif</textarea>
                                                                          </div>
                                                                      </div>
                                                                  </div>
                                                              </div>

                                                          </div>
                                                      </div>
                                                      <div class="card-footer">
                                                          <div class="col-lg-6 col-xs-12 col-md-12 col-sm-12">
                                                              <button type="submit" class="btn btn-primary mt-1 mb-0 pull-right">Send</button>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </form>
                                        </div>
                                        
                                        <div role="tabpanel" class="tab-pane @if($type=='isp') active @endif" id="ispsms" aria-expanded="true" aria-labelledby="base-tab1">

                                            <form id="" method="post" action="{{ route('sms_preview') }}">
                                                        {{ csrf_field() }}

                                                <input type="hidden" name="col_index" id="col_index">
                                                <input type="hidden" value="isp" name="sms_panel">
                                                  <div class="row">
                                                      <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">

                                                          <input type="hidden" class="action" name="action">

                                                          <div class="row">
                                                              <label for="pop_id"
                                                                     class="col-sm-4 control-label">POP </label>

                                                              <div class="col-sm-8">
                                                                  <select class="form-control select2" name="pop_id[]" id="pop_id"  multiple="multiple">
                                                                      @foreach($pops as $row)
                                                                          <option value="{{ $row->id }}" @if(Session::get('pop_id')) @if(in_array($row->id,Session::get('pop_id'))) selected @endif @endif>{{ $row->pop_name }}
                                                                          </option>
                                                                      @endforeach
                                                                  </select>
                                                              </div>
                                                          </div>

                                                          <div class="row">
                                                              <label for="zone_id"
                                                                     class="col-sm-4 control-label">Zone
                                                                  </label>

                                                              <div class="col-sm-8">
                                                                  <select class="form-control select2" name="zone_id[]" id="zone_id" multiple="multiple">
                                                                      @foreach($isp_zones as $row)
                                                                        <option value="{{ $row->id }}" @if(Session::get('zone_id')) @if(in_array($row->id,Session::get('zone_id'))) selected @endif @endif>{{ $row->zone_name_en }}</option>
                                                                      @endforeach
                                                                  </select>
                                                              </div>
                                                          </div>

                                                          <div class="row">
                                                              <label for="node_id"
                                                                     class="col-sm-4 control-label">Node </label>

                                                              <div class="col-sm-8">
                                                                  <select class="form-control select2"
                                                                          name="node_id[]" id="node_id" multiple="multiple"
                                                                          >
                                                                      @foreach($nodes as $row)
                                                                          <option value="{{ $row->id }}"  @if(Session::get('node_id')) @if(in_array($row->id,Session::get('node_id'))) selected @endif @endif>{{ $row->node_name }}</option>
                                                                      @endforeach
                                                                  </select>
                                                              </div>
                                                          </div>

                                                          <div class="row">
                                                              <label for="box_id"
                                                                     class="col-sm-4 control-label">Box </label>

                                                              <div class="col-sm-8">
                                                                  <select class="form-control select2"
                                                                          name="box_id[]" id="box_id"  multiple="multiple">

                                                                      @foreach($boxes as $row)
                                                                          <option value="{{ $row->id }}" @if(Session::get('box_id')) @if(in_array($row->id,Session::get('box_id'))) selected @endif @endif>{{ $row->box_name }}</option>
                                                                      @endforeach
                                                                  </select>
                                                              </div>
                                                          </div>


                                                          <div class="row">
                                                              <label for="package_id"
                                                                     class="col-sm-4 control-label">Package
                                                                  </label>

                                                              <div class="col-sm-8">
                                                                  <select class="form-control select2" multiple="multiple"
                                                                          name="package_id[]" id="package_id"
                                                                          >

                                                                      @foreach($packages as $row)
                                                                          <option value="{{ $row->id }}"
                                                                                  data="{{ $row->package_price }}"  @if(Session::get('package_id')) @if(in_array($row->id,Session::get('package_id'))) selected @endif @endif>{{ $row->package_name }}
                                                                              [D: {{ $row->download }},
                                                                              U: {{ $row->upload }}
                                                                              ,Y: {{ $row->youtube }}]
                                                                              [? {{ $row->package_price }}]
                                                                          </option>
                                                                      @endforeach
                                                                  </select>
                                                              </div>
                                                          </div>
                                                          <div class="row">
                                                              <label for="" class="col-sm-4 control-label">Client Status</label>
                                                              <div class="col-sm-8">
                                                                  <input type="radio" value="'1'"  name="client_status" id=""  @if(empty(Session::get('client_status'))) checked @endif @if(Session::get('sms_from')=="'1'") checked @endif> Active
                                                                  <input type="radio" value="'0'"  name="client_status" id="" @if(Session::get('client_status')=="'0'") checked @endif> Inactive
                                                                  <input type="radio" value="'0','1'"  name="client_status" id="" @if(Session::get('client_status')=="'0','1'") checked @endif> Both
                                                              </div>
                                                          </div>

                                                          <div class="row">
                                                              <label for="sms_from"
                                                                     class="col-sm-4 control-label">Subject
                                                              </label>

                                                              <div class="col-sm-8">
                                                                  <select class="form-control" name="sms_from" id="sms_from">
                                                                      <option value="Due Bill"  @if(Session::get('sms_from')) @if(in_array($row->id,Session::get('sms_from'))) selected @endif @endif>Due Bill</option>

                                                                  </select>
                                                              </div>
                                                          </div>
                                                          <div class="row">
                                                              <label for="clients"
                                                                     class="col-sm-4 control-label">Clients
                                                              </label>

                                                              <div class="col-sm-8">
                                                                  <select class="form-control select2" name="clients" id="clients" >
                                                                      <option value="0">All</option>

                                                                  </select>
                                                              </div>
                                                              <input type="hidden" value="{{ Session::get('clients') }}" id="old_clients">
                                                          </div>
                                                          <input type="hidden" class="datetimepicker form-control" value="{{ date("d/m/Y H:i") }}" name="schedule_time" id="schedule_time">
                                                          <div class="row">
                                                              <label for="schedule_time"
                                                                     class="col-sm-4 control-label">Filter By
                                                              </label>

                                                              <div class="col-sm-8">
                                                                <select class="form-control"  id="filter_by" name="filter_by">
                                                                    <option value="">N/A</option>
                                                                    <option value="1">Commitment Date</option>
                                                                    <option value="2">Payment Date</option>
                                                                    <option value="3">Billing Date</option>
                                                                </select>
                                                              </div>
                                                          </div>
                                                          <div class="row">
                                                              <label for="schedule_time"
                                                                     class="col-sm-4 control-label">Filter Date
                                                              </label>

                                                              <div class="col-sm-8">
                                                                <div class="input-group">
                                                                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ Session::get('date_from') }}" required>
                                                                <div class="input-group-addon">-</div>
                                                                <input type="date" name="date_to"
                                                                id="date_to" class="form-control "
                                                                value="{{ Session::get('date_to') }}" required>
                                                              </div>
                                                              </div>
                                                          </div>

                                                      </div>
                                                      <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">

                                                          <div class="">

                                                              <div class="row">

                                                                  <div class="col-sm-12">
                                                                      <label for="sms_text" class="control-label pull-left">SMS Text<code>*</code>
                                                                          <button class="btn btn-info" id="chooseTemplate" type="button" style="    padding: 2px;   font-size: 11px;">Choose from template</button></label>

                                                                      <textarea autofocus class="form-control" rows="10" name="sms_text" id="sms_text_isp" >@if(Session::get('sms_text')){{ Session::get('sms_text') }}@endif</textarea>
                                                                      <table style="width: 100%;" id="sms-counter">
                                                                          <tr>
                                                                              <td>Encoding: <span class="encoding"></span></td>
                                                                              <td>Length: <span class="length"></span></td>
                                                                              <td>Messages: <span class="messages"></span></td>
                                                                          </tr>
                                                                          <tr>
                                                                              <td>Per Message: <span class="per_message"></span></td>
                                                                              <td>Remaining: <span class="remaining"></span></td>
                                                                          </tr>
                                                                      </table>

                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12">
                                                          <button type="submit" class="btn btn-primary mt-1 mb-0 pull-right">Send</button>
                                                      </div>

                                                  </div>
                                              </form>
                                        </div>
                                        <div role="tabpanel" class="tab-pane @if($type=='catv') active @endif" id="catvsms" aria-expanded="true" aria-labelledby="base-tab1">

                                            <form id="" method="post" action="{{ route('sms_preview') }}">
                                                        {{ csrf_field() }}
                                                <input type="hidden" value="catv" name="sms_panel">
                                                  <div class="row">
                                                      <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">

                                                          <input type="hidden" class="action" name="action">

                                                          <div class="row">
                                                              <label for="cat_zone_id" class="col-sm-4 control-label">Zone</label>
                                                              <div class="col-sm-8">
                                                                  <select class="form-control catv_zone_id select2" name="zone_id[]">
                                                                      <option value="">Choose Zone</option>
                                                                      @foreach($catv_zones as $row)
                                                                          <option value="{{ $row->id }}" @if(Session::get('zone_id')) @if(in_array($row->id,Session::get('zone_id'))) selected @endif @endif>{{ $row->zone_name_en }}</option>
                                                                      @endforeach
                                                                  </select>
                                                              </div>
                                                          </div>
                                                          <div class="row">
                                                              <label for="cat_sub_zone_id" class="col-sm-4 control-label">Sub Zone</label>
                                                              <div class="col-sm-8">
                                                                  <select class="form-control catv_sub_zone_id select2" name="sub_zone_id">
                                                                      <option value="">Choose Sub Zone</option>
                                                                  </select>
                                                              </div>
                                                          </div>

                                                          <div class="row">
                                                              <label for="" class="col-sm-4 control-label">Client Status</label>
                                                              <div class="col-sm-8">
                                                                  <input type="radio" value="'1'"  name="catv_client_status"  @if(Session::get('sms_from')=="'1'") checked @endif> Active
                                                                  <input type="radio" value="'0'"  name="catv_client_status" @if(Session::get('client_status')=="'0'") checked @endif> Inactive
                                                                  <input type="radio" value="'0','1'"  name="catv_client_status" @if(Session::get('client_status')=="'0','1'") checked @endif  @if(empty(Session::get('client_status'))) checked @endif> Both
                                                              </div>
                                                          </div>

                                                          <div class="row">
                                                              <label for="sms_from"
                                                                     class="col-sm-4 control-label">Subject
                                                              </label>

                                                              <div class="col-sm-8">
                                                                  <select class="form-control sms_from"
                                                                          name="sms_from"
                                                                          >
                                                                      <option value="Due Bill"  @if(Session::get('sms_from')) @if(in_array($row->id,Session::get('sms_from'))) selected @endif @endif>Due Bill</option>

                                                                  </select>
                                                              </div>
                                                          </div>
                                                          <div class="row">
                                                              <label for="clients"
                                                                     class="col-sm-4 control-label">Clients
                                                              </label>

                                                              <div class="col-sm-8">
                                                                  <select class="form-control select2 catv_clients" name="clients" >
                                                                      <option value="0">All</option>

                                                                  </select>
                                                              </div>
                                                              <input type="hidden" value="{{ Session::get('clients') }}" class="old_catv_clients">
                                                          </div>
                                                          <div class="row">
                                                              <label for="schedule_time"
                                                                     class="col-sm-4 control-label">Schedule Time
                                                              </label>

                                                              <div class="col-sm-8">
                                                                 <input type="text" class="datetimepicker form-control schedule_time" value="{{ date("d/m/Y H:i") }}" name="schedule_time">
                                                              </div>
                                                          </div>

                                                      </div>
                                                      <div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">

                                                          <div class="">

                                                              <div class="row">

                                                                  <div class="col-sm-12">
                                                                      <label for="sms_text" class="control-label pull-left">SMS Text<code>*</code>
                                                                          <button class="btn btn-info chooseCatvTemplate" id="" type="button" style="    padding: 2px;   font-size: 11px;">Choose from template</button></label>

                                                                      <label class="sms_count badge badge-warning" style="font-size: 11px;float: right;">0</label>
                                                                      <textarea autofocus class="form-control catv_sms_text" rows="10" name="sms_text" >@if(Session::get('sms_text')){{ Session::get('sms_text') }}@endif</textarea>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12">
                                                          <button type="submit" class="btn btn-primary mt-1 mb-0 pull-right">Send</button>
                                                      </div>

                                                  </div>
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


<div class="modal fade text-left" id="sms_template_list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white" id="myModalLabel8">SMS Templates</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="col-md-12">

                    <div class="row " >

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class='text-center'>Sl. No.</th>
                                <th class='text-center'>Template Name</th>
                                <th class='text-center'>SMS Text</th>
                                <th class='text-center'>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($sms_templates as $key=>$row)
                                <tr>
                                    <td class='text-center'>{{ $key+1 }}</td>
                                    <td class='text-center'>{{ $row->template_name }}</td>
                                    <td class='text-center' >{{ $row->template_text }}</td>
                                    <td class='text-center'><button id="choose_sms_template" text="{{ $row->template_text }}" style="    padding: 5px;   font-size: 12px;" class="btn btn-success" type="button">Choose</button></td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<style>
    #sms-counter span{
        font-weight: bold;}
    form .row{
        margin-top: 10px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
        list-style: none;
        font-size: 11px;
    }
</style>
@endsection
@section("page_script")
    <script src="{{ asset('app-assets/js/sms_counter.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#sms_text_isp').countSms('#sms-counter')
            var subzones =  @json($subzones);
            _clients();
            $(document).on('click', ".nav-link", function () {
                if($(this).attr('aria-controls')=='generalSMS'){
                    document.getElementById('tabOption').innerHTML='General';
                    window.history.replaceState(null, null, '/client/send-sms');
                } else if($(this).attr('aria-controls')=='DataList'){
                    document.getElementById('tabOption').innerHTML='ISP SMS';
                    window.history.replaceState(null, null, '/client/send-sms/isp');
                }
                else{
                    document.getElementById('tabOption').innerHTML='CATV SMS';
                    window.history.replaceState(null, null, '/client/send-sms/catv');
                }
                return false;

            });
            $(document).on('change', ".catv_zone_id", function () {

                var html ='<option value="all">All</option>';
                var zone = $(this).val();
                $(".catv_sub_zone_id").empty();
                $.each(subzones,function(key,value){
                    if(value.ref_zone_id==zone){
                        html+='<option value="'+value.id+'">'+value.sub_zone_name+'</option>';
                    }
                });
                $(".catv_sub_zone_id").html(html);
                _clients();
            });
            $(document).on('change', "#pop_id", function () {
                _zone($(this));
                _clients();
            });

            $(document).on('change', ".catv_sub_zone_id", function () {
                _clients();
            });
            $(document).on('change', "input[name='catv_client_status']:checked", function () {
                _clients();
            });
            $(document).on('change input keyup focus blur', "#sms_text", function () {
               var txtCount = $(this).val().trim().length;
                $(".sms_count").html(txtCount);
            });
            $(document).on('click', "#chooseTemplate", function () {
                $("#sms_template_list").modal("show");
            });
            $(document).on('click', "#choose_sms_template", function () {
                var sms = $(this).attr("text");
                $("#sms_template_list").modal("hide");
                $(".tab-pane.active").find("#sms_text_isp").val(sms).focus();
                $('#sms_text_isp').countSms('#sms-counter')
            });

            $(document).on('submit', "#myGenerelSms", function (e) {
            e.preventDefault();

            $("#myGenerelSms button[type='submit']").html("Sending...").prop("disabled", true);
            $.ajax({
                type: "POST",
                url: "{{ route('send_general_sms') }}",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                success: function (response)
                {
                    //console.log(response);
                    var json=JSON.parse(response);
                    if (json.status == true) {
                        $("#myGenerelSms").trigger("reset");
                        toastr.success('SMS successfully send','Success');
                    }
                    else {
                        toastr.warning( 'SMS send failed. Try again!', 'Warning');
                    }
                    $("#myGenerelSms button[type='submit']").html("Send").prop("disabled", false);
                },
                error: function (request, status, error) {
                    console.log(request.responseText);
                    toastr.warning( 'Server Error. Try again!', 'Warning');
                    $("#myGenerelSms button[type='submit']").html("Send").prop("disabled", false);
                }
            });
        });



        });


        function _clients() {
            $("#toast-container").remove();
            var pop_id = $("#pop_id").val();
            var zone_id = $("#zone_id").val();
            var node_id = $("#node_id").val();
            var box_id = $("#box_id").val();
            var package_id = $("#package_id").val();
            var client_status = '';
            var old_clients = $("#old_clients").val();
            var panel;
            var subzone='';
            if($("#catvsms").hasClass("active")){
                panel="catv";
                subzone=$(".catv_sub_zone_id").val();
                zone_id=$(".catv_zone_id").val();
                old_clients=$(".old_catv_clients").val();
                client_status=$("input[name='catv_client_status']:checked").val()

            } else{
                subzone='';
                panel="isp";
                client_status=$("input[name='client_status']:checked").val()
            }
            var info =
                    'pop_id=' + pop_id +
                    '&zone_id=' + zone_id +
                    '&subzone_id=' + subzone +
                    '&node_id=' + node_id +
                    '&box_id=' + box_id +
                    '&package_id=' + package_id +
                    '&client_status=' + client_status +
                    '&panel=' + panel +
                    "&_token={{csrf_token()}}";
            //console.log(id.length)
                $.ajax({
                    type: "POST",
                    url: "{{ route('sms_client_list') }}",
                    data: info,
                    success: function (response) {
                        //console.log(response)
                        $("#clients").empty();
                        $(".catv_clients").empty();
                        if (response != 0) {
                            var json = JSON.parse(response);
                            var selected = "";
                            var html = "<option value='0'  "+selected+">All</option>";
                            $.each(json, function (key, value) {
                                html += "<option value='" + value.id + "' "+selected+">" + value.client_id + " :: " + value.client_name + "</option>";
                            });
                            if(panel=="isp"){
                                $("#clients").html(html);
                            }else{

                                $(".catv_clients").html(html);
                            }
                        } else {
                            toastr.warning('No client found!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                    }
                });
        }

        function _zone(data) {
            var id = data.val();
            var info = 'id=' + id + "&_token={{csrf_token()}}";
            //console.log(id.length)
            if(id.length>0){
                $.ajax({
                    type: "POST",
                    url: "{{ route('zone_by_pop') }}",
                    data: info,
                    success: function (response) {
                        //console.log(response)
                        if (response != 0) {
                            var json = JSON.parse(response);
                            $("#zone_id").empty();
                            var html = "";
                            $.each(json, function (key, value) {
                                html += "<option value='" + value.id + "'>" + value.zone_name_en + "</option>";
                            });
                            $("#zone_id").html(html);
                            _node($('#zone_id'));
                        } else {
                            toastr.warning('Failed to fetch zone list. Try aging!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                    }
                });
            }

        }

        function _node(data) {
            var id = data.val();
            var info = 'id=' + id + "&_token={{csrf_token()}}";
            if(id.length>0){
                $.ajax({
                    type: "POST",
                    url: "{{ route('node_by_zone') }}",
                    data: info,
                    success: function (response) {
                        // console.log(response)
                        if (response != 0) {
                            var json = JSON.parse(response);
                            $("#node_id").empty();
                            var html = "";
                            $.each(json, function (key, value) {
                                html += "<option value='" + value.id + "'>" + value.node_name + "</option>";
                            });
                            $("#node_id").html(html);
                            _box($("#node_id"));
                        } else {
                            toastr.warning('Failde to fetch node list. Try aging!', 'Warning');
                        }
                    }
                    ,
                    error: function (request, status, error) {
                        console.log(request.responseText);
                    }
                });
            }
        }

        function _box(data) {
            var id = data.val();
            var info = 'id=' + id + "&_token={{csrf_token()}}";
            if(id.length>0){
                $.ajax({
                    type: "POST",
                    url: "{{ route('box_by_node') }}",
                    data: info,
                    success: function (response) {
                        // console.log(response)
                        if (response != 0) {
                            var json = JSON.parse(response);
                            $("#box_id").empty();
                            var html = "";
                            $.each(json, function (key, value) {
                                html += "<option value='" + value.id + "'>" + value.box_name + "</option>";
                            });
                            $("#box_id").html(html);
                        } else {
                            toastr.warning('Failed to fetch box list. Try aging!', 'Warning');
                        }
                    }
                    ,
                    error: function (request, status, error) {
                        console.log(request.responseText);
                    }
                });
            }
        }


        function decimal(num){
            var value = num/100*100;
            return value.toFixed(2);
        }
        function dateFormat(date){
            date = date.split("-");
            date = date[2]+"/"+date[1]+"/"+date[0];
            return date;
        }
    </script>
<script src="{{ asset("app-assets/js/sms.js") }}"></script>
@endsection
