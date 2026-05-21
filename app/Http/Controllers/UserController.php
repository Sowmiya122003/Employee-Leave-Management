<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Mail\PasswordMail;
use Illuminate\Http\Request;
use App\Models\User;
use Mail;
use Str;
use DB;
use Hash;
class UserController extends Controller
{
    public function addUser(Request $request){
        // dd($request->all());
        // dd(auth()->user()->name);
        if (auth()->user()->role_id == 1){
            $validate = $request->validate([
                'name' => 'required|min:5',
                'email'=> 'required|unique:users|email',
                'phone_no'=>'required|unique:users',
                'role_id' =>'required',
                'job_title'=>'required',
                'gender'=>'required',
                'date_of_birth'=>'required|date'
            ]);
            $validate['created_by'] = auth()->user()->id;
            $user = User::create($validate);
            Mail::to($request->email)->queue(new WelcomeMail($request->name));
            $token=Str::random(20);
            $table1=DB::table('password_reset_tokens')->where('email',$request->email)->delete();
            $table=DB::table('password_reset_tokens')->insert([
                'email'=> $request->email,
                'token'=>$token
            ]);
            $password_set_link = route('password.set',['token'=>$token,'email'=>$request->email]);
            Mail::to($request->email)->queue(new PasswordMail($request->name,$password_set_link));
            return redirect()->back();
        }
        return ' Access Denied';
    }
    public function passwordset(Request $request , $token){
        $email=$request->query('email');
        $data = DB::table('password_reset_tokens')->where('email',$email)->where('token',$token)->first();
        return view('auth.password_set',['data'=>$data]);
    }
    public function passwordupdate(Request $request){
        $data = DB::table('password_reset_tokens')->where('email',$request->email)->where('token',$request->token)->first();
        if($data){
            if ($request->password == $request->confirmpassword){
                $user = User::where('email',$request->email)->update(['password'=>Hash::make($request->password)]);
                // Mail::to($request->email)->queue(new PasswordUpdateMail());
                return redirect()->route('login');
            }
            return 'Update Failed';
        }
    }
    public function employee(){
        $user = User::where('role_id','!=',1)->get();
        // dd($user->toArray());
        return view('admin.employee',['user'=>$user]);
    }
}
