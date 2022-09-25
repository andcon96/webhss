<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Domain;
use App\Models\Master\GandenganMstr;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GandenganMTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $domain = Domain::get();
        $data = GandenganMstr::query();

        if($request->s_gandengan){
            $data->where('id',$request->s_gandengan);
        }

        $data = $data->paginate(10);
        $gandengmstr = GandenganMstr::get();
        return view('setting.gandengan.index',compact('gandengmstr','data','domain'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $domain = $request->c_domain;
        $gandeng = $request->c_gandeng;
        $active = $request->c_active;
        $gandengdesc = $request->c_gandengdesc;
        DB::beginTransaction();
        try{
            $gandengan = new GandenganMstr();
            $gandengan->gandeng_domain = $domain;
            $gandengan->gandeng_code = $gandeng;
            $gandengan->gandeng_desc = $gandengdesc;
            $gandengan->gandeng_is_active = $active;
            $gandengan->save();
            DB::commit();
            alert()->success('Success', 'Create gandengan success');
            return redirect()->to(route('gandengan.index'));

        }
        catch(Exception $err){
            DB::rollBack();
            
            alert()->error('Error', 'Failed to create gandengan');
            return redirect()->to($request->prevurl ?? route('gandengan.index'));


        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GandenganMstr  $gandenganMstr
     * @return \Illuminate\Http\Response
     */
    public function show(GandenganMstr $gandenganMstr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GandenganMstr  $gandenganMstr
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $data = GandenganMstr::findOrFail($id);
        $domain = Domain::groupby('domain_code')->get();
        return view('setting.gandengan.edit',['data' => $data, 'domain' => $domain]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GandenganMstr  $gandenganMstr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GandenganMstr $gandenganMstr)
    {
        DB::beginTransaction();
        try{
            GandenganMstr::where('id',$request->idcur)->update([
                'gandeng_domain'=>$request->domain,
                'gandeng_code'=> $request->e_gandeng,
                'gandeng_desc'=> $request->e_gandengdesc
            ]);
            DB::commit();
            alert()->success('Success', 'Update gandengan success');
            return redirect()->to($request->prevurl ?? route('gandengan.index'));

        }
        catch(Exception $err){
            DB::rollBack();
            alert()->error('Error', 'Update gandengan failed');
            return redirect()->to($request->prevurl ?? route('gandengan.index'));

        }

        
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GandenganMstr  $gandenganMstr
     * @return \Illuminate\Http\Response
     */
    public function destroy(GandenganMstr $gandenganMstr, Request $request)
    {
        
        $currentid = $request->temp_id;
        $datagandeng = GandenganMstr::where('id',$currentid)->first();
        DB::beginTransaction();
        try{
            if($datagandeng->gandeng_is_active == 1){
                GandenganMstr::where('id',$currentid)->update(['gandeng_is_active' => 0]);
                DB::commit();
                alert()->success('Success', 'Gandengan deactivated');
                return redirect()->to(route('gandengan.index'));
            }
            elseif($datagandeng->gandeng_is_active == 0){
                GandenganMstr::where('id',$currentid)->update(['gandeng_is_active' => 1]);
                DB::commit();
                alert()->success('Success', 'Gandengan activated');
                return redirect()->to(route('gandengan.index'));
            }
            
        }
        catch(Exception $err){
            DB::rollBack();
            
            alert()->error('Error', 'Failed to activate/deactivate gandengan');
            return redirect()->to(route('gandengan.index'));
        }
        
    }

    public function loadgandengan()
    {
        if (($open = fopen(public_path() . "/new_gandengan.csv", "r")) !== FALSE) {

            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                $gandeng[] = $data;
            }

            $gandenganarr = [];
            DB::beginTransaction();
            try{
                foreach($gandeng as $gandengan){  
                    if(!empty($gandengan[2])){
                        $gandenganarr[] = [
                            'gandeng_domain'        => (string)$gandengan[0],
                            'gandeng_code'          => (string)$gandengan[3],
                            'gandeng_desc'          => (string)$gandengan[2],
                            'gandeng_is_active'     => 1,
                            'created_at'            => Carbon::now()->toDateTimeString(),
                            'updated_at'            => Carbon::now()->toDateTimeString()
                        ];
                    }
                }
                
                GandenganMstr::insert($gandenganarr);
                DB::commit();
                $gandenganarr = [];
                fclose($open);
            }
            catch(Exception $err){
                DB::rollBack();
                dd('a');
                alert()->error('Error', 'Failed to activate/deactivate gandengan');
                return redirect()->to(route('gandengan.index'));

            }

            
        }
    }
}
