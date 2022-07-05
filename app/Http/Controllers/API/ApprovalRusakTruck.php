<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\KerusakanMstr;
use App\Services\QxtendServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ApprovalRusakTruck extends Controller
{
    public function receiveAPI($wonbr,$rusaknbr,$nopolnbr,$status){
        
        $rusaknbr = Crypt::decrypt($rusaknbr);
        $nopolnbr = Crypt::decrypt($nopolnbr);
        $status = Crypt::decrypt($status);
        $wonbr = $wonbr;
        
        $errorlist = [];
        $status_display = '';
        $message = '';
        
        if($status == 'yes'){
            
            $checkdata = KerusakanMstr::where('kr_nbr',$rusaknbr)->firstOrFail();
            
            if($checkdata->status != 'Need Approval'){
                $errorlist = [];
                $status = 'ERROR';
                $message = 'Kerusakan sudah di approve/reject';
                
            }
            elseif(!$checkdata){
                $errorlist = [];
                $status = 'ERROR';
                $message = 'Data kerusakan tidak ditemukan';
                
            }
            else{
                $qxrusak = (new QxtendServices())->qxWOkerusakan($rusaknbr,$nopolnbr);
                
                if($qxrusak[0] == false){
                    
                    if(isset($qxrusak[1])){
                        
                        $errorlist = $qxrusak[1];
                        $status = 'ERROR';
                        $message = 'Approval gagal, terdapat kesalahan data pada QAD, mohon diperbaiki dan approve ulang. Error message :';
                        
                    }   
                    else{
                        $status = 'ERROR';
                        $errorlist = ['Error Qxtend Connection'];
                        $message = '';
                        
                    }        
                }
                elseif($qxrusak[0] == true){
                    $checkdata = KerusakanMstr::where('kr_nbr',$rusaknbr)->firstOrFail();
                    DB::beginTransaction();
                    try{
                        $checkdata->kr_status = 'Done';
                        $checkdata->save();
                        DB::commit();
                        $status = 'SUCCESS';
                        $errorlist = ['Kerusakan berhasil di setujui'];
                        $message = '';
                    }
                    catch(Exception $er){
                        DB::rollBack();
                        $status = 'ERROR';
                        $errorlist = ['Status kerusakan gagal di update '];
                        $message = '';                        
                    }

                    
                }
            }
        }
            
        elseif($status == 'no'){
            
            $checkdata = KerusakanMstr::where('kr_nbr',$rusaknbr)->firstOrFail();
            
            if($checkdata->status != 'Need Approval'){
                $errorlist = [];
                $status = 'ERROR';
                $message = 'Kerusakan sudah di approve/reject';
                
            }
            DB::beginTransaction();
            try{
                $checkdata->kr_status = 'Reject';
                $checkdata->save();
                DB::commit();
                $status_display = 'SUCCESS';
                $errorlist = ['Reject approval success'];
                $message = '';
                
            }
            catch(Exception $e){
                DB::rollBack();
                $status = 'ERROR';
                $errorlist = ['Terjadi kesalahan, mohon diulang kembali'];
                $message = '';
                
            }

        }
        return view('publicview.APIresult', compact('errorlist','status_display','message'));
    }       
}
