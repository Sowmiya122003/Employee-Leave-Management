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
use Yajra\DataTables\DataTables;
class UserController extends Controller
{
    public function employeeForm()
    {
        if (auth()->user()->role_id == 1) {
            $teams = Team::select('id', 'team_name')->get();
            return view('admin.employee.add_employee', ['teams' => $teams]);
        }
        return redirect()->route('employee-list')->with('error', 'Access Denied!');
    }
    public function addEmployee(Request $request)
    {
        $validate = $request->validate([
            'full_name' => 'required|min:5',
            'email' => 'required|unique:users|email',
            'phone' => 'required|unique:users',
            'role_id' => 'required',
            'team_id' => 'required',
            'job_title' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required|date'
        ]);
        $validate['created_by'] = auth()->user()->id;
        $user = User::create($validate);
        Mail::to($request->email)->queue(new WelcomeMail($request->name));
        $token = Str::random(20);
        $table1 = DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        $table = DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token
        ]);
        $password_set_link = route('password.set', ['token' => $token, 'email' => $request->email]);
        Mail::to($request->email)->queue(new PasswordMail($request->name, $password_set_link));
        return redirect()->route('admin.dashboard');
    }
    public function passwordset(Request $request, $token)
    {
        $email = $request->query('email');
        $data = DB::table('password_reset_tokens')->where('email', $email)->where('token', $token)->firstOrFail();
        return view('auth.password_set', ['data' => $data]);
    }
    public function passwordupdate(Request $request)
    {
        $data = DB::table('password_reset_tokens')->where('email', $request->email)->where('token', $request->token)->first();
        if ($data) {
            if ($request->password == $request->confirmpassword) {
                $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password), 'address' => $request->address]);
                return redirect()->route('login');
            }
            return redirect()->route('login')->with('error', 'Password Update Failed');
        }
    }
    public function employee(Request $request)
    {
        $users = User::with('creator')->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.role_id', '!=', 1);

        if ($request->ajax()) {
            return DataTables::of($users)
                ->editColumn('gender', function ($row) {
                    if ($row->gender == 'M') {
                        return "Male";
                    }
                    if ($row->gender == 'F') {
                        return "Female";
                    }
                    return "<i>Others</i>";
                })
                ->addColumn("creator_name", function ($row) {
                    return $row->creator->full_name;
                })
                ->filterColumn('creator_name', function ($query, $keyword) {
                    $query->whereHas('creator', function ($q) use ($keyword) {
                        $q->where('full_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('gender', function ($query, $keyword) {

                    $keyword = strtolower(trim($keyword));

                    if ($keyword == 'male') {
                        $query->where('users.gender', 'M');

                    } elseif ($keyword == 'female') {
                        $query->where('users.gender', 'F');

                    } elseif ($keyword == 'others') {
                        $query->whereNotIn('users.gender', ['M', 'F']);
                    }
                })
                ->rawColumns(['gender'])
                ->toJson();
        }
        // $users = User::with('creator')->join('roles', 'users.role_id', '=', 'roles.id')->where('users.role_id', '!=', 1)->get();
        return view('admin.employee.employee', ['users_count' => $users->count()]);
    }
}
