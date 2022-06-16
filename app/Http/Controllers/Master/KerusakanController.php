<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Kerusakan;
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
        $data = $data->paginate(10);

        return view('setting.kerusakan.index',compact('data','kerusakan'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $newdata = new Kerusakan();
            $newdata->kerusakan_code = $request->code;
            $newdata->kerusakan_desc = $request->desc;
            $newdata->save();

            DB::commit();
            alert()->success('Success', 'Department successfully created');
        }catch(Exception $e){
            DB::rollback();
            alert()->error('Error', 'Failed to create Kerusakan');
        }

        return back();
    }

    public function update(Request $request, Kerusakan $kerusakan)
    {
        try{
            $newdata = Kerusakan::findOrFail($request->edit_id);
            $newdata->kerusakan_desc = $request->e_desc;
            $newdata->save();

            DB::commit();
            alert()->success('Success', 'Department successfully Updated');
        }catch(Exception $e){
            DB::rollback();
            alert()->error('Error', 'Failed to update Kerusakan');
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
            alert()->success('Success', 'Department successfully Activated / Deactivated');
        }catch(Exception $e){
            DB::rollback();
            alert()->error('Error', 'Failed to Activated / Deactivated Kerusakan');
        }

        return back();
    }
}
