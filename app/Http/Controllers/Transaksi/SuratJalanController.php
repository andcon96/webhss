<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\Domain;
use App\Models\Master\Item;
use App\Models\Master\Prefix;
use App\Models\Master\Rute;
use App\Models\Master\Truck;
use App\Models\Transaksi\SalesOrderDetail;
use App\Models\Transaksi\SalesOrderMstr;
use App\Models\Transaksi\SJHistTrip;
use App\Models\Transaksi\SuratJalan;
use App\Models\Transaksi\SuratJalanDetail;
use App\Services\CreateTempTable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SuratJalanController extends Controller
{
    public function index(Request $request)
    {
        $listcust = Customer::get();
        $listsj = SuratJalan::get();
        $listso = SalesOrderMstr::get();
        $truck = Truck::get();

        $data = SuratJalan::query()
                    ->with('getSOMaster.getCOMaster.getCustomer','getTruck','getSOMaster.getShipFrom','getSOMaster.getShipTo');

        if($request->sjnumber){
            $data->where('id',$request->sjnumber);
        }

        if($request->sonumber){
            $data->whereRelation('getSOMaster','sj_so_mstr_id',$request->sonumber);
        }

        if($request->s_customer){
            $data->whereRelation('getSOMaster.getCOMaster','co_cust_code',$request->s_customer);
        }

        if($request->s_truck){
            $data->where('sj_truck_id',$request->s_truck);
        }

        if($request->datefrom){
            $data->where('sj_eff_date','>=',$request->datefrom);
        }

        if($request->dateto){
            $data->where('sj_eff_date','<=',$request->dateto);
        }


        $data = $data->paginate(10);
        
        return view('transaksi.sj.index',compact('data','listcust','listso','listsj','truck'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $getSJ = (new CreateTempTable())->getrnsj();
            if($getSJ === false){
                alert()->error('Error', 'Gagal mengambil nomor SJ')->persistent('Dismiss');
                return back();
            }

            $sjmstr = new SuratJalan();
            $sjmstr->sj_so_mstr_id = $request->soid;
            $sjmstr->sj_nbr = $getSJ;
            $sjmstr->sj_eff_date = Carbon::now()->toDateString();
            $sjmstr->sj_remark = $request->remark;
            // $sjmstr->sj_status = "Open";
            $sjmstr->sj_status = "Selesai";
            $sjmstr->sj_truck_id = $request->truck;
            $sjmstr->sj_jmlh_trip = $request->trip;
            $sjmstr->sj_tot_sangu = str_replace(',','',$request->totsangu);
            $sjmstr->sj_default_sangu = str_replace(',','',$request->defaultsangu);
            $sjmstr->sj_default_sangu_type = $request->defaultpriceid;
            $sjmstr->save();

            $id = $sjmstr->id;
            foreach($request->line as $key => $datas){
                if($request->qtysj[$key] > 0){
                    $sjdet = new SuratJalanDetail();
                    $sjdet->sjd_sj_mstr_id = $id;
                    $sjdet->sjd_line = $datas;
                    $sjdet->sjd_part = $request->part[$key];
                    $sjdet->sjd_qty_ship = $request->qtysj[$key];
                    $sjdet->sjd_qty_conf = 0;
                    $sjdet->save();

                    $sodet = SalesOrderDetail::query()
                                            ->where('sod_so_mstr_id',$request->soid)
                                            ->where('sod_line',$datas)
                                            ->where('sod_part',$request->part[$key])
                                            ->firstOrFail();
                    if($sodet->sod_qty_ship + $request->qtysj[$key] > $sodet->sod_qty_ord){
                        DB::rollBack();
                        alert()->error('Error', 'Data gagal disimpan, Qty sudah berubah')->persistent('Dismiss');
                        return back();
                    }
                    $sodet->sod_qty_ship = $sodet->sod_qty_ship + $request->qtysj[$key];
                    $sodet->save();
                }
            }
            
            $prefix = Prefix::firstOrFail();
            $prefix->prefix_sj_rn = substr($getSJ,2,6);
            $prefix->save();

            $newdata = new SJHistTrip();
            $newdata->sjh_sj_mstr_id = $id;
            $newdata->sjh_truck = $request->truck;
            $newdata->save();

            DB::commit();
            alert()->success('Success', 'Surat Jalan : '.$getSJ.' berhasil dibuat')->persistent('Dismiss');
            return back();
        }catch(Exception $e){
            DB::rollBack();
            alert()->error('Error', 'Surat Jalan gagal dibuat')->persistent('Dismiss');
            return back();
        }
    }

    public function edit($id)
    {
        $data = SuratJalan::with('getSOMaster.getCOMaster.getCustomer','getSOMaster.getDetail','getSOMaster.getShipTo','getSOMaster.getShipFrom','getDetail','getRuteHistory')->findOrFail($id);
        $this->authorize('update',[SuratJalan::class, $data]);
        $item = SalesOrderDetail::where('sod_so_mstr_id',$data->sj_so_mstr_id)->get();
        
        return view('transaksi.sj.edit',compact('data','item'));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try{
            $sjmstr = SuratJalan::findOrFail($request->idmaster);
            $this->authorize('update',[SuratJalan::class, $sjmstr]);
            $sjmstr->sj_default_sangu = str_replace(',','',$request->defaultsangu);
            $sjmstr->sj_tot_sangu = str_replace(',','',$request->totsangu);
            $sjmstr->sj_jmlh_trip = $request->trip;
            $sjmstr->save();
            
            foreach($request->iddetail as $key => $datas){
                $sjdet = SuratJalanDetail::firstOrNew(['id'=>$datas]);
                if($request->operation[$key] == 'R'){
                    $sjdet->delete();
                }else{
                    $sjdet->sjd_sj_mstr_id = $request->idmaster;
                    $sjdet->sjd_line = $request->line[$key];
                    $sjdet->sjd_part = $request->part[$key];
                    $sjdet->sjd_qty_ship = $request->qtyord[$key];
                    $sjdet->sjd_qty_conf = 0;
                    $sjdet->save();
                }

                $soddet = SalesOrderDetail::lockForUpdate()->findOrFail($request->idsodetail[$key]);
                if($request->operation[$key] == 'R'){
                    $soddet->sod_qty_ship = $soddet->sod_qty_ship - $request->qtyold[$key];
                    $soddet->save();
                }else{
                    if($soddet->sod_qty_ship - $request->qtyold[$key] + $request->qtyord[$key] > $soddet->sod_qty_ord){
                        alert()->error('Error', 'Save Gagal, Qty Sales Order berubah')->persistent('Dismiss');
                        DB::rollback();
                        return back();
                    }
                    $soddet->sod_qty_ship = $soddet->sod_qty_ship + $request->qtyord[$key] - $request->qtyold[$key];
                    $soddet->save();
                }
            }
            
            DB::commit();
            alert()->success('Success', 'Surat Jalan berhasil diupdate')->persistent('Dismiss');
            return back();
        }catch(Exception $e){
            DB::rollBack();
            alert()->error('Error', 'Surat Jalan gagal diupdate')->persistent('Dismiss');
            return back();
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->temp_id;

        DB::beginTransaction();

        try{
            $sjmstr = SuratJalan::findOrFail($id);
            $this->authorize('delete',[SuratJalan::class, $sjmstr]);
            
            $sjddet = SuratJalanDetail::where('sjd_sj_mstr_id',$id)->get();
            foreach($sjddet as $key => $sjddets){
                $soddet = SalesOrderDetail::query()
                                    ->where('sod_line',$sjddets->sjd_line)
                                    ->where('sod_part',$sjddets->sjd_part)
                                    ->where('sod_so_mstr_id',$sjmstr->sj_so_mstr_id)
                                    ->firstOrFail();
                $soddet->sod_qty_ship = $soddet->sod_qty_ship - $sjddets->sjd_qty_ship;
                $soddet->save();
            }
            
            $sjmstr->sj_status = 'Cancelled';
            $sjmstr->save();
            DB::commit();
            alert()->success('Success', 'Surat Jalan Deleted Successfully')->persistent('Dismiss');
        }catch (\Exception $err) {
            DB::rollBack();
            alert()->error('Error', 'Failed to delete Surat Jalan')->persistent('Dismiss');
        }

        return redirect()->route('suratjalan.index');
    }

    public function getdetail($id)
    {
        $data = SuratJalanDetail::with('getItem')->where('sjd_sj_mstr_id',$id)->get();
        $output = '<tr><td colspan="7"> No Data Avail </td></tr>';
        if($data->count() > 0){
            $output = '';
            foreach($data as $datas){
                $output .= '<tr>';
                $output .= '<td>'.$datas->sjd_line.'</td>';
                $output .= '<td>'.$datas->sjd_part.' - '.$datas->getItem->item_desc.'</td>';
                $output .= '<td>'.$datas->getItem->item_um.'</td>';
                $output .= '<td>'.$datas->sjd_qty_ship.'</td>';
                $output .= '<td>'.$datas->sjd_qty_angkut.'</td>';
                $output .= '<td>'.$datas->sjd_qty_conf.'</td>';
                $output .= '<td>'.$datas->sjd_price.'</td>';
                $output .= '</tr>';
            }
        }

        return response($output);
    }

    public function getdetailso($id)
    {
        $data = SalesOrderDetail::with('getItem')->where('sod_so_mstr_id',$id)->get();
        $output = '<tr><td colspan="7"> No Data Avail </td></tr>';
        if($data->count() > 0){
            $output = '';
            foreach($data as $datas){
                $qtysisa = $datas->sod_qty_ord - $datas->sod_qty_ship;
                if((int)$qtysisa > 25000){
                    $qtyopen = 25000;
                }else{
                    $qtyopen = $datas->sod_qty_ord - $datas->sod_qty_ship;
                }

                $qtyopen <= 0 ? $status = 'disabled' : $status = '';
                
                $output .= '<tr>';
                $output .= '<td data-label="Line">'.$datas->sod_line.'</td>';
                $output .= '<td data-label="Item">'.$datas->sod_part.' - '.$datas->getItem->item_desc.'</td>';
                $output .= '<td data-label="UM">'.$datas->getItem->item_um.'</td>';
                $output .= '<td data-label="Qty Ord">'.(int)$datas->sod_qty_ord.'</td>';
                $output .= '<td data-label="Qty Open">'.$qtysisa.'</td>';
                $output .= '<td data-label="Qty SJ">
                            <input type="number" name="qtysj[]" max="'.$qtyopen.'" 
                                    value="'.$qtyopen.'" required class="form-control qtysj" '.$status.'>
                            <input type="hidden" '.$status.' name="line[]" value="'.$datas->sod_line.'">
                            <input type="hidden" '.$status.' name="part[]" value="'.$datas->sod_part.'">        
                            </td>';
                $output .= '</tr>';
            }
        }

        return response($output);
    }

    public function getrute(Request $request)
    {
        $data = Rute::query()
                    ->where('rute_tipe_id',$request->tipetruck)
                    ->where('rute_shipfrom_id',$request->shipfrom)
                    ->where('rute_customership_id',$request->shipto)
                    ->with('getAllActivePrice')
                    ->first();
                    
        return $data->getAllActivePrice ?? 0;
        
    }

    public function socreatesj(Request $request)
    {
        $data = SalesOrderMstr::with('getDetail.getItem','getCOMaster.getCustomer','getShipFrom','getShipTo')->findOrFail($request->id);
        $item = Item::get();
        $cust = Customer::get();
        $truck = Truck::with('getUserDriver','getUserPengurus')->get();
        
        return view('transaksi.salesorder.suratjalan.create',compact('data','item','cust','truck'));
    }
    
    public function createsj()
    {
        $listso = SalesOrderMstr::with('getCOMaster.getCustomer','getShipFrom','getShipTo')
                            ->whereIn('so_status',['New','Open'])
                            ->get();
        $item = Item::get();
        $truck = Truck::with('getUserDriver','getUserPengurus')->get();

        return view('transaksi.sj.createsj.create',compact('listso','truck'));
    }
}
