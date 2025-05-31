@extends('layouts.app')

@section('title', 'CATV Clients')

@section('content')
<style>
	h4 span {
	padding-right: 10px;
	}
</style>
<!-- BEGIN: Content-->
<div class="app-content content">
	<div class="content-wrapper">
		<div class="content-wrapper-before"></div>
		<div class="content-header row">
			<div class="content-header-left col-md-4 col-12 mb-2">
				<h3 class="content-header-title" id="tabOption">@yield("title")</h3>
			</div>
			<div class="content-header-right col-md-8 col-12">
				
				<ul class="nav nav-tabs float-md-right">
					<li class="nav-item">
						<a onclick="document.getElementById('tabOption').innerHTML='Clients'"
						class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="DataList"
						href="#DataList" aria-expanded="true">Show @yield("title")</a>
					</li>
					<li class="nav-item">
						<a onclick="document.getElementById('tabOption').innerHTML='Add New Client'"
						class="nav-link addnew" id="base-tab2" data-toggle="tab" aria-controls="operation"
						href="#operation" aria-expanded="false">Add New</a>
					</li>
					<li class="nav-item">
						<a onclick="document.getElementById('tabOption').innerHTML='Client Import/Export'"
						class="nav-link" id="base-tab3" data-toggle="tab" aria-controls="importExport"
						href="#importExport" aria-expanded="false">Import/Export</a>
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
										<div role="tabpanel " class="tab-pane active" id="DataList"
										aria-expanded="true" aria-labelledby="base-tab1">
											
											
											<div class="text-center client_summery">
												<h4>
													<span>Total: <i
													class="text-primary total">{{ $total_client }}</i></span>
													<span>Active: <i
													class="text-success actived">{{ $active_client }}</i></span>
													<span>Inactive: <i
													class="text-danger inactived">{{ $inactive_client }}</i></span>
													{{--<span>Lock: <i--}}
													{{--class="text-warning locked">{{ $locked_client }}</i></span>--}}
												</h4>
											</div>
											<div style="clear:both; overflow: hidden;padding-right: 15px;">
												<div class="pull-right" >
													<label for="filter_zone">Filter:</label>
													<select id="filter_zone" onchange="clientData()">
														<option value="all">All Zone</option>
														@foreach($zones as $zone)
														<option value="{{ $zone->id }}">{{ $zone->zone_name_en }}</option>
														@endforeach
													</select>
													<select id="filter_status" onchange="clientData()">
														<option value="all">All</option>
														<option value="1">Active</option>
														<option value="0">Inactive</option>
														{{--<option value="0">Locked</option>--}}
													</select>
												</div>
											</div>
											
											
											<div class="">
												<table id="datalist"
												class="table table-striped table-bordered table-hovered"
												style="width: 100%;">
													<thead>
                                                        <tr>
                                                            <th >SL</th>
                                                            <th >ID/Name/Cell</th>
                                                            <th >Card No</th>
                                                            <th >Zone/Address</th>
                                                            <th >MRP</th>
                                                            <th >Payment<br>Deadline</th>
                                                            <th >Status</th>
                                                            <th >Action</th>
														</tr>
													</thead>
													<tbody>
														
													</tbody>
												</table>
											</div>
											
										</div>
										<div role="tabpanel" class="tab-pane" id="importExport" aria-expanded="true" aria-labelledby="base-tab3">





											<div class="text-center">
												<h3>Import Excel File</h3>

												<a href="{{ asset("app-assets/files/catv_client.xlsx") }}" download="catv station"><i class="ft-download"></i> Excel Demo Download</a>
												<br>
												<br>
												<form action="{{ route('catv-client-import') }}" method="POST" enctype="multipart/form-data">
													@csrf
													<div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
														<img class="excel_img" style="display: none;width: 50px;float: left;" src="{{ asset("app-assets/images/excel-icon.png") }}">
														<span class="fileName" style="float: left;"></span>

														<div class="custom-file text-left">
															<input  onchange="return Validate(this)" type="file" id="file" name="file" class="custom-file-input" required="" autocomplete="off">
															<label  class="custom-file-label" for="customFile">Choose file</label>
														</div>
													</div>

													<button class="btn btn-primary">Import data</button>
												</form>
											</div>

										</div>
										<div class="tab-pane" id="operation" aria-labelledby="base-tab2">
											<div class="card" style="margin-bottom: 0;">
												<div class="card-body" style="padding:0;">
													<form id="DataForm" method="post" class="form-horizontal">
														{{ csrf_field() }}
														
														<input type="hidden" id="action" name="action">
														<input type="hidden" id="id" name="id">
														
														
														<div class="card" style="margin-bottom: 0;">
															<div class="card-body" style="    border-bottom: 1px solid #ddd;">
																<h4 class="card-title" style="    margin: 0;">Basic Information</h4>
															</div>
															<div class="card-body" style="padding-top: 0; padding-bottom:0;">
																<div class="row">
																	<div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
																		
																		<div class="row">
																			<label for="zone_id" class="col-sm-4 control-label">Zone <span class="text-danger">*</span></label>
																			
																			<div class="col-sm-8">
																				<div class="input-group">
																					<select class="form-control select2-group" name="zone_id" id="zone_id" required>
																						<option>Select Zone</option>
																						@foreach($zones as $zone)
																						<option value="{{ $zone->id }}">{{ $zone->zone_name_en }}</option>
																						@endforeach
																					</select>
																					<div class="input-group-addon btn-primary ft-plus addZone"></div>
																				</div>
																			</div>
																		</div>
																		<div class="row">
																			<label for="sub_zone_id"
																			class="col-sm-4 control-label">Sub Zone
																			<span class="text-danger">*</span></label>
																			
																			<div class="col-sm-8">
																				<div class="input-group">
																					<select class="form-control select2-group"
																					name="sub_zone_id" id="sub_zone_id">
																						<option>Select Sub Zone</option>
																					</select>
																					<div class="input-group-addon btn-primary ft-plus addSubZone"></div>
																				</div>
																			</div>
																		</div>
																		
																		<div class="row">
																			<label for="client_name"
																			class="col-sm-4 control-label">Client Name <span  class="text-danger">*</span></label>
																			<div class="col-sm-8">
																				<input type="text" autocomplete="off"
																				name="client_name" id="client_name"
																				class="form-control" required>
																			</div>
																		</div>
																		
																		<div class="row">
																			<label for="client_username"
																			class="col-sm-4 control-label">Client ID
																			<span class="text-danger">*</span></label>
																			<div class="col-sm-8">
																				<div class="input-group">
																					<input type="text" autocomplete="off"
																					name="client_username"
																					id="client_username"
																					class="form-control" required>
																					
																					<select id="id_prefix" name="prefix_id" onchange="_clientId()">
																						@foreach($id_prefixs as $prefix)
																						<option value="{{ $prefix->id }}">{{ $prefix->id_prefix_name }}</option>
																						@endforeach
																					</select>
																				</div>
																			</div>
																		</div>
																		
																		<div class="row">
																			<label for="client_password"
																			class="col-sm-4 control-label">Password<span
																			class="text-danger">*</span></label>
																			
																			<div class="col-sm-8">
																				<input type="text" autocomplete="off"
																				value="123123" name="client_password"
																				id="client_password"
																				class="form-control" required>
																			</div>
																		</div>
																		
																	</div>
																	
																	<div class="col-lg-6 col-xs-12  col-md-6 col-sm-12">
																		
																		
																		<div class="row">
																			<label for="card_no"
																			class="col-sm-4 control-label">Card No<span
																			class="text-danger">*</span></label>
																			
																			<div class="col-sm-8">
																				<input type="text" autocomplete="off"  name="card_no"
																				id="card_no"
																				class="form-control" required>
																			</div>
																		</div>
																		
																		
																		
																		
																		<div class="row">
																			<label for="cell_no"
																			class="col-sm-4 control-label">Mobile No
																			<span class="text-danger">*</span></label>
																			
																			<div class="col-sm-8">
																				<input type="text" name="cell_no"
																				id="cell_no" class="form-control"
																				required>
																				<span class="text-danger cellMsg"></span>
																			</div>
																		</div>
																		
																		
																		
																		<div class="row">
																			<label for="address"
																			class="col-sm-4 control-label">Address
																			<span class="text-danger">*</span></label>
																			
																			<div class="col-sm-8">
																				<textarea name="address" id="address"
																				class="form-control"
																				required></textarea>
																			</div>
																		</div>
																		
																		<div class="row">
																			<label for="thana"
																			class="col-sm-4 control-label">Thana
																			<span class="text-danger">*</span></label>
																			
																			<div class="col-sm-8">
																				<input type="text" name="thana" id="thana"
																				class="form-control" required>
																			</div>
																		</div>
																		
																		<div class="row">
																			<label for="join_date"
																			class="col-sm-4 control-label">Join Date
																			<span class="text-danger">*</span></label>
																			
																			<div class="col-sm-8">
																				<input type="text" autocomplete="off"
																				name="join_date" id="join_date"
																				class="form-control text-center datepicker" value="{{ date("d/m/Y") }}" required>
                                                                                </div>
																				</div>
																				</div>
																				</div>
																				</div>
																				
																				
																				<div class="card-body" style="    border-bottom: 1px solid #ddd;">
																				<h4 class="card-title" style="    margin: 0;">Payment Information</h4>
																				
																			</div>
																			<div class="card-body secondPart" style="padding-top: 0;">
																				<div class="row">
																					<div class="col-md-12">
																						<div class="row">
																							<div class="col-sm-3 hideEdit">
																								<label for="custom_bill" class="control-label pull-left">
																									<input type="checkbox" name="custom_bill" id="custom_bill"> Generate full month bill
																								</label>
																							</div>
																							<div class="col-sm-2 hideEdit">
																								<label for="previous_bill" class="control-label pull-left">
																									<input type="checkbox" name="previous_bill" id="previous_bill"> Previous Bill
																								</label>
																							</div>
																							<div class="col-sm-4">
																								<label for="payment_conformation_sms" class="control-label">Payment Conformation SMS</label>
																								<input type="radio" name="payment_conformation_sms" id="payment_conformation_sms_1" value="1" checked> Yes
																								<input type="radio" name="payment_conformation_sms" id="payment_conformation_sms_0" value="0"> No
																							</div>
																							<div class="col-sm-3">
																								<label for="payment_alert_sms" class="control-label">Payment Alert SMS</label>
																								<input type="radio" name="payment_alert_sms" id="payment_alert_sms_1" value="1" checked> Yes
																								<input type="radio" name="payment_alert_sms" id="payment_alert_sms_0" value="0"> No
																							</div>
																						</div>
																						
																						
																						<div class="row">
																							<div class="col-sm-3">
																								<label for="payment_dateline" class="control-label">Payment Deadline <span class="text-danger">*</span></label>
																								<select name="payment_dateline" id="payment_dateline" class="form-control select2" required>
																									@for($i=1;$i<=31;$i++)
																									<option value="{{ $i }}">{{ $i }}</option>
																									@endfor
																								</select>
																							</div>
																							
																							<div class="col-sm-3">
																								<label for="billing_date" class="control-label">Billing Date <span class="text-danger">*</span></label>
																								<select name="billing_date" id="billing_date" class="form-control select2" required>
																									@for($i=1;$i<=31;$i++)
																									<option value="{{ $i }}">{{ $i }}</option>
																									@endfor
																								</select>
																							</div>
																							
																							<div class="col-sm-3">
																								<label for="payment_id" class="control-label">Package<span class="text-danger">*</span></label>
																								<select class="form-control select2" name="package_id" id="package_id">
																									@foreach($catv_packages as $row)
																									<option value="{{ $row->id }}" price="{{ $row->price }}">{{ $row->name }} [{{ $row->price }}Tk.]</option>
																									@endforeach
																								</select>
																							</div>
																							
																							<div class="col-sm-3">
																								<label for="signup_fee" class="control-label">MRP <span class="text-danger">*</span></label>
																								<input type="number" name="mrp" id="mrp" min="1" value="{{ $catv_packages[0]->price }}" class="form-control text-right" readonly required>
																							</div>
																							
																							<div class="col-sm-3 hideEdit">
																								<label for="otc" class="control-label">OTC </label>
																								<input type="number" name="otc" id="otc" min="0" value="0" class="form-control text-right">
																							</div>
																							
																							<div class="col-sm-3" id="previous_due_row" style="display: none;">
																								<label for="previous_due" class="control-label">Previous Due <span class="text-danger">*</span></label>
																								<input type="number" name="previous_due" id="previous_due" class="form-control text-right">
																							</div>
																							
																							<div class="col-sm-3 hideEdit">
																								<label for="discount" class="control-label">Discount </label>
																								<input type="number" name="discount" id="discount" class="form-control text-right">
																							</div>
																							
																							<div class="col-sm-3 hideEdit">
																								<label for="payable_amount" class="control-label">Payable Amount </label>
																								<input type="text" name="payable_amount" id="payable_amount" class="form-control text-right" readonly>
																							</div>
																							
																							
																							<div class="col-sm-3 hideEdit">
																								<label for="receive_amount" class="control-label">Receive Amount <span class="text-danger">*</span></label>
																								<input type="number" name="receive_amount" id="receive_amount" class="form-control text-right" min="0" value="0" required>
																							</div>
																							
																							<div class="col-sm-3 hideEdit">
																								<label for="receive_date" class=" control-label">Receive Date <span style="display: none" class="text-danger">*</span></label>
																								<input type="text" name="receive_date" disabled id="receive_date" class="form-control text-left datepicker">
																							</div>
																							
																							<div class="col-sm-3">
																								<label for="payment_id" class="control-label">Payment Method <span class="text-danger">*</span></label>
																								<select class="form-control" name="payment_id" id="payment_id">
																									@foreach($payment_method as $row)
																									<option value="{{ $row->id }}">{{ $row->payment_name }}</option>
																									@endforeach
																								</select>
																							</div>
																							
																							<div class="col-sm-3">
																								<label for="cable_id"  class="control-label">Cable Type <span class="text-danger">*</span></label>
																								<select class="form-control" name="cable_id" id="cable_id" required>
																									@foreach($cable_types as $row)
																									<option value="{{ $row->id }}">{{ $row->cable_name }}</option>
																									@endforeach
																								</select>
																							</div>
																							
																							<div class="col-sm-3">
																								<label for="required_cable" class="control-label">Required Cable </label>
																								<div class="input-group">
																									<input type="text" name="required_cable" id="required_cable" class="form-control text-right">
																									<div class="input-group-addon">meter</div>
																								</div>
																							</div>
																							
																							<div class="col-sm-3">
																								<label for="connection_mode" class="control-label">Connectivity Type <span class="text-danger">*</span></label>
																								<select class="form-control" name="connection_mode" id="connection_mode" required>
																									<option value="1">Active</option>
																									<option value="0">Inactive</option>
																									{{--<option value="2">Locked</option>--}}
																								</select>
																							</div>
																							
																							<div class="col-sm-6">
																								<label for="note" class="control-label">Note </label>
																								<textarea name="note" id="note" class="form-control"></textarea>
																							</div>
																						</div>
																						
																						<div style="display: none;" class="alert alert-danger bill_msg text-center" role="alert" >	</div>
																						
																					</div>
																				</div>
																			</div>
																			<div class="card-footer border-top-blue-grey border-top-lighten-5 text-right">
																				<div class="col-md-12">
																					<label><input type="checkbox" name="welcome_sms" value="1"> SMS</label>
																					<label><input type="checkbox" name="welcome_email" value="1" checked> Email</label>
																					<br>
																					<button type="submit" class="btn btn-primary mt-1 mb-0 save"> 	<i class="la la-check-square-o"></i> Save</button>
																				</div>
																				
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
									</div>
								</div>
							</section>
							<!--/ Zero configuration table -->
						</div>
					</div>
				</div>
<div class="modal fade text-left" id="SendSMS" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success white">
				<h4 class="modal-title white" id="myModalLabel8">Send SMS</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="SMSForm" method="post">
				<input type="hidden" id="sms_receiver_id" name="sms_receiver_id">
				<input type="hidden" value="catv" name="client_type">
				<div class="modal-body">
					@csrf
					<div class="col-md-12">
						<div class="row">
							<div class="col-sm-12">
								<label for="sms_text" class="control-label pull-left">SMS Text <span class="text-danger">*</span>
									<button class="btn btn-info" id="chooseTemplate" type="button" style="    padding: 2px;   font-size: 11px;">Choose from template</button></label>

								<label class="sms_count badge badge-warning" style="font-size: 11px;float: right;">0</label>
								<textarea class="form-control" required name="sms_text" rows="10" id="sms_text" placeholder=""></textarea>
							</div>
						</div>

						<div class="row" style="    margin-top: 10px;">

							<div class="col-sm-12">
								<label for="schedule_time">Schedule Time
								</label>
								<input type="text" class="datetimepicker form-control" value="{{ date("d/m/Y H:i") }}" name="schedule_time" id="schedule_time">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn grey btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-danger save">Send</button>
				</div>
			</form>
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
				<!-- Modal -->
				<div data-animation="lightSpeedIn" class="modal fade text-left" id="AddZone" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header bg-success white">
								<h4 class="modal-title white text-center" id="myModalLabel8">Add Zone</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form id="AddZoneForm" method="post">
								<input type="hidden" id="action" name="action" value="1">
								<input type="hidden" id="zone_type" name="zone_type" value="2">
								<input type="hidden" id="ref_network_id" name="ref_network_id" value="0">
								<input type="hidden" id="pop_id" name="pop_id" value="">
								<div class="modal-body">
									@csrf
									<div class="col-md-12">
										<div class="row">
											<label for="name" class="col-sm-4">Zone Name <span class="text-danger">*</span></label>
											<div class="col-sm-8">
												<input type="text" class="form-control"  name="zone_name_en" id="zone_name_en" required autocomplete="off">
											</div>
										</div>
										<div class="row">
											<label for="mobile" class="col-sm-4">Area Incharge <span class="text-danger">*</span></label>
											<div class="col-sm-8">
												<select name="area_incharge" id="area_incharge" class="form-control select2" required>
													@foreach($employees as $row)
													<option value="{{ $row->id }}">{{ $row->emp_name }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="row">
											<label for="address" class="col-sm-4">Technician/Employee <span class="text-danger">*</span></label>
											<div class="col-sm-8">
												<select name="technician_id" id="technician_id" class="form-control select2" required>
													@foreach($employees as $row)
													<option value="{{ $row->id }}">{{ $row->emp_name }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>
									<button type="submit" class="btn btn-success save">Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div data-animation="lightSpeedIn" class="modal fade text-left" id="AddSubZone" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header bg-success white">
								<h4 class="modal-title white text-center" id="myModalLabel8">Add Sub Zone</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form id="AddSubZoneForm" method="post">
								<input type="hidden" id="action" name="action" value="1">
								<div class="modal-body">
									@csrf
									<div class="col-md-12">
										<div class="row">
											<label for="ref_zone_id" class="col-sm-4">Zone<span class="text-danger">*</span></label>
											<div  class="col-sm-8">
												<select name="ref_zone_id" id="ref_zone_id" class="form-control select2">
													<option>Select Zone</option>
													@foreach($zones as $zone)
													<option value="{{ $zone->id }}">{{ $zone->zone_name_en }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="row">
											<label for="sub_zone_name" class="col-sm-4">Sub Zone Name <span class="text-danger">*</span></label>
											<div  class="col-sm-8">
												<input type="text" class="form-control"  name="sub_zone_name" id="sub_zone_name" required>
											</div>
										</div>
										<div class="row">
											<label for="thana" class="col-sm-4">Thana <span class="text-danger">*</span></label>
											<div  class="col-sm-8">
												<input type="text" class="form-control"  name="thana" id="thana" required>
											</div>
										</div>
										<div class="row">
											<label for="sub_zone_location" class="col-sm-4">Location <span class="text-danger">*</span></label>
											<div  class="col-sm-8">
												<input type="text" class="form-control"  name="sub_zone_location" id="sub_zone_location" required>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>
									<button type="submit" class="btn btn-success save">Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- END: Content-->
				<!-- END: Content-->
				
				
				<style>
					form .row{
						margin-top: 10px;
					}
					.secondPart input{
						margin-bottom: 20px;
					}
					#DataForm .row {
						margin-top: 10px;
					}
					
					#DataForm .row label {
						text-align: left;
					}
					
					table#datalist tr td:last-child {
						vertical-align: middle !important;
					}
				</style>
				@endsection
				@section("page_script")
				<script type="text/javascript">
					
					$(document).ready(function () {
						__startup();
						
						var table;
						
						$(document).on('submit', "#DataForm", function (e) {
							e.preventDefault();
							
							$(".save").text("Saving...").prop("disabled", true);
							$.ajax({
								type: "POST",
								url: "{{ route('catb-clients.store') }}",
								data: new FormData(this),
								processData: false,
								contentType: false,
								cache: false,
								success: function (response) {
									console.log(response);
									if (response == 1) {
										$("#DataForm").trigger("reset");
										toastr.success('Data Saved Successfully!', 'Success');
										$("[href='#DataList']").tab("show");
										_clientId();
										_clientCount();
										clientData();
										
									}
									else {
										toastr.warning('Data Cannot Saved. Try aging!', 'Warning');
									}
									$(".save").text("Save").prop("disabled", false);
								},
								error: function (request, status, error) {
									console.log(request.responseText);
									toastr.warning('Server Error. Try aging!', 'Warning');
									$(".save").text("Save").prop("disabled", false);
								}
							});
						});
						
						$(document).on('submit', "#AddSubZoneForm", function (e) {
							e.preventDefault();
							
							$("#AddSubZoneForm .save").text("Saving...").prop("disabled", true);
							$.ajax({
								type: "POST",
								url: "{{ route('save_sub_zone') }}",
								data: new FormData(this),
								processData: false,
								contentType: false,
								cache: false,
								success: function (response)
								{
									$("#AddSubZone").modal("hide");
									$("#AddSubZoneForm .save").text("Save").prop("disabled", false);
								},
								error: function (request, status, error) {
									console.log(request.responseText);
									$("#AddSubZoneForm .save").text("Save").prop("disabled", false);
								}
							});
						});
						
						$(document).on('submit', "#AddZoneForm", function (e) {
							e.preventDefault();
							
							$("#AddZoneForm .save").text("Saving...").prop("disabled", true);
							$.ajax({
								type: "POST",
								url: "{{ route('save_zone') }}",
								data: new FormData(this),
								processData: false,
								contentType: false,
								cache: false,
								success: function (response)
								{
									_zone();
									$("#AddZone").modal("hide");
									$("#AddZoneForm .save").text("Save").prop("disabled", false);
								},
								error: function (request, status, error) {
									console.log(request.responseText);
									$("#AddZoneForm .save").text("Save").prop("disabled", false);
								}
							});
						});
						
						$(document).on('change', '#zone_id', function () {
							if($(this).val()){
								$('#loader').removeClass('hidden');
								$("#sub_zone_id").empty();
								$.ajax({
									type: "POST",
									url: "{{ route('ge_sub_zone') }}",
									data: {
										id      :   $(this).val(),
										_token  :   "{{csrf_token()}}"
									},
									success: function (response) {
										$('#loader').addClass('hidden');
										//console.log(response);
										if (response!=0) {
											var json = JSON.parse(response);
											$.each(json,function(key,value){
												$("#sub_zone_id").append("<option value='"+value.id+"' thana='"+value.thana+"'>"+value.sub_zone_name+"</option>")
											});
											$("#sub_zone_id").trigger("change");
										}
										else {
											//  toastr.warning('Data Cannot Removed. Try aging!', 'Warning');
										}
									},
									error: function (request, status, error) {
										$('#loader').addClass('hidden')
										console.log(request.responseText);
										toastr.warning('Server Error. Try aging!', 'Warning');
									}
								});
							}
						});
						
						$(document).on('change', '#sub_zone_id', function () {
							var thana = $('#sub_zone_id :selected').attr("thana");
							$("#thana").val(thana);
						});
						
						$(document).on('click', '.addZone', function () {
							$("#AddZone").modal("show");
							$("#AddZoneForm").trigger("reset");
						});
						
						$(document).on('click', '.addSubZone', function () {
							$("#AddSubZone").modal("show");
							$("#AddSubZoneForm").trigger("reset");
						});
						
						$(document).on('change', '#package_id', function () {
							var price = $('#package_id :selected').attr("price");
							$("#mrp").val(price);
						});
						
						$(document).on('click', '.addnew', function () {
							$("#action").val(1);
							$("#id").val("");
							$("#client_password").attr("required", "required").val("");
							$("#receive_amount").attr("required", "required");
							$("#receive_date").attr("required", "required");
							$(".operation_type").text("Add New");
							$("#DataForm").trigger("reset");
							$(".hideEdit").show();
							$("#otc").prop("disabled", false);
							$("#mrp").prop("disabled", false);
							$("#client_username").prop("disabled", false);
							$('.bill_msg').hide().html("");
							_clientId();
						});
						
						$(document).on('change keyup blur', '#receive_amount', function () {
							var element = $("#receive_date");
							if(Number($(this).val())>0){
								element.attr("required","required").prop("disabled",false);
								element.closest(".row").find(".text-danger").show();
								} else {
								element.removeAttr("required","required").prop("disabled",true);
								element.closest(".row").find(".text-danger").hide();
								element.val("");
							}
							
						});
						$(document).on('change keyup blur', '#cell_no', function () {
							var element = $(this).val();
							var requirement = $(".cellMsg");
							//console.log(element.length)
							if (element.length != 11) {
								requirement.html("Invalid mobile no! Contain 11 digit.");
								} else {
								requirement.html("");
							}
						});
						
						$(document).on('change', 'input[name="paymentPaid"]', function () {
							if ($(this).val() == 1) {
								$("#inputDate").hide();
								} else {
								$("#inputDate").show();
							}
						});
						
						$(document).on('click', '.update', function () {
							var element = $(this);
							var del_id = element.attr("id");
							var info = 'id=' + del_id + "&_token={{csrf_token()}}";
							$(".edit" + del_id).html('<i class="ft-loader"></i>').prop("disabled", true);
							$.ajax({
								type: "POST",
								url: "{{route('catb_client_update')}}",
								data: info,
								success: function (response) {
									$(".edit" + del_id).html('<i class="ft-edit"></i> Edit').prop("disabled", false);
									if (response != 0) {
										$(".hideEdit").hide();
										$("#receive_amount").removeAttr("required", "required");
										$("#receive_date").removeAttr("required", "required");
										$("#tabOption").text("Update Client");
										$("[href='#operation']").tab("show");
										var json = JSON.parse(response);
										//console.log(json)
										$("#action").val(2);
										$("#id").val(json.id);
										$("#client_password").removeAttr("required").val("");
										$("#client_username").val(json.client_id).prop("disabled", true);
										$("#client_name").val(json.client_name);
										$("#card_no").val(json.home_card_no);
										$("#zone_id").val(json.zone_id).trigger("change");
										$("#sub_zone_id").val(json.sub_zone_id).trigger("change");
										$("#payment_dateline").val(json.payment_dateline).trigger("change");
										$("#id_prefix").val(json.prefix_id).trigger("change");
										$("#billing_date").val(json.billing_date).trigger("change");
										$("#cell_no").val(json.cell_no);
										$("#package_id").val(json.package_id).trigger("change");
										$("#company_id").val(json.company_id).trigger("change");
										$("#payment_id").val(json.payment_id).trigger("change");
										$("#mrp").val(json.mrp).prop("disabled", true);
										$("#otc").val(json.otc).prop("disabled", true);
										$("#alter_cell_no_1").val(json.alter_cell_no_1);
										$("#address").val(json.address);
										$("#thana").val(json.thana);
										$("#join_date").val(json.join_date);
										$("#occupation").val(json.occupation);
										$("#email").val(json.email);
										$("#nid").val(json.nid);
										$("#connection_mode").val(json.connection_mode).trigger("change");
										$("#cable_id").val(json.cable_id).trigger("change");
										$("#required_cable").val(json.required_cable);
										if (json.picture) {
											$(".picture").html("<img src='" + json.picture + "' style='width:100px; height: 100px;'>");
											} else {
											$(".picture").html("");
										}
										if (json.payment_conformation_sms == 1) {
											$("#payment_conformation_sms_1").attr('checked', true).val(1);
											$("#payment_conformation_sms_0").attr('checked', false).val(0);
											} else {
											$("#payment_conformation_sms_1").attr('checked', false).val(1);
											$("#payment_conformation_sms_0").attr('checked', true).val(0);
										}
										if (json.payment_alert_sms == 1) {
											$("#payment_alert_sms_1").attr('checked', true).val(1);
											$("#payment_alert_sms_0").attr('checked', false).val(0);
											} else {
											$("#payment_alert_sms_1").attr('checked', false).val(1);
											$("#payment_alert_sms_0").attr('checked', true).val(0);
										}
										
										$("#note").val(json.note);
										_clientCount();
										} else {
										toastr.warning('Server Error. Try aging!', 'Warning');
									}
								}
							});
						});
						
						$(document).on('click', '.deleteData', function () {
							var element = $(this);
							var del_id = element.attr("id");
							var data = 'id=' + del_id + '&_token={{csrf_token()}}';
							
							if (confirm("Are you sure you want to delete this?")) {
								$.ajax({
									type: "POST",
									url: "{{ route('catb_client_del') }}",
									data: data,
									success: function (response) {
										console.log(response);
										if (response == 1) {
											toastr.success('Data removed Successfully!', 'Success');
											element.parents("tr").animate({backgroundColor: "#003"}, "slow").animate({opacity: "hide"}, "slow");
											_clientId();
											_clientCount();
										}
										else {
											toastr.warning('Data Cannot Removed. Try aging!', 'Warning');
										}
									},
									error: function (request, status, error) {
										console.log(request.responseText);
										toastr.warning('Server Error. Try aging!', 'Warning');
									}
								});
							}
							return false;
							
						});
						
						$(document).on('change', '#custom_bill', function () {
							_payableFee();
						});
						
						$(document).on('change', '#previous_bill', function () {
							console.log($(this).is(':checked'))
							if ($(this).is(':checked')) {
								$("#previous_due_row").show();
								$("#previous_due").attr("required","required");
								} else {
								$("#previous_due_row").hide();
								$("#previous_due").val(0).removeAttr("required","required");
							}
						});
						
						$(document).on('keyup input', '#otc', function () {
							_payableFee();
						});
						$(document).on('keyup input', '#mrp', function () {
							_payableFee();
						});
						$(document).on('keyup input', '#discount', function () {
							_payableFee();
						});
						$(document).on('keyup input change', '#billing_date', function () {
							_payableFee();
						});

						$(document).on('click', '.send_sms', function () {
							$("#sms_receiver_id").val($(this).attr("id"));
							$("#SendSMS").modal("show");
						});

						$(document).on('click', "#chooseTemplate", function () {
							$("#sms_template_list").modal("show");
						});
						$(document).on('click', "#choose_sms_template", function () {
							var sms = $(this).attr("text");
							$("#sms_template_list").modal("hide");
							$("#sms_text").val(sms).focus();
						});
						$(document).on('change input keyup focus blur', "#sms_text", function () {
							var txtCount = $(this).val().trim().length;
							$(".sms_count").html(txtCount);
						});
						$(document).on('submit', "#SMSForm", function (e) {
							e.preventDefault();

							$("#SMSForm .save").text("Sending...").prop("disabled", true);
							$.ajax({
								type: "POST",
								url: "{{ route('send_sms_from_client') }}",
								data: new FormData(this),
								processData: false,
								contentType: false,
								cache: false,
								success: function (response) {
									console.log(response);
									if (response == 1) {
										$("#SendSMS").modal("hide");
										$("#SMSForm ").trigger("reset");
										toastr.success('Sent Successfully!', 'Success');
									}
									else {
										toastr.warning('Failed. Try aging!', 'Warning');
									}
									$("#SMSForm .save").text("Save").prop("disabled", false);
								},
								error: function (request, status, error) {
									console.log(request.responseText);
									toastr.warning('Failed. Try aging!', 'Warning');
									$("#SMSForm .save").text("Sent").prop("disabled", false);
								}
							});
						});
					});

					function __startup() {
						clientData();
						//            _pop($('#network_id'));
						//            _zone($('#network_id'));
						_clientId();
						//            _payableFee();
					}
					
					function clientData(){
						
						var filterData  = {
							status  : $("#filter_status").val(),
							zone  : $("#filter_zone").val()
						};

						table = $('#datalist').removeAttr('width').DataTable
						({
							"bAutoWidth": false,
							"destroy": true,
							"bProcessing": true,
							"serverSide": true,
							"responsive": false,
							"aaSorting": [[0, 'desc']],
							"scrollX": true,
							"scrollCollapse": true,
							"columnDefs": [
							{
								"targets": [4, 5, 6,7],
								"orderable": false
								}, {
								"targets": [0, 6,7],
								className: "text-center"
							}],
							"ajax": {
								url: "{{ route('catb_client_list') }}",
								type: "post",
								"data": {
									_token: "{{ csrf_token() }}",
									filter: filterData
								},
								"aoColumnDefs": [{
									'bSortable': false
								}],
								
								"dataSrc": function (jsonData) {
									//console.log(jsonData)
									for (var i = 0, len = jsonData.data.length; i < len; i++) {
										
//										jsonData.data[i][7] = '<div class="btn-group align-middle clientId' + jsonData.data[i][0] + '" role="group">' +
//												'<button id="' + jsonData.data[i][0] + '" class="send_sms edit' + jsonData.data[i][0] + ' btn btn-success btn-sm badge">' +
//												'<span class="ft-message-circle"></span> SMS</button>' +
//										'<button id="' + jsonData.data[i][0] + '" class="update edit' + jsonData.data[i][0] + ' btn btn-primary btn-sm badge">' +
//										'<span class="ft-edit"></span> Edit</button>' +
//										'<button  id=' + jsonData.data[i][0] + ' class="deleteData btn btn-danger btn-sm badge">' +
//										'<span class="ft-delete"></span> Del</button>' +
//										'</div>';

										jsonData.data[i][7] =
												'<button class="btn btn-outline-purple btn-sm dropdown-toggle clientId' + jsonData.data[i][0] + '" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
												'Action'+
												'</button>'+
												'<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">'+
												'<a id="' + jsonData.data[i][0] + '" class="send_sms dropdown-item text-success" href="#"><span class="ft-message-circle"></span> SMS</a>'+
												'<a id="' + jsonData.data[i][0] + '" class="update dropdown-item text-primary" href="#"><span class="ft-edit"></span> Edit</a>'+
												'<a id="' + jsonData.data[i][0] + '" class="ledger dropdown-item text-info" href="#"><span class="ft-list"></span> Ledger</a>'+
												'<a id="' + jsonData.data[i][0] + '" class="deleteData dropdown-item text-danger" href="#"><span class="ft-trash"></span> Del</a>'+
												'</div>';

										jsonData.data[i][6] = status;
									}
									//console.log()
									return jsonData.data;
								},
								error: function (request, status, error) {
									console.log(request.responseText);
									toastr.warning('Server Error. Try aging!', 'Warning');
								}
							}
						});
						
						table.on('order.dt search.dt', function () {
							table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
								cell.innerHTML = i + 1;
							});
						}).draw();
					}
					
					function _payableFee() {
						var mrp = Number($('#mrp').val());//monthly fee
						var otc = Number($('#otc').val());
						var discount = Number($('#discount').val());
						var billing_date = "{{ date("Y-m-") }}" + $('#billing_date').val();
						var lastDayOfMonth = new Date();
						lastDayOfMonth.setDate(30);
						if (billing_date != "") {
							var days = datediff(parseDate(billing_date), parseDate(lastDayOfMonth));
							var total = days * mrp / 30;
						}
						else {
							total = 0
						}
						
						if ($('#custom_bill').is(":checked")) {
							total = mrp;
							$('.bill_msg').show().html("This bill calculated from this current month.");
							} else {
							$('.bill_msg').hide().html("");
						}
						
						var payable_amount = (total + otc) -  discount;
						
						$('#payable_amount').val(Math.round(payable_amount));
					}
					
					function _zone() {
						$('#loader').removeClass('hidden');
						$.ajax({
							type: "POST",
							url: "{{ route('CatvZoneList') }}",
							data: {
								_token	:	"{{ csrf_token() }}"
							},
							success: function (response) {
								$('#loader').addClass('hidden');
								//console.log(response)
								if (response != 0) {
									var json = JSON.parse(response);
										if(json.length>0){
											$("#zone_id").empty();
											$("#ref_zone_id").empty();
											var html =  "<option value=''>Select Zone</option>";
											$.each(json, function (key, value) {
												html += "<option value='" + value.id + "'>" + value.zone_name_en + "</option>";
											});
											$("#zone_id").html(html).trigger("reset");
											$("#ref_zone_id").html(html).trigger("reset");
										}
									} else {
									$('#loader').addClass('hidden');
								}
							}
						});
					}
					
					function _clientId() {
						$('#loader').removeClass('hidden');
						var prefix= $("#id_prefix").val();
						var info = "prefix="+prefix+"&_token={{csrf_token()}}";
						$.ajax({
							type: "POST",
							url: "{{ route('get_catv_client_id') }}",
							data: info,
							success: function (response) {
								$('#loader').addClass('hidden');
								if (response !== 0) {
									if($("#action").val()==1) {
										$("#client_username").val(response);
									}
									} else {
									toastr.warning('Failed to fetch client id. Try aging!', 'Warning');
								}
								if($("#action").val()==2){
									$("#id_prefix").prop("disabled",true);
									} else{
									$("#id_prefix").prop("disabled",false);
								}
								
							},
							error: function (request, status, error) {
								$('#loader').addClass('hidden')
								console.log(request.responseText);
							}
						});
					}
					
					function _clientCount() {
						var info = "_token={{csrf_token()}}";
						$.ajax({
							type: "POST",
							url: "{{ route('catv_client_count') }}",
							data: info,
							success: function (response) {
								var json = JSON.parse(response);
								var total = Number(json.active)+Number(json.inactive);
								$(".client_summery .total").html(total);
								$(".client_summery .actived").html(json.active);
								$(".client_summery .inactived").html(json.inactive);
							},
							error: function (request, status, error) {
								console.log(request.responseText);
							}
						});
					}
					
					function parseDate(str) {
						return new Date(str);
					}
					
					function datediff(first, second) {
						// Take the difference between the dates and divide by milliseconds per day.
						// Round to nearest whole number to deal with DST.
						return Math.round((second - first) / (1000 * 60 * 60 * 24));
					}


					var _validFileExtensions = [".xls",".xlsx", ".csv"];
					function Validate(oForm) {
						var arrInputs = $("#file");
						for (var i = 0; i < arrInputs.length; i++) {
							var oInput = arrInputs[i];
							if (oInput.type == "file") {
								var sFileName = oInput.value;
								if (sFileName.length > 0) {
									var blnValid = false;
									for (var j = 0; j < _validFileExtensions.length; j++) {
										var sCurExtension = _validFileExtensions[j];
										if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
											blnValid = true;
											$(".excel_img").show();
											$(".fileName").html(sFileName.split(/(\\|\/)/g).pop());
											break;
										}
									}

									if (!blnValid) {
										arrInputs.val('');
										$(".excel_img").hide();
										$(".fileName").html('');
										alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
										return false;
									}
								}
							}
						}
						if(arrInputs.val()==""){
							$(".excel_img").hide();
							$(".fileName").html('');
						}
						return true;
					}
				</script>
				
				@endsection
						