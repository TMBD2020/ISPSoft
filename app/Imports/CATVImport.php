<?php

namespace App\Imports;

use App\Models\CatbClients;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CATVImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $userId = User::create([
            "user_id"=>$row['client_id'],
            "name"=>$row['client_name'],
            "email"=>$row['client_id'],
            "is_admin"=>0,
            "user_type"=>'catb_client',
            "password"=>'123123'
        ]);

        return new CatbClients(
            [
                'auth_id'     => $userId->id,
                'client_id'    => $row['client_id'],
                'client_name'    => $row['client_name'],
                'home_card_no'    => $row['home_card_no'],
                'zone_id'    => $row['zone_id'],
                'cell_no'    => $row['cell_no'],
                'payment_dateline'    => $row['payment_dateline'],
                'payment_id'    => $row['payment_id']
            ]
        );
    }
}
