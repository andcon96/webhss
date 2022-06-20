<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\Item;
use App\Models\Master\Truck;
use App\Models\Transaksi\CustomerOrderMstr;
use App\Models\Transaksi\SalesOrderMstr;
use App\Models\Transaksi\SuratJalan;
use App\Models\Transaksi\SuratJalanDetail;
use Illuminate\Http\Request;

class SuratJalanController extends Controller
{
    public function index(Request $request){
        $listcust = Customer::get();
        $listco = CustomerOrderMstr::get();

        $data = SuratJalan::query();

        $data = $data->with('getSOMaster.getCustomer','getTruck')->paginate(10);
        
        return view('transaksi.sj.index',compact('data','listcust','listco'));
    }

    public function create(Request $request)
    {
        $data = SalesOrderMstr::with('getDetail.getItem')->findOrFail($request->id);
        $item = Item::get();
        $cust = Customer::get();
        $truck = Truck::with('getActiveDriver.getUser','getActiveDriver.getPengurus')->get();

        return view('transaksi.sj.create',compact('data','item','cust','truck'));
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
                $output .= '<td>EA</td>';
                $output .= '<td>'.$datas->sjd_qty_ship.'</td>';
                $output .= '</tr>';
            }
        }

        return response($output);
    }
}
