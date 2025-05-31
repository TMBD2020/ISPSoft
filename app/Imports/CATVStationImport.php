<?php

namespace App\Imports;

use App\Models\Zones;
use App\Models\SubZones;
use App\Models\CatvPackages;
use App\Models\IdPrefixs;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CATVStationImport implements  ToCollection,WithHeadingRow
{

    public function collection(Collection $rows){

        $myZone = [];
        $idPrefix = [];

        foreach ($rows as $row) {
            $myZone[] = trim($row["zone_name"]);
            $idPrefix[] = trim($row['id_prefix']);
            if($row['package_name'] && $row['package_price']){
                CatvPackages::create([
                    "name"=>$row['package_name'],
                    "price"=>$row['package_price']
                ]);
            }
        }

        $zones = array_unique($myZone);

        if(count($zones)>0){
            foreach ($zones as $key=>$zone) {
                if($zone){

                    $zoneCount = Zones::query()->where(
                        [
                            "zone_name_en"=>$zone
                        ]
                    )->count();
                    if($zoneCount==0) {
                        $zone_data = Zones::create([
                            "zone_name_en" => $zone,
                            "area_incharge" => 0,
                            "technician_id" => 0,
                            "ref_network_id" => 0,
                            "pop_id" => 0,
                            "zone_type" => 2
                        ]);
                    } else {
                        $zone_data = Zones::query()->where(
                            [
                                "zone_name_en"=>$zone
                            ]
                        )->first();
                    }
                    if($zone_data){

                        if(count($idPrefix)>0){
                            if($idPrefix[$key]){
                                $prefixCount = IdPrefixs::query()->where(
                                    [
                                        "id_prefix_name"=>$idPrefix[$key]
                                    ]
                                )->count();
                                if($prefixCount==0){
                                    IdPrefixs::create([
                                        "id_prefix_name"=>$idPrefix[$key],
                                        "initial_id_digit"=>1,//start
                                        "ref_id_type_name"=>4,//catv
                                        "zone_id"=>$zone_data->id
                                    ]);
                                }
                            }
                        }
                        foreach ($rows as $row) {
                            if($zone==trim($row["zone_name"])){



                                if($row['sub_zone_name']){
                                    SubZones::create([
                                        "ref_zone_id"=>$zone_data->id,
                                        "sub_zone_name"=>$row['sub_zone_name'],
                                        "thana"=>$row['thana'],
                                        "sub_zone_location"=>$row['location']
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

    }
}
