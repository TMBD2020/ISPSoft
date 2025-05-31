@extends('layouts.app')

@section('title', 'Expense')

@section('content')

<style>
    table td{
padding: 0 !important;
text-align: center;
    }
</style>
    <div class="app-content content">

        <div class="content-wrapper">
            <div class="content-body">
                <section id="configuration">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <br>
                                <center>
                                   <h3>ISP PPPOE Client Import</h3>
                                </center>
                                <br>
                                <form onsubmit="return confirmSubmit()" method="post" action="{{ route("isp_client_import_queue_save") }}">
                                    @csrf
                                    <div class="table-responsive">
                                        <table  class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>box</th>
                                                    <th>client_name</th>
                                                    <th>client_id</th>
                                                    <th>mobile_no</th>
                                                    <th>alter_mobile_no</th>
                                                    <th>email</th>
                                                    <th>nid</th>
                                                    <th>client_address</th>
                                                    <th>client_thana</th>
                                                    <th>ip_address</th>
                                                    <th>dynamic_mac_address</th>
                                                    <th>gpon_mac_address</th>
                                                    <th>join_date</th>
                                                    <th>package_id</th>
                                                    <th>previous_bill</th>
                                                    <th>permanent_discount</th>
                                                    <th>client_required_cable</th>
                                                    <th>payment_date</th>
                                                    <th>billing_date</th>
                                                    <th>bill_responsible_person</th>
                                                    <th>client_status</th>
                                                    <th>note</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $row)
                                                @if(trim($row['box_id']) && trim($row['client_name']) && trim($row['mobile_no']) && trim($row['ip_address']))
                                                    <tr>
                                                        <td>
                                                            <select name="box_id[]">
                                                                @foreach($boxes as $value)
                                                                <option value="{{ $value->id }}" @if($value->id==$row['box_id']) selected @endif>{{ $value->box_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="text" name="client_name[]" value="{{ $row['client_name'] }}" /></td>
                                                        <td><input type="text" name="client_id[]" value="{{ $row['client_id'] }}" /></td>
                                                        <td><input type="text" name="mobile_no[]" value="{{ $row['mobile_no'] ? '0'.$row['mobile_no']:'' }}" /></td>
                                                        <td><input type="text" name="alter_mobile_no[]" value="{{ $row['alter_mobile_no'] ? '0'.$row['alter_mobile_no']:'' }}" /></td>
                                                        <td><input type="text" name="email[]" value="{{ $row['email'] }}" /></td>
                                                        <td><input type="text" name="nid[]" value="{{ $row['nid'] }}" /></td>
                                                        <td><input type="text" name="client_address[]" value="{{ $row['client_address'] }}" /></td>
                                                        <td><input type="text" name="client_thana[]" value="{{ $row['client_thana'] }}" /></td>
                                                        <td><input type="text" name="ip_address[]" value="{{ $row['ip_address'] }}" /></td>
                                                        <td><input type="text" name="dynamic_mac_address[]" value="{{ $row['dynamic_mac_address'] }}" /></td>
                                                        <td><input type="text" name="gpon_mac_address[]" value="{{ $row['gpon_mac_address'] }}" /></td>
                                                        <td><input type="text" name="join_date[]" value="{{ $row['join_date'] }}" /></td>
                                                        <td>
                                                            <select name="package_id[]">
                                                                @foreach($packages as $value)
                                                                <option value="{{ $value->id }}" @if($value->id==$row['package_id']) selected @endif>{{ $value->package_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="text" name="previous_bill[]" value="{{ $row['previous_bill'] }}" /></td>
                                                        <td><input type="text" name="permanent_discount[]" value="{{ $row['permanent_discount'] }}" /></td>
                                                        <td><input type="text" name="client_required_cable[]" value="{{ $row['client_required_cable'] }}" /></td>
                                                        <td>
                                                            <select name="billing_date[]" style="width: 100%">
                                                                @for($i=1;$i<=31;$i++)
                                                                    <option value="{{ $i }}" @if($i==$row['billing_date']) selected @endif>{{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="payment_date[]" style="width: 100%">
                                                                @for($i=1;$i<=28;$i++)
                                                                    <option value="{{ $i }}" @if($i==$row['payment_date']) selected @endif>{{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="billing_responsible[]" style="width: 100%">
                                                                <option value="">Set by zone</option>
                                                                @foreach($technicians as $row)
                                                                    <option value="{{ $row->auth_id }}">{{ $row->emp_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        
                                                        <td>
                                                            <select name="client_status[]" style="width: 100%">
                                                                <option value="1" @if('Active'==$row['client_status']) selected @endif>Active</option>
                                                                <option value="0" @if('Inactive'==$row['client_status']) selected @endif>Inactive</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" name="note[]" value="{{ $row['note'] }}" /></td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                            
                                        </table>
                                    </div>
                                    <br>
                                    <br>
                                    <center>
                                        <label><input type="checkbox" name="isSMS" value="1"> SMS Sent</label>
                                        <div style="clear:both;"></div>
                                        <input type="submit" class="btn btn-primary" value="Submit">
                                    </center>
                                    <br>
                                    <br>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
<script>
    function confirmSubmit(){
        if(confirm("Are you sure to submit?")){
            return true;
        } return false;
    }
</script>
@endsection
