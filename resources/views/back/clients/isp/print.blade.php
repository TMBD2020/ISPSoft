<table style="border-collapse: collapse;" border="1">
  <thead>
  <tr>
      <th>SL</th>
      <th>ID</th>
      <th>Name</th>
      <th>Mobile</th>
      <th>Address</th>
      <th>Package</th>
      <th>Discount</th>
      <th>Join Date</th>
      <th>Status</th>
  </tr>
  </thead>
    <tbody>
  @foreach($data as $key=>$client)
      <tr>
          <td>{{$key+1}}</td>
          <td style="text-align: center;">{{$client->client_id}}</td>
          <td style="">{{$client->client_name}}</td>
          <td style="text-align: center;">{{$client->cell_no}}</td>
          <td style="">{{$client->address}}</td>
          <td>{{$client->package->package_name}}-{{$client->package->package_price}}</td>
          <td style="text-align: center;">{{$client->permanent_discount}}</td>
          <td style="text-align: center;">{{$client->join_date}}</td>
          <td style="text-align: center;">
              @if($client->connection_mode==1) Active @elseif($client->connection_mode==2) Locked @else Inactive @endif</td>
      </tr>
      @endforeach
    </tbody>
</table>