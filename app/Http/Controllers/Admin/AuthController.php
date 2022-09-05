<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public  function  login(){
        return view('admin.auth.signin');
    }
    public  function LoginDashboard(Request $request){
        $user = User::where('email', $request->email)->first();
        if($user->status == INACTIVE) {
            return  redirect()->route('login')->with('toast_error' , __('User is blocked by admin.'));
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password,'is_admin'=>1])) {
            if(Auth::user()->is_admin == 1){
                return redirect()->route('admin.dashboard');
            }else{
                Auth::logout();
                return redirect()->back()->with('toast_error', __('Something went wrong!'));
            }
        }
        return  redirect()->route('login')->with('toast_error' , __('Wrong Credential'));
    }
    public function logout(){
        if (Auth::check()) {
            Auth::logout();
            return redirect()->route('login');
        }
        return redirect()->back()->with('error', __('Something went wrong!'));
    }

}
