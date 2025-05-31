<form method="post" id="billGenerate">
    @csrf
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>ID</th>
            <th>Client</th>
            <th>Particular</th>
            <th>Bill ID</th>
            <th>Bill Date</th>
            <th>Bill Month</th>
            <th>Package</th>
            <th>Package Price</th>
            <th>Permanent Discount</th>
            <th>Due</th>
            <th>Bill Amount</th>
            <th>SMS </th>
            <th>SMS Count</th>
        </tr>
    </thead>
    <tbody>
        
        @php
            $total_permanent_discount_amount = 0;
            $total_client_due = 0;
            $total_payable_amount = 0;
            $sms = 0;
        @endphp
        @if (count($isp_bills) > 0)
            @foreach ($isp_bills as $key => $row)
                <tr>

                    <td>
                        <input type="hidden" name="particular[]" value="Monthly Bill">
                        <input type="hidden" name="company_id[]" value="<?= $row['company_id'] ?>">
                        <input type="hidden" name="payment_alert_sms[]" value="<?= $row['payment_alert_sms'] ?>">
                        <input type="hidden" name="client_id[]" value="<?= $row['client_id'] ?>">
                        <input type="hidden" name="client_initial_id[]" value="<?= $row['client_id'] ?>">
                        <input type="hidden" name="name[]" value="<?= $row['name'] ?>">
                        <input type="hidden" name="cell_no[]" value="<?= $row['cell_no'] ?>">
                        <input type="hidden" name="bill_id[]" value="<?= $row['bill_id'] ?>">
                        <input type="hidden" name="bill_date[]" value="<?= $row['bill_date'] ?>">
                        <input type="hidden" name="bill_month[]" value="<?= $row['bill_month'] ?>">
                        <input type="hidden" name="bill_year[]" value="<?= $row['bill_year'] ?>">
                        <input type="hidden" name="bill_type[]" value="<?= $row['bill_type'] ?>">
                        <input type="hidden" name="bill_approve[]" value="1">
                        <input type="hidden" name="bill_status[]" value="0">
                        <input type="hidden" name="package_title[]" value="<?= $row['package_title'] ?>">
                        <input type="hidden" name="package_id[]" value="<?= $row['package_id'] ?>">
                        <input type="hidden" name="package_amount[]" value="<?= $row['package_amount'] ?>">
                        <input type="hidden" name="payable_amount[]" value="<?= $row['package_amount'] - $row['permanent_discount_amount'] ?>">
                        <input type="hidden" name="permanent_discount[]"  value="<?= $row['permanent_discount_amount'] ?>">
                        <input type="hidden" name="total_bill[]"  value="<?= $row['package_amount'] - $row['permanent_discount_amount'] + $row['client_due'] ?>">
                        <textarea hidden name="sms_text[]"
                            ><?= $row['sms_text'] ?></textarea>

                        {{ $key + 1 }}
                    </td>
                    <td>{{ $row['client_initial_id'] }}</td>
                    <td>{{ $row['name'] }} - {{ $row['cell_no'] }}</td>
                    <td>{{ $row['particular'] }}</td>
                    <td>{{ $row['bill_id'] }}</td>
                    <td>{{ $row['bill_date'] }}</td>
                    <td>{{ $row['bill_month'] }} {{ $row['bill_year'] }}</td>
                    <td>{{ $row['package_title'] }}</td>
                    <td class="text-right">{{ $row['package_amount'] }}</td>
                    <td class="text-right">{{ $row['permanent_discount_amount'] }}</td>
                    <td class="text-right">{{ $row['client_due'] }}</td>
                    <td class="text-right">{{ $row['payable_amount'] }}</td>
                    <td class="">{{ $row['sms_text'] }}</td>
                    <td class="text-right">{{ $row['sms_count'] }}</td>
                </tr>
                @php
                    $total_permanent_discount_amount += $row['permanent_discount_amount'];
                    $total_client_due += $row['client_due'];
                    $total_payable_amount += $row['payable_amount'];
                    $sms += $row['sms_count'];
                @endphp
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="9" class="text-right">Total</th>
            <th class="text-right">{{ $total_permanent_discount_amount }}</th>
            <th class="text-right">{{ $total_client_due }}</th>
            <th class="text-right">{{ $total_payable_amount }}</th>
            <th class="text-right"></th>
            <th class="text-right">{{ $sms }}</th>
        </tr>
    </tfoot>
</table>

@if (count($isp_bills) > 0)
    <div class="row">
        <div class="col-sm-4">
            <label><input type="checkbox" name="payment_confirm_sms" value="1" checked> SMS</label>
            <label><input type="checkbox" name="payment_confirm_email" value="1" checked> Email</label>
        </div>
        <div style="clear:both;"></div>
    </div>
    <button type="submit" class="btn btn-primary mt-1 mb-0 save">Submit Bill</button>
@endif


</form>