<style>
    .text-center {
        text-align: center;
    }
    table th,table td{
        font-size: 12px;
    }
</style>
<table style="width: 100%">
    <tr>
        <td style="text-align: left;">
            <div style="margin-bottom: 15px">
                @if(Settings::settings()['company_logo'])
                    <div class="text-center w-33">
                        <img src="{{ Settings::settings()['company_logo'] }}" width="60">
                    </div>
                @endif
                <div class="text-center w-33">
                    <h2>{{ Settings::settings()['company_name']  }}</h2>
                    <h5>{{ Settings::settings()["contact_number"]  }}</h5>
                    <h5>{{ Settings::settings()["company_email"]  }}</h5>
                    <h5>{{ Settings::settings()["contact_address"]  }}</h5>
                </div>
            </div>
        </td>
        <td style="text-align: right;"><h6>ISP Clients</h6>
            <small>Date: {{ date("d-m-Y") }}</small>
        </td>
    </tr>
</table>
<table style="border-collapse: collapse; width: 100%" border="1">
    <thead>
    <tr>
        <th  class="text-center">#</th>
        <th  class="text-center">ID</th>
        <th  class="text-center">Name</th>
        <th  class="text-center">Mobile</th>
        <th  class="text-center">Address</th>
        <th  class="text-center">IP/MAC/GPON</th>
        <th  class="text-center">Package</th>
        <th  class="text-center">POP</th>
        <th  class="text-center">Zone</th>
        <th  class="text-center">Node</th>
        <th  class="text-center">Box</th>
        <th  class="text-center">Status</th>
        <th  class="text-center">Join</th>
    </tr>
    </thead>
    <tbody>
    @foreach($clients as $key=>$data)
        <tr>
            <td class="text-center">{{ $key+1 }}</td>
            <td class="text-center"> {{ $data->client_id }}</td>
            <td> {{ $data->client_name }}</td>
            <td class="text-center"> {{ $data->cell_no }}</td>
            <td>{{ $data->address }}</td>
            <td>
                <u>IP:</u> {{ $data->ip_address }} <br>
                @if($data->mac_address)<u>MAC:</u>{{ $data->mac_address }}<br>@endif
                @if($data->gpon_mac_address)<u>GPON:</u> {{ $data->gpon_mac_address }}@endif
            </td>
            <td>{{ $data->package->package_name }}</td>
            <td>{{ $data->pop ? $data->pop->pop_name: "" }}</td>
            <td>{{ $data->zone ? $data->zone->zone_name_en: "" }}</td>
            <td>{{ $data->node ? $data->node->node_name: "" }}</td>
            <td>{{ $data->box ? $data->box->box_name: "" }}</td>
            <td class="text-center">
                @if($data->connection_mode == 1)
                    Active
                @elseif($data->connection_mode == 2)
                    Locked
                @else
                    Inactive
                @endif
            </td>
            <td class="text-center">@if($data->join_date){{ date('d/m/Y',strtotime($data->join_date)) }}@endif</td>
        </tr>
    @endforeach
    </tbody>


</table>