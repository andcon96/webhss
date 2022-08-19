<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\KerusakanMstr;
use App\Services\QxtendServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            $krdate = $checkdata->kr_date;
            if($checkdata->kr_status != 'Need Approval'){
                $errorlist = [];
                $status_display = 'ERROR';
                $message = 'Kerusakan '.$rusaknbr.' sudah di approve/reject';
                
            }
            elseif(!$checkdata){
                $errorlist = [];
                $status_display = 'ERROR';
                $message = 'Data kerusakan tidak ditemukan';
                
            }

            else{
                $qxrusak = (new QxtendServices())->qxWOkerusakan($rusaknbr,$nopolnbr,$krdate);
                
                if($qxrusak[0] == false){
                    
                    if(isset($qxrusak[1])){
                        
                        $errorlist = [$qxrusak[1]];
                        
                        $status_display = 'ERROR';
                        $message = 'Approval gagal, terdapat kesalahan data pada QAD, mohon diperbaiki dan approve ulang. Error message :';
                        
                    }   
                    else{
                        $status_display = 'ERROR';
                        $errorlist = [];
                        $message = 'Error Qxtend Connection';
                        
                    }        
                }
                elseif($qxrusak[0] == true){
                    $checkdata = KerusakanMstr::where('kr_nbr',$rusaknbr)->firstOrFail();
                    DB::beginTransaction();
                    try{
                        $checkdata->kr_status = 'Done';
                        $checkdata->save();
                        DB::commit();
                        $status_display = 'SUCCESS';
                        $errorlist = [];
                        $message = 'Kerusakan berhasil di approve';
                    }
                    catch(Exception $er){
                        DB::rollBack();
                        $status_display = 'ERROR';
                        $errorlist = [];
                        $message = 'Status kerusakan gagal di update ';                        
                    }

                    
                }
            }
        }
            
        elseif($status == 'no'){
            
            $checkdata = KerusakanMstr::where('kr_nbr',$rusaknbr)->firstOrFail();
            // dd($checkdata->kr_status != 'Need Approval');
            if($checkdata->kr_status != 'Need Approval'){
                $errorlist = [];
                $status_display = 'ERROR';
                $message = 'Kerusakan '.$rusaknbr.' sudah di approve/reject';
                
            }
            else{
                DB::beginTransaction();
                try{
                    $checkdata->kr_status = 'Reject';
                    $checkdata->save();
                    DB::commit();
                    $status_display = 'SUCCESS';
                    $errorlist = [];
                    $message = 'Kerusakan '.$rusaknbr.' telah di reject';
                    
                }
                catch(Exception $e){
                    DB::rollBack();
                    $status_display = 'ERROR';
                    $errorlist = [];
                    $message = 'Terjadi kesalahan, mohon diulang kembali';
                    
                }
            }
            

        }
        return view('publicview.APIresult', compact('errorlist','status_display','message'));
    }   
    
    //outbound check status wo berubah
    public function qxoutstatus(Request $request){
        $xml = simplexml_load_string($request->getContent());
        
    	$qdocFields = $xml->children('qdoc', true);
        $data = $xml->children('soapenv',true)->Body->children('qdoc',true)->WOCheckStatus->dsWo_mstr->wo_mstr->woNbr;
       
        $kerusakandata = KerusakanMstr::where('kr_nbr',$data)->where('kr_status','<>','Close')->first();
        if($kerusakandata){
            DB::beginTransaction();
            try{
                $kerusakandata->kr_status = 'Close';
                $kerusakandata->save();
                DB::commit();
            }catch(Exception $err){
                DB::rollback();
            }
            
        }
        
    	
        
		// foreach($qdocFields->purchaseOrderReceive->lineDetail as $data){
		// 	Log::channel('customlog')->info('Item Part : '. $data->sodPart);
		// }


    	// dd($xml, $qdocFields, (String) $qdocFields->purchaseOrderReceive->soCust);

    }
}
