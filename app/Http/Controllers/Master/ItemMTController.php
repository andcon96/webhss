<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Domain;
use App\Models\Master\Item;
use App\Services\WSAServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemMTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $item = Item::query();
        $listitem = Item::get();

        if($request->s_itemcode){
            $item->where('id',$request->s_itemcode);
        }

        $item = $item->paginate(10);

        return view('setting.item.index',compact('item','listitem'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $domain = Domain::get();
            
            $loadcust = (new WSAServices())->wsaitem();
            if($loadcust === false){
                alert()->error('Error', 'No Data from QAD');
                DB::rollback();
                return back();
            }
            alert()->success('Success', 'Customer Data Loaded');
            DB::commit();
            return back();
        }catch(Exception $err){
            alert()->error('Error', 'WSA Failed');
            DB::rollback();
            return back();
        }
    }

   
}
