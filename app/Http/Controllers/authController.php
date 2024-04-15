<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Mail;
use Stevebauman\Location\Facades\Location;
class authController extends Controller
{
    function admin_login()
    {
     
        if (auth()->check() && auth()->user()->role_id == 1) {
            return redirect()->route('admin_dashboard');
        } else {
            return view('admin.login');
        }
    }

    function user_login()
    {
     
        if (auth()->check() && auth()->user()->role_id == 2) {
            return redirect()->route('admin_dashboard');
        } else {
            return view('user.login');
        }
    }

    function postAdminLogin(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
      
            // Authentication was successful...
     
       
        if (Auth::attempt(['email' => $email, 'password' => $password], true) && auth()->user()->role_id == 1) {
                return Redirect::to('/admin_dashboard');
            
        } else {
            return redirect()->back()->with('alert', 'Incorrect Login Details');

        }

    }

    function postuserLogin(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
      
            // Authentication was successful...
    
       
        if (Auth::attempt(['email' => $email, 'password' => $password], true) && auth()->user()->role_id == 2) {
          
                return Redirect::to('/user_dashboard');
            
        } else {
            return redirect()->back()->with('alert', 'Incorrect Login Details');

        }

    }
    public function admin_logout()
    {
      
        Auth::logout();
        return redirect()->to('/admin_login');
    }

    public function user_logout()
    {
      
        Auth::logout();
        return redirect()->to('/');
    }

    function admin_user(){
        return view('admin.register_user');
    }
   
   
    function register_user(){
        return view('user.signup');
    }

    function register_user_post(Request $request){
      
       $user = User:: where('email',$request->email)->first();
       if($user){
        return redirect()->back()->with('success', 'Account with email already exist');
       }
      
       else{
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->dob =   Carbon::parse($request->dob);
        $user->role_id = $request->role_id;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);
        $user->save();
      
        return redirect()->back()->with('success', 'You have registered successfully!');
       }
    }
   

   
}