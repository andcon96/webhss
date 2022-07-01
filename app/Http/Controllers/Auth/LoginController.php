<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Master\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    protected function authenticated(Request $request)
    {
        // Ambil Username Session
        $username = $request->input('username');
        $id = Auth::id();

        // Set Session Username & ID
        Session::put('userid', $id);
        $request->session()->put('username', $username);
        
        //new
        $users = User::with('getRole')->where('id', $id)->first();

        // set Session Flag buat Menu Access
        if ($users == '') {
            Auth::logout();
            return redirect()->back()->with(['error' => 'Pastikan Role User sudah dibuat, Silahkan kontak Admin']);
        } else {
            Session::put('getRole', $users->getRole->role);
            Session::put('domain',$users->domain);
            Session::put('department', $users->department);
            Session::put('name', $users->name);

            Session::put('username', $users->username);

            if (session()->get('url.now') != null) {
                // buat redirect ke prev url klo ada.
                return redirect(session()->get('url.now'));
            }
        }
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $previous_session = Auth::User()->session_id;
        if ($previous_session) {
            Session::getHandler()->destroy($previous_session);
        }

        Auth::user()->session_id = Session::getId();

        Auth::user()->save();
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    protected function sendFailedLoginResponse(Request $request)
    {

        $data = User::where('username', $request->username)->get();

        if (count($data) == 0) {
            return redirect()->back()->with(['error' => 'Username salah / tidak terdaftar']);
        }

        $hasher = app('hash');

        $users = User::select('id', 'password')
                    ->where('username', $request->username)
                    ->first();

        if (!$hasher->check($request->password, $users->password)) {
            return redirect()->back()->with(['error' => 'Password salah']);
        }
    }
}
