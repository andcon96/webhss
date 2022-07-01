<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\RoleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccessRoleMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roleAccess = RoleType::with('getRole')->get();

        return view('setting.accessrolemenu.index', compact('roleAccess'));
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
        //
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
        // Menu SO
        $cbSOMT = $request->input('cbSOMT');
        $cbCOMT = $request->input('cbCOMT');
        $cbSJMT = $request->input('cbSJMT');

        // Menu Trip
        $cbTripBrowse = $request->input('cbTripBrowse');
        $cbTripLapor = $request->input('cbTripLapor');
        $cbSJLapor = $request->input('cbSJLapor');
        $cbKerusakan = $request->input('cbKerusakan');
        $cbBiaya = $request->input('cbBiaya');

        // Menu Driver
        $cbDRInOut = $request->input('cbDRInOut');

        // Fitur Tambahan
        $cbPdDomain = $request->input('cbPdDomain');

        $data = $cbSOMT . $cbCOMT . $cbSJMT . $cbTripBrowse . $cbTripLapor . 
                $cbSJLapor . $cbKerusakan . $cbDRInOut. $cbBiaya. $cbPdDomain;

        DB::beginTransaction();

        try {
            $roleAccess = RoleType::where('id', $request->edit_id)->first();
            $roleAccess->accessmenu = $data;
            $roleAccess->save();
            
            DB::commit();
            alert()->success('Success', 'Role Access successfully updated');
            return redirect()->back();
        } catch (\Exception $err) {
            DB::rollBack();
            alert()->error('Error', 'Failed to save role access');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function accessmenu(Request $request)
    {
        if ($request->ajax()) {

            $output = "";

            $accessmenu = RoleType::where('id', $request->search)->get();

            if ($accessmenu) {
                foreach ($accessmenu as $menu) {
                    $output .= $menu->accessmenu;
                }
            }

            return Response($output);
        }
    }
}
