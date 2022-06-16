<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Domain;
use App\Models\Master\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class SiteMTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = Site::with('hasDomain')->get();
        $domains = Domain::get();
        return view('setting.sites.index', compact('sites', 'domains'));
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
        $act = $request->input('act');
        $request->validate([
            'site' => 'required',
            'desc' => 'required',
        ]);

        if(is_null($act)) {
            $act = false;
        }

        DB::beginTransaction();

        try {
            $sites = new Site();
            $sites->domain_id = $request->dom;
            $sites->site_code = $request->site;
            $sites->site_desc = ucwords($request->desc);
            $sites->site_isActive = $request->act == "true" ? 1 : 0;
            $sites->save();

            DB::commit();

            alert()->success('Success', 'Site Successfully Created');
        } catch (\Exception $err) {
            DB::rollBack();
            alert()->error('Error', 'Failed to save Site');
        }

        return redirect()->back();
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
    public function update(Request $request)
    {
        $id = $request->input('site_id');
        $act = $request->input('e_act');
        $site_desc = $request->input('e_desc');

        if(is_null($act)) {
            $act = false;
        }

        DB::beginTransaction();

        try {
            $sites = Site::find($id);
            $sites->site_desc = ucwords($site_desc);
            $sites->site_isActive = $act == 'true' ? 1 : 0;
            $sites->save();

            DB::commit();
            alert()->success('Success', 'Site Successfully updated');
        } catch (\Exception $err) {
            DB::rollBack();

            alert()->error('Error', 'Failed to update Site');
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('d_site_id');

        DB::beginTransaction();

        try {
            $sites = Site::find($id);
            $sites->delete();

            DB::commit();

            alert()->success('Success', 'Site Successfully Deleted');
        } catch (\Exception $err) {
            DB::rollBack();
            alert()->error('Error', 'Failed to delete Site');
        }
        
        return redirect()->back();
    }
}
