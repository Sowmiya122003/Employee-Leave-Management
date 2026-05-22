<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Mail\PasswordMail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Mail;
use Str;
use DB;
use Hash;
class UserController extends Controller
{
    public function employeeForm(){
        $teams = Team::select('id','team_name')->get();
        return view('admin.employee.add_employee',['teams'=>$teams]);
    }
    public function addEmployee(Request $request){
        if (auth()->user()->role_id == 1){
            $validate = $request->validate([
                'full_name' => 'required|min:5',
                'email'=> 'required|unique:users|email',
                'phone'=>'required|unique:users',
                'role_id' =>'required',
                'team_id'=>'required',
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
            return redirect()->route('admin.dashboard');
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
                $user = User::where('email',$request->email)->update(['password'=>Hash::make($request->password),'address'=>$request->address]);
                return redirect()->route('login');
            }
            return 'Update Failed';
        }
    }
    public function employee(){
        $users = User::with('creator')->join('roles','users.role_id','=','roles.id')->where('users.role_id','!=',1)->get();
        return view('admin.employee.employee',['users'=>$users]);
    }
}
