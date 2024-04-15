<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subcategory;
use Carbon\Carbon;
use DB; 
use Illuminate\Support\Str;
use Hash;
use Mail;


class adminController extends Controller
{
    function index(){
   
       return view('admin.dashboard');
    }
    function admin_profile(){
      $data['user'] = auth()->user();
      return view('user.admin_profile',$data);
   }
   function user_index(){
   
      return view('user.dashboard');
   }
   function user_profile(){
     $data['user'] = auth()->user();
     return view('user.admin_profile',$data);
  }

   function user_update_profile(Request $request){
      $id = auth()->user()->id;
      if($request->password){
         $password = bcrypt($request->password);
      }
      else{
         $password = auth()->user()->password;
      }
      User::where('id', $id)
            ->update([
                'name'=> $request->name,
                'email'=>$request->email,
                'password'=>$password,
                ]);
      return redirect()->back()->with('alert', 'Profile is updated successfully');
      }
      function admin_update_profile(Request $request){
         $id = auth()->user()->id;
         if($request->password){
            $password = bcrypt($request->password);
         }
         else{
            $password = auth()->user()->password;
         }
         User::where('id', $id)
               ->update([
                   'name'=> $request->name,
                   'email'=>$request->email,
                   'password'=>$password,
                   ]);
         return redirect()->back()->with('alert', 'Profile is updated successfully');
         }

   public function admin_delete_users($id)
   {
           $productCategory = User::where('id', $id)->first();
           $productCategory->delete();
           return redirect()->back()->with('alert', 'User is deleted successfully');

   }
   public function loadSubCategories($id)
   {
      $subcategories = Subcategory::where('category_id',$id)->get();
     
      return response()->json($subcategories);

   }

   
  

 public function showForgetPasswordForm()
      {
         return view('admin.forgetPassword');
      }

 public function submitForgetPasswordForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users',
          ]);
          $token = Str::random(64);
          DB::table('password_resets')->insert([
            'email' => $request->email, 
            'token' => $token, 

          ]);
          Mail::send('emails.forgetPassword', ['token' => $token], function($message) use($request){
              $message->to($request->email);
              $message->subject('Reset Password from buygim');
          });
          return back()->with('alert', 'We have e-mailed your password reset link!');

      }
      public function showResetPasswordForm($token) { 

         return view('admin.forgetPasswordLink', ['token' => $token]);

      }
      public function submitResetPasswordForm(Request $request)
      {
         $user = User::where('email', $request->email)->first();
         if(!$user){
            return redirect()->back()->with('alert', 'Your email address is not correct');
         }
         if($request->password != $request->password_confirmation){
            return redirect()->back()->with('alert', 'Password and confirm password are not same');
         }
        $updatePassword = DB::table('password_resets')
                              ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                              ])
                              ->first();
          if(!$updatePassword){
              return back()->withInput()->with('alert', 'Invalid token!');
          }
          $user = User::where('email', $request->email)
         ->update(['password' => Hash::make($request->password)]);
         DB::table('password_resets')->where(['email'=> $request->email])->delete();
          return redirect()->back()->with('alert', 'Your password has been changed!');

      }
   
}
