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
            // $teamwithmanager = User::groupBy('team_id')->where('role_id', 2)->whereNotNull('team_id')->select('team_id')->get();
            // dd($teamwithmanager ->toArray());
            $teams = Team::select('id', 'team_name')->get();
            return view('admin.employee.add_employee', ['teams' => $teams]);
        }
        return redirect()->route('employee-list')->with('error', 'Access Denied!');
    }
    public function addEmployee(Request $request)
    {
        if ($request->role_id == 2) {

            $managerExists = User::where('team_id', $request->team_id)->where('role_id', 2)->exists();
            if ($managerExists) {
                return back()->withInput()->with('error', 'This team already has manager');
            }
        }
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
        return redirect()->route('admin.dashboard')->with('success', 'Employee Added Successfully!');
    }
    public function passwordSet(Request $request, $token)
    {
        $email = $request->query('email');
        $data = DB::table('password_reset_tokens')->where('email', $email)->where('token', $token)->firstOrFail();
        return view('auth.password_set', ['data' => $data]);
    }
    public function passwordUpdate(Request $request)
    {
        $data = DB::table('password_reset_tokens')->where('email', $request->email)->where('token', $request->token)->first();
        if ($data) {
            if ($request->password == $request->confirmpassword) {
                $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password), 'address' => $request->address]);
                return redirect()->route('login')->with('success', 'Password Set Successfully!');
            }
            return redirect()->route('login')->with('error', 'Password Update Failed');
        }
    }
    public function employee(Request $request)
    {
        $users = User::with('creator')->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.role_id', '!=', 1)->select('users.*', 'roles.role_name as role_name');

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
                })->addColumn(
                    'Action',
                    function ($row) {
                        return "<a href = '" . route('admin.view.employee', $row->id) . "'><i class='bi bi-eye'></i></a>
                    <a href = '" . route('admin.edit.employee', $row->id) . "'><i class='bi bi-pencil'></i></a>
                    <a href='" . route('admin.delete.employee', $row->id) . "' onclick=\"return confirm('Do you want to delete?')\">
                        <i class='bi bi-trash'></i></a>";
                    }
                )
                ->rawColumns(['gender', 'Action'])
                ->toJson();
        }
        // $users = User::with('creator')->join('roles', 'users.role_id', '=', 'roles.id')->where('users.role_id', '!=', 1)->get();
        return view('admin.employee.employee', ['users_count' => $users->count()]);
    }
    public function viewEmployee($id)
    {
        $user = User::with('creator')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('teams', 'users.team_id', '=', 'teams.id')
            ->where('users.id', $id)
            ->select(
                'users.*',
                'roles.role_name as role_name',
                'teams.team_name as team_name'
            )
            ->first();
        // dd($user->toArray());
        return view('admin.employee.employee_view', ['user' => $user]);
    }
    public function editEmployee($id)
    {
        $user = User::find($id);
        $teams = Team::select('id', 'team_name')->get();
        // dd($teams->toArray());
        // dd($user->toArray());
        return view('admin.employee.employee_edit', compact('user', 'teams'));
    }
    public function updateEmployee($id, Request $request)
    {
        // dd($request->toArray());
        // $user = User::find($id);
        $validate = $request->validate(
            [
                'full_name' => 'required|min:6',
                'email' => 'required|email|unique:users,email,' . $id,
                'phone' => 'required|unique:users,phone,' . $id,
                'gender' => 'required',
                'date_of_birth' => 'required|date',
                'role_id' => 'required',
                'team_id' => 'required',
                'job_title' => 'required'
            ]
        );
        // dd($validate);
        $users = User::where('id', $id)->update($validate);
        return redirect()->route('employee-list')->with('success', 'Employee Updated Successfully');
    }
    public function deleteEmployee($id)
    {
        $user = User::find($id);
        // dd($user->toArray());
        $user->delete();
        return redirect()->route('employee-list')->with('success', 'Deleted Successfully!');

    }
}
