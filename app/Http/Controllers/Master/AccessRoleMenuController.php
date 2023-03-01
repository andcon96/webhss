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
        $cbConfirmSJ = $request->input('cbConfirmSJ');

        // Menu Driver
        $cbDRInOut = $request->input('cbDRInOut');

        // Menu Report
        $cbRPMT = $request->input('cbRPMT');
        
        // Menu Invoice
        $cbInv = $request->input('cbInvoice');

        // Menu Cicilan
        $cbCicilan = $request->input('cbCicilan');
        $cbBayarCicilan = $request->input('cbBayarCicilan');

        // Menu Setting
        $cbMT01 = $request->input('cbMT01');
        $cbMT02 = $request->input('cbMT02');
        $cbMT03 = $request->input('cbMT03');
        $cbMT06 = $request->input('cbMT06');
        $cbMT08 = $request->input('cbMT08');
        $cbMT09 = $request->input('cbMT09');
        $cbMT10 = $request->input('cbMT10');
        $cbMT11 = $request->input('cbMT11');
        $cbMT12 = $request->input('cbMT12');
        $cbMT14 = $request->input('cbMT14');
        $cbMT15 = $request->input('cbMT15');
        $cbMT16 = $request->input('cbMT16');
        $cbMT21 = $request->input('cbMT21');
        $cbMT22 = $request->input('cbMT22');
        $cbMT19 = $request->input('cbMT19');
        $cbMT20 = $request->input('cbMT20');

        $cbMQ01 = $request->input('cbMQ01');
        $cbMQ02 = $request->input('cbMQ02');
        $cbMQ03 = $request->input('cbMQ03');
        $cbMQ04 = $request->input('cbMQ04');

        $data = $cbSOMT . $cbCOMT . $cbSJMT . $cbTripBrowse . $cbTripLapor . 
                $cbSJLapor . $cbKerusakan . $cbDRInOut. $cbBiaya.
                $cbCicilan . $cbBayarCicilan . $cbConfirmSJ.
                $cbRPMT . $cbMT01 . $cbMT02 . $cbMT03 . $cbMQ01.
                $cbMQ02 . $cbMT06 . $cbMQ03 . $cbMT08 . $cbMT09.
                $cbMT10 . $cbMT11 . $cbMT12 . $cbMQ04 . $cbMT14.
                $cbRPMT . $cbMT01 . $cbMT02 . $cbMT03 . $cbMQ01.
                $cbMQ02 . $cbMT06 . $cbMQ03 . $cbMT08 . $cbMT09.
                $cbMT10 . $cbMT11 . $cbMT12 . $cbMQ04 . $cbMT14.
                $cbMT15 . $cbMT16 . $cbInv. $cbMT22 . $cbMT21.
                $cbMT19 . $cbMT20; 

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
