<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Mail\PasswordMail;
use App\Models\LeaveMonthlyBalance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use App\Models\CompanyHoliday;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use Mail;
use Str;
use DB;
use Hash;
use Yajra\DataTables\DataTables;
class UserController extends Controller
{
    public function index()
    {
        $holidays = CompanyHoliday::select('title', 'holiday_date')->get();
        if (auth()->user()->role_id == 2) {
            $my_leave_balance = LeaveMonthlyBalance::join('leave_types', 'leave_types.id', '=', 'leave_monthly_balances.type_of_leave_id')
                ->where('leave_monthly_balances.user_id', auth()->id())
                ->where('leave_monthly_balances.company_year', now()->year)
                ->where('leave_monthly_balances.month',now()->month)
                ->select('leave_types.leave_type_name', 'leave_monthly_balances.allocated_leaves', 'leave_monthly_balances.used_leaves', 'leave_monthly_balances.carry_forward_days', 'leave_monthly_balances.unpaid_leaves')
                ->get();
            $leavechart = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')
                ->join('leave_types', 'leave_types.id', '=', 'leave_requests.type_of_leave_id')
                ->groupBy('leave_requests.type_of_leave_id', 'users.id')
                ->where('users.role_id', 3)
                ->where('leave_requests.status', 'approved')
                ->where('users.team_id', auth()->user()->team_id)
                ->selectRaw('leave_types.leave_type_name as type_name, COUNT(*) as members, SUM(approved_leaves) as total')
                ->get();
            $leaves_count = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')
                ->where('users.team_id', auth()->user()->team_id)
                ->groupBy('leave_requests.status')
                ->selectRaw('leave_requests.status,COUNT(*) as total')
                ->get();
            $leave = LeaveRequest::join('leave_types', 'leave_requests.type_of_leave_id', '=', 'leave_types.id')
                ->join('users', 'leave_requests.user_id', '=', 'users.id')
                ->where('users.team_id', auth()->user()->team_id)
                ->where('leave_requests.status', 'approved')
                ->select('leave_types.leave_type_name as leave_name', 'from_date', 'to_date', 'users.role_id', 'users.full_name as full_name')
                ->get();
            $leaves_count = $leaves_count->pluck('total', 'status');
            $top_leave_employees = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')
                ->join('teams', 'teams.id', '=', 'users.team_id')
                ->join('roles', 'roles.id', '=', 'users.role_id')
                ->where('leave_requests.status', 'approved')
                ->where('users.role_id', 3)
                ->where('users.team_id', auth()->user()->team_id)
                ->groupBy('leave_requests.user_id', 'users.full_name')
                ->selectRaw('users.full_name, roles.role_name,teams.team_name,SUM(leave_requests.approved_leaves) as total_leaves,DATE(users.created_at) as joining_date')
                ->orderByDesc('total_leaves')
                ->limit(5)
                ->get();
            return view('admin.dashboard', compact('holidays', 'leaves_count', 'leave', 'top_leave_employees', 'leavechart', 'my_leave_balance'));
        } elseif (auth()->user()->role_id == 3) {
            $leavechart = LeaveRequest::join('leave_types', 'leave_types.id', '=', 'leave_requests.type_of_leave_id')
                ->groupBy('leave_requests.type_of_leave_id')
                ->where('leave_requests.status', 'approved')
                ->where('leave_requests.user_id', auth()->user()->id)
                ->selectRaw('leave_types.leave_type_name as type_name ,SUM(approved_leaves) as total')
                ->get();
            $employee_leave = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')
                ->where('leave_requests.user_id', auth()->user()->id)
                ->groupBy('status')
                ->selectRaw('leave_requests.status,COUNT(*) as total')
                ->get();
            $leaves_count = $employee_leave->pluck('total', 'status');
            $leave = LeaveRequest::join('leave_types', 'leave_requests.type_of_leave_id', '=', 'leave_types.id')
                ->join('users', 'users.id', '=', 'leave_requests.user_id')
                ->where('leave_requests.user_id', auth()->user()->id)
                ->where('leave_requests.status', 'approved')
                ->select('leave_types.leave_type_name as leave_name', 'from_date', 'to_date', 'leave_requests.status', 'users.full_name as full_name')
                ->get();
            $my_leave_balance = LeaveMonthlyBalance::join('leave_types', 'leave_types.id', '=', 'leave_monthly_balances.type_of_leave_id')
                ->where('leave_monthly_balances.user_id', auth()->id())
                ->where('leave_monthly_balances.company_year', now()->year)
                ->where('leave_monthly_balances.month',now()->month)
                ->select('leave_types.leave_type_name', 'leave_monthly_balances.allocated_leaves', 'leave_monthly_balances.used_leaves', 'leave_monthly_balances.carry_forward_days', 'leave_monthly_balances.unpaid_leaves')
                ->get();
            return view('admin.dashboard', compact('holidays', 'leaves_count', 'leave', 'leavechart', 'my_leave_balance'));
        } else {
            $leavechart = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')->join('leave_types', 'leave_types.id', '=', 'leave_requests.type_of_leave_id')->groupBy('leave_requests.type_of_leave_id')->where('leave_requests.status', 'approved')->selectRaw('leave_types.leave_type_name as type_name, COUNT(DISTINCT leave_requests.user_id) as members, SUM(approved_leaves) as total')->get();
            $leaves_count = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')->where('users.role_id', '!=', 1)->groupBy('leave_requests.status')->selectRaw('leave_requests.status,COUNT(*) as total')->get();
            $leaves_count = $leaves_count->pluck('total', 'status');
            $leave = LeaveRequest::join('users', 'leave_requests.user_id', '=', 'users.id')->join('leave_types', 'leave_requests.type_of_leave_id', '=', 'leave_types.id')->where('users.role_id', '!=', 1)->where('leave_requests.status', 'approved')->select('leave_types.leave_type_name as leave_name', 'from_date', 'to_date', 'users.role_id', 'users.full_name')->get();
            $top_leave_employees = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')->join('teams', 'teams.id', '=', 'users.team_id')->join('roles', 'roles.id', '=', 'users.role_id')->where('leave_requests.status', 'approved')->groupBy('leave_requests.user_id', 'users.full_name')->selectRaw('users.full_name, roles.role_name,teams.team_name,SUM(leave_requests.approved_leaves) as total_leaves,DATE(users.created_at) as joining_date')->orderByDesc('total_leaves')->limit(5)->get();
            return view('admin.dashboard', compact('holidays', 'leave', 'leaves_count', 'top_leave_employees', 'leavechart'));
        }
    }
    public function employeeForm()
    {
        if (auth()->user()->role_id == 1) {
            $teams = Team::select('id', 'team_name')->get();
            return view('admin.employee.add_employee', ['teams' => $teams]);
        }
        return redirect()->route('manager.employee-list')->with('error', 'Access Denied!');
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
            'job_title' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
        ]);
        if ($request->role_id != 1) {
            $validate['team_id'] = $request->team_id;
        }
        $validate['created_by'] = auth()->id();
        $token = Str::random(20);
        $user = DB::transaction(function () use ($validate, $request, $token) {
            $user = User::create($validate);
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
            ]);
            if ((int) $user->role_id === 1) {
                return $user;
            }
            $joiningDate = $user->created_at;
            $remainingMonths = 12 - $joiningDate->month + 1;
            $daysInMonth = $joiningDate->daysInMonth;
            $remainingDays = $daysInMonth - $joiningDate->day + 1;
            foreach (LeaveType::all() as $leaveType) {
                $monthlyAllocated = round(($leaveType->per_month / $daysInMonth) * $remainingDays * 2) / 2;
                $allocatedLeaves = round(($leaveType->per_year / 12) * $remainingMonths * 2) / 2;
                LeaveBalance::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'type_of_leave_id' => $leaveType->id,
                        'company_year' => $joiningDate->year,
                    ],
                    [
                        'allocated_leaves' => $allocatedLeaves,
                        'total_leaves_taken' => 0,
                        'unpaid_leaves' => 0,
                        'carry_forward_days' => 0,
                    ],
                );
                LeaveMonthlyBalance::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'type_of_leave_id' => $leaveType->id,
                        'company_year' => $joiningDate->year,
                        'month' => $joiningDate->month,
                    ],
                    [
                        'allocated_leaves' => $monthlyAllocated,
                        'used_leaves' => 0,
                        'carry_forward_days' => 0,
                        'unpaid_leaves' => 0,
                    ],
                );
            }
            return $user;
        });
        Mail::to($request->email)->queue(new WelcomeMail($request->full_name));
        $password_set_link = route('password.set', ['token' => $token, 'email' => $request->email]);
        Mail::to($request->email)->queue(new PasswordMail($request->full_name, $password_set_link));
        return redirect()->route('manager.employee-list')->with('success', 'Employee Added Successfully!');
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
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('success', 'Password Set Successfully! Please login.');
            }
            return redirect()->route('login')->with('error', 'Password Update Failed');
        }
    }
    public function employee(Request $request)
    {
        $users = User::with('creator')->join('roles', 'users.role_id', '=', 'roles.id')->where('users.role_id', '!=', 1)->select('users.*', 'roles.role_name as role_name');
        if ($request->ajax()) {
            return DataTables::of($users)
                ->editColumn('gender', function ($row) {
                    if ($row->gender == 'M') {
                        return 'Male';
                    }
                    if ($row->gender == 'F') {
                        return 'Female';
                    }
                    return '<i>Others</i>';
                })
                ->addColumn('creator_name', function ($row) {
                    return $row->creator?->full_name ?? '-';
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
                ->addColumn('Action', function ($row) {
                    return "<a class='action-icon view-icon' href = '" .
                        route('admin.view.employee', $row->id) .
                        "'><i class='bi bi-eye'></i></a>
                    <a class='action-icon edit-icon' href = '" .
                        route('admin.edit.employee', $row->id) .
                        "'><i class='bi bi-pencil'></i></a>
                    <a class='action-icon delete-icon' href='" .
                        route('admin.delete.employee', $row->id) .
                        "' onclick=\"return confirm('Do you want to delete?')\">
                        <i class='bi bi-trash'></i></a>";
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="employee-checkbox" value="' . $row->id . '">';
                })
                ->rawColumns(['gender', 'Action', 'checkbox'])
                ->toJson();
        }
        return view('admin.employee.employee', ['users_count' => $users->count()]);
    }
    public function viewEmployee($id)
    {
        $user = User::with('creator')->leftJoin('roles', 'users.role_id', '=', 'roles.id')->leftJoin('teams', 'users.team_id', '=', 'teams.id')->where('users.id', $id)->select('users.*', 'roles.role_name as role_name', 'teams.team_name as team_name')->firstOrFail();
        return view('admin.employee.employee_view', ['user' => $user]);
    }
    public function editEmployee($id)
    {
        if (auth()->user()->role_id == 1) {
            $user = User::findOrFail($id);
            $teams = Team::select('id', 'team_name')->get();
            return view('admin.employee.employee_edit', compact('user', 'teams'));
        }
        return redirect()->back()->with('error', 'Access Denied!');
    }
    public function updateEmployee($id, Request $request)
    {
        if (auth()->user()->role_id == 1) {
            $validate = $request->validate([
                'full_name' => 'required|min:6',
                'email' => 'required|email|unique:users,email,' . $id,
                'phone' => 'required|unique:users,phone,' . $id,
                'gender' => 'required',
                'date_of_birth' => 'required|date',
                'role_id' => 'required',
                'job_title' => 'required',
            ]);
            if ($request->role_id != 1) {
                $validate['team_id'] = $request->team_id;
            }
            $users = User::where('id', $id)->update($validate);
            return redirect()->route('manager.employee-list')->with('success', 'Employee Updated Successfully');
        }
        return redirect()->back()->with('error', 'Access Denied');
    }
    public function deleteEmployee($id)
    {
        if (auth()->user()->role_id == 1) {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('manager.employee-list')->with('success', 'Deleted Successfully!');
        }
        return redirect()->back()->with('error', 'Access Denied !');
    }
    public function viewProfile($id)
    {
        if (auth()->user()->id == $id) {
            $user = User::with('creator')->join('roles', 'roles.id', '=', 'users.role_id')->join('teams', 'teams.id', '=', 'users.team_id')->where('users.id', $id)->first();
            return view('employee.profile', ['user' => $user]);
        }
        return redirect()->back()->with('error', 'Access Denied !');
    }
    public function bulkDelete(Request $request)
    {
        if (auth()->user()->role_id == 1) {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:users,id',
            ]);
            User::whereIn('id', $request->ids)->delete();
            return response()->json([
                'message' => 'Selected employees deleted successfully',
            ]);
        }
        return redirect()->back()->with('error', 'Access Denied !');
    }
}
