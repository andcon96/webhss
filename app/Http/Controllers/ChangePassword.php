<?php

namespace App\Http\Controllers;

use App\Models\Master\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ChangePassword extends Controller
{
    public function update(Request $request){
        
        $this->validate($request, [
            'c_oldpass' => 'required',
            'c_newpass' => 'required',
            'c_confirmpass' => 'required|same:c_newpass',
        ],
        [
            'c_oldpass.required' => 'old password cannot empty',
            'c_newpass.required' => 'New password cannot empty',
            'c_confirmpass.required' => 'Confirm password cannot empty',
            'same' => 'Confirm password and new password not match',
        ]);

        $user = User::where('username',Session::get('username'))->first();
        if($user){
            $checkpass = Hash::check($request->c_oldpass,$user->password);
            if($checkpass == true){
                $passhash = Hash::make($request->c_newpass);
                DB::beginTransaction();
                try{
                    $user->password = $passhash;
                    $user->save();
                    DB::commit();
                    alert()->success('Success', 'Update password success');
                    return back();
                }
                catch(Exception $err){
                    
                    DB::rollBack();
                    alert()->error('Error', 'Failed to update password');
                    return back();
                }
            }
            else{
                alert()->error('Error', 'Old password not match with user');
                return back();
            }
        }
        else{
            alert()->error('Error', 'Please try again');
            return back();
        }    
    }
    //
}
