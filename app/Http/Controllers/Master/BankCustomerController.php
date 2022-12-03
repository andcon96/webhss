<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\BankCustomer;
use App\Models\Master\Customer;
use App\Models\Master\Domain;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customer = Customer::get();
        $domain = Domain::get();

        $bankcust = BankCustomer::query()->with('getCustomer','getDomain');
        
        if($request->s_customer){
            $bankcust->where('bc_customer_id',$request->s_customer);
        }
        if($request->s_domain){
            $bankcust->where('bc_domain_id',$request->s_domain);
        }

        
        $bankcust = $bankcust->paginate(10);

        return view('setting.bankcust.index',compact('bankcust','domain','customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer = Customer::get();
        $domain = Domain::get();

        return view('setting.bankcust.create',compact('domain','customer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newacc = new BankCustomer();
        $newacc->bc_customer_id = $request->customer;
        $newacc->bc_domain_id = $request->domain;
        $newacc->bc_acc_name = $request->bankname;
        $newacc->bc_acc_nbr = $request->bankacc;
        $newacc->save();
    
        alert()->success('Success', 'Data Created');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Master\BankCustomer  $bankCustomer
     * @return \Illuminate\Http\Response
     */
    public function edit(BankCustomer $bankCustomer, $id)
    {
        $bankcust = BankCustomer::with('getCustomer','getDomain')->findOrFail($id);
        $customer = Customer::get();
        $domain = Domain::get();

        return view('setting.bankcust.edit',compact('bankcust','domain','customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master\BankCustomer  $bankCustomer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankCustomer $bankCustomer)
    {
        $bankacc = BankCustomer::findOrFail($request->idmaster);
        $bankacc->bc_customer_id = $request->customer;
        $bankacc->bc_domain_id = $request->domain;
        $bankacc->bc_acc_name = $request->bankname;
        $bankacc->bc_acc_nbr = $request->bankacc;
        $bankacc->bc_is_active = $request->active;
        $bankacc->save();
    
        alert()->success('Success', 'Data Updated');
        return back();
    }

    public function loadexcel()
    {
        if (($open = fopen(public_path() . "/bank-asa.csv", "r")) !== FALSE) {

            while (($data = fgetcsv($open, 1000, ";")) !== FALSE) {
                $history[] = $data;
            }

            $tipetruck = '';
            DB::beginTransaction();
            try{
                foreach($history as $datas){
                    $domain_id = ($datas[2] == 'AS' ? '1' :
                                 ($datas[2] == 'HSSTR' ? '2' :
                                 ($datas[2] == 'SPJS' ? '3' : 
                                 '')));
                    
                    $insertData[] = [
                        'bc_customer_id' => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $datas[0]),
                        'bc_domain_id' => $domain_id,
                        'bc_acc_name' => $datas[3],
                        'bc_acc_nbr' => str_replace(',','',$datas[4])
                    ];
                }

                BankCustomer::insert($insertData);

                DB::commit();
            }
            catch(Exception $err){
                DB::rollback();
                dd($err);
            }
            

            fclose($open);
        }
    }
}
