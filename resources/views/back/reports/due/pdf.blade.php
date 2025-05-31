<style>
    .text-center{
        text-align: center;
    }
    .text-right{
        text-align: right;
    }
</style>

<center>
    <table style="width: 100%">
        <tr>
            <td style="text-align: left;">
                <div style="margin-bottom: 15px">
                    @if(Settings::settings()['company_logo'])
                        <div  class="text-center w-33">
                            <img src="{{ Settings::settings()['company_logo'] }}" width="60">
                        </div>
                    @endif
                    <div class="text-center w-33">
                        <h2 class="m0">{{ Settings::settings()['company_name']  }}</h2>
                        <h5 class="m0">{{ Settings::settings()["contact_number"]  }}</h5>
                        <h5 class="m0">{{ Settings::settings()["company_email"]  }}</h5>
                        <h5 class="m0">{{ Settings::settings()["contact_address"]  }}</h5>
                    </div>
                </div>
            </td>
            <td style="text-align: right;">  <h6>Due Bill</h6>
            <small>Date: {{ date("d-m-Y") }}</small>
            </td>
        </tr>
    </table>


</center>
                <table style="border-collapse: collapse;" border="1">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th  class="text-center">ID<br>Client</th>
                            <th class="text-center">Mobile</th>
                            <th  class="text-center">Address</th>
                            <th class="text-center">Package</th>
                            <th  class="text-center">Zone</th>
                            <th class="text-center">Commitment Date</th>
                            <th class="text-center">Amount</th>
                            <th  class="text-center">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                    $total_payable = 0;
                    @endphp
                    @foreach($data as $key=>$data)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td>{{ $data->client->client_id }}/ {{ $data->client->client_name }}</td>
                            <td>{{ $data->client->cell_no }}</td>
                            <td class="text-left">{{ $data->client->address }}</td>
                            <td class="text-center">{{ $data->client->package->package_name }}</td>
                            <td class="text-left">{{ $data->client->zone?$data->client->zone->zone_name_en:'' }}</td>
                            <td class="text-center">{{ $data->client->termination_date=='0000-00-00'?'':$data->client->termination_date }}</td>
                            <td class="text-right"> {{ number_format( $data->payable ,2) }}</td>
                            <td class="text-left">{{ $data->client->note }}</td>
                        </tr>
                        @php
                        $total_payable += $data->payable;
                        @endphp
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="7" class="text-center">Total</th>
                        <th class="text-right">{{ number_format( $total_payable,2) }}</th>
                        <th class="text-right"></th>
                    </tr>
                    </tfoot>

                </table>