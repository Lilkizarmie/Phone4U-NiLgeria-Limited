<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAuthRequest;
use App\Http\Requests\UserChangePasswordRequest;
use App\Models\SeoSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
   public function userSignIn(){
       $seo = SeoSetting::where('slug', 'sign-in')->first();
       $data['title'] = $seo->title;
       $data['description'] = $seo->description;
       $data['keywords'] = $seo->keywords;
        return view('front.auth.sign_in', $data);
   }
   public function userSignInPost(Request $request){
       $request->validate([
           'email'=>'required',
           'password'=>'required',
       ]);
       $user = User::where('email', $request->email)->first();
       if($user->status == INACTIVE) {
           return  redirect()->route('front')->with('toast_error' , __('User is blocked by admin.'));
       }
       if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
           if(Auth::user()->is_admin == 0) {
               return redirect()->route('front');
           }else{
               Auth::logout();
               return redirect()->back()->with('toast_error', __('Something went wrong!'));
           }
       }
       return redirect()->back()->with('toast_error' , __('Wrong Credential'));
   }
   public function userSignUp(){
       $seo = SeoSetting::where('slug', 'sign-up')->first();
       $data['title'] = $seo->title;
       $data['description'] = $seo->description;
       $data['keywords'] = $seo->keywords;
       return view('front.auth.sign_up', $data);
   }
   public function userSignUpPost(UserAuthRequest $request){
       $user=User::create([
           'name'=>$request->name,
           'email'=>$request->email,
           'password'=>Hash::make($request->confirm_password),
        ]);
        if($user){
            return redirect()->route('front')->with('toast_success', __('Sign Up Successfully !'));
        }else{
            return redirect()->route('user.sign.up')->with('toast_success', __('Wrong Credential !'));
        }
   }
   public function userLogout(){
       if (Auth::check()) {
           Auth::logout();
           return redirect()->route('front');
       }
       return redirect()->back()->with('error', __('Something went wrong!'));
   }
   public function userChangePassword(UserChangePasswordRequest $request){
        $user=Auth::user();
       $userPassword=$user->password;
       $request->validate([
           'current_password'=>'required',
           'new_password'=>'required|same:confirm_password|min:6',
           'confirm_password'=>'required',
       ]);
       if(!Hash::check($request->current_password,$userPassword)){
           return 'wrong';
       }
       $user->password=Hash::make($request->new_password);
       $user->save();
       return redirect()->back()->with('toast_success', __('Password change successfully!'));
   }
   //forget password
    public function userForgetPasswordGet(){
        $seo = SeoSetting::where('slug', 'forget-password')->first();
        $data['title'] = $seo->title;
        $data['description'] = $seo->description;
        $data['keywords'] = $seo->keywords;
        return view('front.auth.forget_password', $data);
    }
    public function userForgetPasswordPost(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('front.auth.mail_form', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return back()->with('toast_success', 'We have e-mailed your password reset link!');
    }
    public function userShowResetPasswordForm($token){
        $seo = SeoSetting::where('slug', 'reset-password')->first();
        $data['title'] = $seo->title;
        $data['description'] = $seo->description;
        $data['keywords'] = $seo->keywords;
        $data['token'] = $token;
        return view('front.auth.show_reset_form', $data);
    }
    public function submitResetPasswordForm(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('toast_error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();
        if ($user){
            return redirect()->route('user.sign.in')->with('toast_success', 'Your password has been changed!');
        }
        return redirect()->back()->with('toast_error', 'Your password not changed!');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            $finduser = User::where('google_id', $user->id)->orWhere('email', $user->email)->first();

            if($finduser){
                if($finduser->status == INACTIVE) {
                    return  redirect()->route('front')->with('toast_error' , __('User is blocked by admin.'));
                }
                Auth::login($finduser);
                return redirect()->route('front')->with('toast_success', __('Welcome to Phone4U!'));
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => encrypt('123456')
                ]);
                Auth::login($newUser);
                return redirect()->route('front')->with('toast_success', __('Welcome to Phone4U!'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('toast_error', __('Something went wrong!'));
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();

            $finduser = User::where('facebook_id', $user->id)->first();

            if($finduser){
                if($finduser->status == INACTIVE) {
                    return  redirect()->route('front')->with('toast_error' , __('User is blocked by admin.'));
                }
                Auth::login($finduser);
                return redirect()->route('front')->with('toast_success', __('Welcome to Phone4U!'));
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'facebook_id'=> $user->id,
                    'password' => encrypt('123456')
                ]);
                Auth::login($newUser);
                return redirect()->route('front')->with('toast_success', __('Welcome to Phone4U!'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('toast_error', __('Something went wrong!'));
        }
    }
}
