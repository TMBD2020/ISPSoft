<?php

namespace App\Services;

use App\Models\Microtiks;
use App\Models\Boxes;
use App\Models\Nodes;
use App\Models\Zones;
use App\Models\Pops;
use App\Models\PopCategories;
use App\Models\PaymentMethods;
use App\Models\Packages;
use App\Models\IdPrefixs;
use App\Models\Departments;
use App\Models\Designations;
use App\Models\Employees;
use App\Models\EmpSalarys;
use Illuminate\Foundation\Auth\User;
use Hash;
use DB;
class DefaultService
{
    public function DefalutDataSave($companyId)
    {
        // $companyId = \Settings::company_id();
        if($companyId)
        {           
            $network=Microtiks::where(["network_name"=>"Default","company_id"=>$companyId])->count();
            if($network==0){
                $network=new Microtiks();
                $network->company_id=$companyId;
                $network->network_name="Default";
                $newtork_id=$network->save();
            }           

            $popCat=PopCategories::where(["pop_category_name"=>"Default","company_id"=>$companyId])->count();
            if($popCat==0){
                $popCat=new PopCategories();
                $popCat->company_id=$companyId;
                $popCat->pop_category_name="Default";
                $popCatId=$popCat->save();
            }

            $pop=Pops::where(["pop_name"=>"Default","company_id"=>$companyId])->count();
            if($pop==0){
                $pop=new Pops();
                $pop->company_id=$companyId;
                $pop->ref_network_id=$newtork_id->id;
                $pop->ref_cat_id=$popCatId->id;
                $pop->pop_name="Default";
                $popId=$pop->save();
            }
            $zone=Zones::where(["zone_name_en"=>"Default","company_id"=>$companyId])->count();
            if($zone==0){
                $zone=new Zones();
                $zone->company_id=$companyId;
                $zone->ref_network_id=$newtork_id->id;
                $zone->pop_id=$popId;
                $zone->zone_name_en="Default";
                $zone_id=$zone->save();
            }

            $node=Nodes::where(["node_name"=>"Default","company_id"=>$companyId])->count();
            if($node==0){
                $node=new Nodes();
                $node->company_id=$companyId;
                $node->ref_zone_id=$zone_id;
                $node->node_name="Default";
                $node_id=$node->save();
            }

            $box=Boxes::where(["box_name"=>"Default","company_id"=>$companyId])->count();
            if($box==0){
                $box=new Boxes();
                $box->company_id=$companyId;
                $box->ref_node_id=$node_id->id;
                $box->box_name="Default";
                $box->save();
            }
            $package=Packages::where(["package_name"=>"Default","company_id"=>$companyId])->count();
            if($package==0){
                $package=new Packages();
                $package->company_id=$companyId;
                $package->profile_name="Default";
                $package->package_name="Default";
                $package->package_price="600";
                $package->download="10";
                $package->upload="10";
                $package->youtube="10";
                $package->que_type="10";
                $package->package_type="client";
                $package->save();
            }

            $pay=PaymentMethods::where(["payment_name"=>"Cash","company_id"=>$companyId])->count();
            if($pay==0){
                $pay=new PaymentMethods();
                $pay->company_id=$companyId;
                $pay->payment_name="Cash";
                $pay->save();
            }
            $dept_id=(object) ["id"=>0];
            $dept=Departments::where(["department_name"=>"Default","company_id"=>$companyId])->count();
            if($dept==0){
                $dept=new Departments();
                $dept->company_id=$companyId;
                $dept->department_name="Default";
                $dept_id=$dept->save();
            }
            $desig_id=(object) ["id"=>0];
            $desig=Designations::where(["designation_name"=>"Default","company_id"=>$companyId])->count();
            if($desig==0){
                $desig=new Designations();
                $desig->company_id=$companyId;
                $desig->designation_name="Default";
                $desig_id=$desig->save();
            }

            $prefix=IdPrefixs::where(["id_prefix_name"=>"TM$companyId","company_id"=>$companyId,"ref_id_type_name"=>3])->count();
            if($prefix==0){
                $prefix=new IdPrefixs();
                $prefix->company_id=$companyId;
                $prefix->ref_id_type_name=3;//emp
                $prefix->id_prefix_name="TM$companyId";
                $prefix->initial_id_digit=100;
                $prefix->save();  
            }
            // DB::beginTransaction();
            // try {
                $user=new User();
                $user->company_id=$companyId;
                $user->name="Default";
                $user->user_id="TM$companyId". 101;
                $user->email="TM$companyId". 101;
                $user->password=Hash::make("123456");
                $user->is_admin="2";
                $user->user_type="emp";
                $users=$user->save();

                if($users){
                    $emp=new Employees();
                    $emp->company_id=$companyId;
                    $emp->auth_id=$users->id;
                    $emp->emp_id= $user->user_id;
                    $emp->emp_name=$user->name;
                    $emp->emp_department_id=$dept_id->id;
                    $emp->emp_designation_id=$desig_id->id;
                    $emp_id=$emp->save();
                    if($emp_id){
                        $salary=new EmpSalarys();
                        $salary->emp_id=$emp_id->id;
                        $salary->save();
                    }
                }
            //     DB::commit();
            // } catch (\Exception $e) {
            //     DB::rollback();
            // }
        }
    }
}