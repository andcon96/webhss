<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Kerusakan;
use App\Models\Master\Truck;
use App\Models\Transaksi\KerusakanMstr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KerusakanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $kerusakan = Kerusakan::get();
        $data = Kerusakan::query();
        if($request->s_kerusakan){
            $data->where('id',$request->s_kerusakan);
        }
        $quernumber = Kerusakan::where('kerusakan_code','like','KR%')->orderBy('kerusakan_code','desc')->first();
        $lastnumber = '';
        
        if($quernumber){
            
            $number = $quernumber->kerusakan_code;
            
            $strangka = substr($number,(strpos($number,'R')+1),strlen($number));
            
            $newangka = (string)((int)$strangka +1);
            $selisihangka = 4 - strlen($newangka);
            $lastnumber = 'KR';
            for($i = 0; $i < $selisihangka; $i++){
                $lastnumber .= '0';
                if($i == $selisihangka - 1){
                    $lastnumber .= $newangka;
                }
            }
            
        }
        else{
            $lastnumber = 'KR0001';
        }
        
        $data = $data->paginate(10);

        return view('setting.kerusakan.index',compact('data','kerusakan','lastnumber'));
    }

    public function store(Request $request)
    {
        $cekkerusakan = Kerusakan::where('kerusakan_code',$request->code)->first();
        if(isset($cekkerusakan)){
            alert()->error('Error', 'Data Exist');
            return back();
        }
        DB::beginTransaction();
        try{
            $newdata = new Kerusakan();
            $newdata->kerusakan_code = $request->code;
            $newdata->kerusakan_desc = $request->desc;
            $newdata->kerusakan_need_approval = $request->appr;
            $newdata->save();

            DB::commit();
            alert()->success('Success', 'Kerusakan behasil dibuat');
        }catch(Exception $e){
            DB::rollback();
            alert()->error('Error', 'Kerusakan gagal dibuat');
            return back();
        }

        return back();
    }

    public function update(Request $request, Kerusakan $kerusakan)
    {
        try{
            $newdata = Kerusakan::findOrFail($request->edit_id);
            $newdata->kerusakan_desc = $request->e_desc;
            $newdata->kerusakan_need_approval = $request->e_appr;
            $newdata->save();

            DB::commit();
            alert()->success('Success', 'Kerusakan berhasil di edit');
        }catch(Exception $e){
            DB::rollback();
            alert()->error('Error', 'Gagal mengedit kerusakan');
        }

        return back();
    }

    public function destroy(Request $request)
    {
        try{
            $newdata = Kerusakan::findOrFail($request->temp_id);

            $newstat = 0;
            $newdata->kerusakan_is_active == 0 ? $newstat = 1 : $newstat = 0;
            $newdata->kerusakan_is_active = $newstat;
            
            $newdata->save();

            DB::commit();
            alert()->success('Success', 'Kerusakan berhasil di aktif / non-aktif');
        }catch(Exception $e){
            DB::rollback();
            alert()->error('Error', 'Kerusakan gagal di aktif / non-aktif kan');
        }

        return back();
    }

    // public function loaddatafromexcel(){

    //     if (($open = fopen(public_path() . "/Book2.csv", "r")) !== FALSE) {

    //         while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
    //             $history[] = $data;
    //         }
    //         $quernumber = Kerusakan::where('kerusakan_code','like','KR%')->orderBy('kerusakan_code','desc')->first();
    //         $rutearray = [];
    //         $tipetruck = '';
    //         foreach($history as $histories){
    //             $truck = Truck::where('truck_no_polis',$histories[0])->first();
    //             if($truck){
    //                 $truckid = $truck->id;
    //                 $kerusakan = KerusakanMstr::where('kr_truck',$truckid)->where('kr_date',$histories[2])->first();
    //                 if($kerusakan){

    //                 }
    //                 else{
    //                     KerusakanMstr::
    //                 }
    //             }
    //             if(!empty($histories[2])){
    //                 $tipebefore = str_replace('*','"',substr($histories[0],-3));
    //                 $tipeid = TipeTruck::where('tt_code',$tipebefore)->first();
    //                 if(isset($tipeid)){
    //                     $shipto = CustomerShipTo::where('cs_shipto','like','%'.$histories[2])->get();
    //                     if(count($shipto) > 0){
                            
    //                         foreach($shipto as $st){
    //                             $rute = Rute::where('rute_tipe_id',$tipeid->id)->where('rute_customership_id',$st->id)->get();
    //                             foreach ($rute as $rt){
    //                                 $rutearray[] = [
    //                                     'history_rute_id'       => $rt->id,
    //                                     'history_harga'         => 0,
    //                                     'history_sangu'         => (int)$histories[3],
    //                                     'history_ongkos'        => (int)$histories[4],
    //                                     'history_is_active'     => 1,
    //                                     'history_last_active'   => Carbon::now()->toDateTimeString(),
    //                                     'history_user'          => 1,
    //                                     'created_at'            => Carbon::now()->toDateTimeString(),
    //                                     'updated_at'            => Carbon::now()->toDateTimeString()
    //                                 ];
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //             RuteHistory::insert($rutearray);
    //             $rutearray = [];
    //         }
            
    //         fclose($open);
            
    //     }
    
    // }
}
