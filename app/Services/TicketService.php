<?php
/**
 * Created by PhpStorm.
 * User: iQ
 * Date: 4/30/2022
 * Time: 11:19 PM
 */

namespace App\Services;
use App\Models\Tickets;
use App\Models\Packages;


class TicketService
{
    public function generateTicketPackageChange($clientId,$newPackageId,$oldPackageId){

        $newPackage=Packages::find($newPackageId);
        $oldPackage=Packages::find($oldPackageId);
        $complain= "New Package : ".$newPackage->download ."<br>
        D:".$newPackage->download.", U:".$newPackage->upload.", Y:".$newPackage->youtube.", Q:".$newPackage->que_type;

        $complain .= "Current Package : ".$oldPackage->package ."<br>
        D:".$oldPackage->download.", U:".$oldPackage->upload.", Y:".$oldPackage->youtube.", Q:".$oldPackage->que_type;

        $ticket["ticket_no"]    = date("d").$clientId.date("is");
        $ticket["ref_client_id"]          = $clientId;//auth_id
        $ticket["ref_department_id"]      = 0;
        $ticket["subject"]                = "Package Change";
        $ticket["complain"]               = $complain;
        $ticket["opened_by"]              = auth()->user()->id;
        $ticket["company_id"]              = \Settings::company_id();
        $ticket["ticket_type"]            = "package_change";
        $ticket["ticket_datetime"]        = date("Y-m-d H:i:s");
        $ticket["created_at"]             = date("Y-m-d H:i:s");

       return Tickets::query()->insert($ticket);
    }
}