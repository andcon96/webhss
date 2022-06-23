<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\approval;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;

class ApprovalController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $approval = approval::query()
                            ->orderBy('approval_name');

        $name = $request->s_name;
        if (isset($name)) {
            $approval->where('approval_name', 'LIKE', '%' . $name . '%');
        }

        $approval = $approval->paginate(10);

            // dd($request->all());
         
        return view('setting.approval.index', compact('approval'));
        
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
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
        
        ]);
        DB::beginTransaction();
        try{
            $name = $request->name;
            $email = $request->email;

            $insertapproval = approval::firstOrCreate([
                
                'approval_email' => $request->email
            ],['approval_name' => $request->name,]);
            if(!$insertapproval->wasRecentlyCreated){
                alert()->error('Error', 'Approval email used already');
                return back();
            }
            DB::commit();
            alert()->success('Success', 'Approval created');
            return back();
        }
        catch(Exception $err){
            DB::rollBack();
            
            alert()->error('Error', 'Create approval failed');
            return back();
            
        } 
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $name = $request->e_name;
        $email = $request->e_email;
        $id = $request->e_id;
        DB::beginTransaction();
        try{
            approval::where('id',$id)->update([
                'approval_name' => $name,
                'approval_email' => $email
            ]);
            DB::commit();
            alert()->success('Success', 'Edit approval success');
            return back();
        }
        catch(Exception $err){
            DB::rollBack();
            
            alert()->error('Error', 'Edit approval failed');
            return back();
            
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // dd($request->all());
        $id = $request->d_id;
        DB::beginTransaction();
        try{
            $approval = new approval();
            $approval->where('id',$id)->delete();
            DB::commit();
            alert()->success('Success', 'Approval deleted');
            return back();
        }
        catch(Exception $err){
            DB::rollBack();
            
            alert()->error('Error', 'Delete approval failed');
            return back();
            
        } 

    }

  
}