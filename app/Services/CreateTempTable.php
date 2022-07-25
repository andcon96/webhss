<?php

namespace App\Services;

use App\Models\Master\Domain;
use App\Models\Master\Prefix;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class CreateTempTable
{
    public function createPOTemp($data){
        // WSA -> LMA_getPO
        Schema::create('temp_group', function ($table) {
            $table->string('po_nbr');
            $table->string('po_cust');
            $table->string('pod_line');
            $table->string('pod_part');
            $table->string('pod_qty_ord');
            $table->string('pod_qty_rcvd');
            $table->temporary();
        });

        foreach($data as $datas){
            DB::table('temp_group')->insert([
                'po_nbr' => $datas->t_ponbr,
                'po_cust' => $datas->t_cust,
                'pod_line' => $datas->t_line,
                'pod_part' => $datas->t_part,
                'pod_qty_ord' => $datas->t_ord,
                'pod_qty_rcvd' => $datas->t_rcvd,
            ]);
        }

        $table_po = DB::table('temp_group')->get();

        Schema::dropIfExists('temp_group');

        return $table_po;
    }    

    public function getrnso(){
        try{
            $prefix = Prefix::firstOrFail();
            
            $cektahun = substr($prefix->prefix_so_rn,0,1);
            $yearnow = date('y');
            $lastdigityear = $yearnow % 10;
            
            if($cektahun != $lastdigityear){
                $rn_new = $lastdigityear.'00001';
            }else{
                $rn_new = $prefix->prefix_so_rn + 1;
            }
            $newprefix = $prefix->prefix_so.$rn_new;
    
            return $newprefix;
        }catch(Exception $e){
            return false;
        }
    }

    public function getrnkerusakan(){
        try{
            // $prefix = Prefix::firstOrFail();
            
            $prefix = Domain::where('domain_code',Session::get('domain'))->firstOrFail();
            
            $cektahun = substr($prefix->domain_kr_rn,0,2);
            $yearnow = date('y');
            
            if($cektahun != $yearnow){
                $rn_new = $yearnow.'0001';
            }else{
                $rn_new = $prefix->domain_kr_rn + 1;
            }
            $newprefix = $prefix->domain_kr_prefix.$rn_new;
    
            return $newprefix;
        }catch(Exception $e){
            
            return false;
        }
    }

    public function getrnco(){
        try{
            $prefix = Prefix::firstOrFail();
            
            $cektahun = substr($prefix->prefix_co_rn,0,1);
            $yearnow = date('y');
            $lastdigityear = $yearnow % 10;
            
            if($cektahun != $lastdigityear){
                $rn_new = $lastdigityear.'00001';
            }else{
                $rn_new = $prefix->prefix_co_rn + 1;
            }
            $newprefix = $prefix->prefix_co.$rn_new;
    
            return $newprefix;
        }catch(Exception $e){
            return false;
        }
    }

    public function getrnsj(){
        try{
            $prefix = Prefix::firstOrFail();
            
            $cektahun = substr($prefix->prefix_sj_rn,0,1);
            $yearnow = date('y');
            $lastdigityear = $yearnow % 10;
            
            if($cektahun != $lastdigityear){
                $rn_new = $lastdigityear.'00001';
            }else{
                $rn_new = $prefix->prefix_sj_rn + 1;
            }
            $newprefix = $prefix->prefix_sj.$rn_new;
    
            return $newprefix;
        }catch(Exception $e){
            return false;
        }
    }
}
