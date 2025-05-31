<table class="table table-bordered ">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">Date</th>
            <th class="text-center">Particular</th>
            <th class="text-center">P. Dis.</th>
            <th class="text-center">Dis.</th>
            <th class="text-center">Bill</th>
            <th class="text-center">Received</th>
            <th class="text-center">Bal.</th>
            <th class="text-center">Received By</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($bills)>0)
        @php 
            $bal=0;
        @endphp
        @foreach($bills as $key=>$bill)
        @php 
            $bal+=($bill->debit-($bill->credit+$bill->discount));
        @endphp
        <tr>
            <td class="text-center">{{$key+1}}</td>
            <td class="text-center">{{ $bill->bill_date }}</td>
            <td>{!! $bill->package_name ?  ($bill->package_name . " (TK " . $bill->package_price.")") :  $bill->particular !!} </td>
            <td class="text-right">{{$bill->permanent_discount}} </td>
            <td class="text-right">{{$bill->permanent_discount}} </td>
            <td class="text-right">{{$bill->discount}}</td>
            <td class="text-right">{{$bill->debit}}</td>
            <td class="text-right">{{ $bill->credit }}</td>
            <td class="text-right">{{$bal}} </td>
            <td class="text-center">{{ $bill->receive_by ? $bill->receive_by : "" }}</td>
            <td class="text-center">
                <form method="post" target="blank" action="{{ route('isp-bill-print')}}">
                    @csrf
                    <input type="hidden" value="{{$bill->id}}" name="id"/>
                   <div class="btn-group">
                    <button type="button" id="{{$bill->id}}" ttype="{{$bill->ttype}}" class="btn btn-primary badge print"><i class="ft-printer"></i></button>
                    @if(Auth::user()->can('isp-bill-delete'))
                    <button type="button" id="{{$bill->id}}" ttype="{{$bill->ttype}}" class="btn btn-danger badge delmyb"><i class="ft-trash"></i></button>
                    @endif
                   </div>
                </form>               
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>

</table>
