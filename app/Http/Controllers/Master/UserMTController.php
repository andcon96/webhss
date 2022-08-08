<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Department;
use App\Models\Master\Domain;
use App\Models\Master\Role;
use App\Models\Master\RoleType;
use App\Models\Master\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserMTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::with([ 'getRoleType', 'getRole'])
            ->orderBy('role_id')
            ->paginate(10);
        
        $roleType = RoleType::get();

        $dept = Department::get();

        // $domain = Domain::get();

        if ($request->ajax()) {
            $username = $request->username;
            $name = $request->name;

            $users = new User();

            if (isset($username)) {
                $users = $users->where('username', 'LIKE', '%' . $username . '%');
            }

            if (isset($name)) {
                $users = $users->where('name', 'LIKE', '%' . $name . '%');
            }

            $users = $users
                ->with(['getRoleType', 'getRole'])
                ->orderBy('role_id')
                ->paginate(10);

            return view('setting.users.table', compact('users'));
        } else {
            return view('setting.users.index', compact('users', 'roleType', 'dept'));
        }
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
        
        $this->validate($request, [
            'username' => 'required|unique:users',
            'name' => 'required',
            'password' => 'required|max:20',
            'password_confirmation' => 'required|max:20|same:password',
        ], [
            'unique' => 'Username Must Be Unique',
        ]);
        
        DB::beginTransaction();
        try {
            $dept = $request->dept;
            $password = $request->password;

            $role = $request->role;
            $role_id = Role::where('role', $role)->value('id');

            $storeUser = new User();

            $storeUser->username = $request->input('username');
            $storeUser->name = $request->input('name');
            $storeUser->email = $request->input('email');
            $storeUser->password = Hash::make($password);
            $storeUser->role_id = $role_id;
            $storeUser->role_type_id = $request->input('roletype');
            $storeUser->dept_id = 1;
            // $storeUser->domain = $request->input('domain');

            $storeUser->isActive = 1;
            $storeUser->save();
            

            DB::commit();
            alert()->success('Success', 'User successfully created!');
            return redirect()->route('usermaint.index');
        } catch (\InvalidArgumentException $ex) {
            DB::rollback();
            alert()->error('Error', $ex->getMessage());
            return back()->withError($ex->getMessage())->withInput();
            
        } catch (\Exception $ex) {
            DB::rollback();
            alert()->error('Error', $ex->getMessage());
            return back()->withError($ex->getMessage())->withInput();
            
        } catch (\Error $ex) {
            DB::rollback();
            alert()->error('Error', $ex->getMessage());
            return back()->withError($ex->getMessage())->withInput();
            
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
        $user_id = $request->t_id;
        $username = $request->d_uname;
        $name = $request->name;
        $roletype = $request->roletype;
        // $domain = $request->domain;

        DB::beginTransaction();

        try {
            //code...
            $user = User::where('id', $user_id)->first();
            $user->name = $name;
            $user->username = $username;
            $user->role_type_id = $roletype;
            // $user->domain = $domain;
            if ($user->isDirty()) {
                $user->save();
            }

            DB::commit();
            alert()->success('Success', 'User updated successfully');
        } catch (\Exception $err) {
            //throw $th;
            // dd($err);
            DB::rollBack();
            alert()->error('Error', 'Failed to update user');
        }

        return redirect()->route('usermaint.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = User::where('id', $request->temp_id)->first();

        if ($user->isActive == 1) {
            $user->isActive = 0;
            alert()->success('Success', 'User successfully De-Activated');
        } else {
            $user->isActive = 1;
            alert()->success('Success', 'User successfully Activated');
        }
        $user->save();

        return redirect()->back();
    }

    /**
     * Search role_type pada create user
     */
    public function searchoptionuser(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search;
            $role = Role::where('role', $search)->first();
            $data = RoleType::where('role_id', $role->id)->get();
            $array = json_decode(json_encode($data), true);
            // dd($array);
            return response()->json($array);
        }
    }

    /**
     * Change password by admin
     */
    public function adminchangepass(Request $request)
    {
        $this->validate($request, [
            'c_password' => 'required|min:8',
            'password_confirmation' => 'required|min:8|same:c_password',
        ]);


        $id = $request->c_id;
        $password = $request->c_password;
        $confpass = $request->password_confirmation;

        User::where('id', $id)->update(['password' => Hash::make($password)]);

        DB::table('users')
            ->where('id', $id)
            ->update(['password' => Hash::make($password)]);

        alert()->success('Success', 'Password Successfully Updated');
        return back();
    }
}