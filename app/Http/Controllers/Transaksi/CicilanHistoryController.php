<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Driver;
use App\Models\Master\Truck;
use App\Models\Transaksi\Cicilan;
use App\Models\Transaksi\CicilanHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CicilanHistoryController extends Controller
{

    public function index(Request $request)
    {
        $domain = Auth::user()->domain;

        $listtruck = Truck::query()->with('getDomain');
        $listdriver = Driver::get();

        $cicilan = Cicilan::query()
                        ->with(['getTotalPaidActive',
                                'getDriverNopol.getTruck.getDomain']);

        if($domain){
            $listtruck->whereRelation('getDomain','id',$domain);
            $cicilan->whereRelation('getDriverNopol.getTruck.getDomain','id',$domain);
        }

        if($request->truck){
            $cicilan->whereRelation('getDriverNopol','dn_truck_id',$request->truck);
        }

        if($request->driver){
            $cicilan->whereRelation('getDriverNopol','dn_driver_id',$request->driver);
        }

        $listtruck = $listtruck->get();
        $cicilan = $cicilan->where('cicilan_is_active',1)
                           ->sortable()
                           ->paginate(10);


        return view('transaksi.cicilan-bayar.index',compact('cicilan','listtruck','listdriver'));
    }

    public function edit($id)
    {
        $cicilan = Cicilan::with(['getTotalPaid','getDriverNopol.getTruck','getDriverNopol.getDriver'])->findOrFail($id);
        $totalbayar = $cicilan->getTotalPaidActive->sum('hc_nominal');

        return view('transaksi.cicilan-bayar.edit',compact('cicilan','totalbayar'));
    }

    public function update(Request $request)
    {
        $historycicilan = new CicilanHistory();
        $historycicilan->hc_cicilan_id = $request->idmaster;
        $historycicilan->hc_remarks = $request->remark;
        $historycicilan->hc_eff_date = $request->effdate;
        $historycicilan->hc_nominal = str_replace(',','',$request->nominal);
        $historycicilan->save();

        alert()->success('Success', 'Data Saved')->persistent('Dismiss');
        return back();
    }

    public function updatehistorycicilan(Request $request)
    {
        $histcicilan = CicilanHistory::findOrFail($request->detid);
        $histcicilan->hc_eff_date = $request->deteffdate;
        $histcicilan->hc_nominal = str_replace(',','',$request->dettotpaid);
        $histcicilan->hc_remarks = $request->detremark;
        $histcicilan->hc_is_active = $request->detisactive;
        $histcicilan->save();
        
        alert()->success('Success', 'Data Updated')->persistent('Dismiss');
        return back();
    }
}
