<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use Illuminate\Http\Request;

class InvoicePriceController extends Controller
{
    public function index(Request $request){
        $customer = Customer::query();
        $listcust = Customer::all();

        if($request->s_custcode){
            $customer->where('id', $request->s_custcode);
        }

        $customer = $customer->paginate(10);

        return view('setting.invoice-price.index', compact('customer','listcust'));
    }

    public function listDetail($id){
        dd($id);
    }
}
