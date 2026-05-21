<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Hash;
class AuthController extends Controller
{
    public function login(){
        if (Auth::check()){
            return view('admin.dashboard');
        }
        return view('auth.login');
    }
    public function register(){
        if (Auth::check()){
            return view('admin.dashboard');
        }
        return view('auth.register');
    }
    public function registersubmit(Request $request){
        $validated = $request->validate([
            'name' => 'required|min:5',
            'email' => 'required|unique:users|email',
            'phone_no' => 'required|unique:users',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'password'=>'required'
            ]);
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone_no'=> $request->phone_no,
            'gender'=>$request->gender,
            'date_of_birth'=>$request->date_of_birth,
            'role_id'=>1,
            'job_title'=>'Admin',
            'address'=>$request->address,
            'password'=>$request->password
        ]);
        // dd($user);
        return redirect()->route('login');
    }
    public function loginsubmit(Request $request){
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            // dd($request->toArray());
            $user = User::where('email',$request->email)->first();
            // dd(auth()->user()->name);
            return redirect()->route('admin.dashboard');
        }
        return 'Incorrect credentials';
    }
    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
