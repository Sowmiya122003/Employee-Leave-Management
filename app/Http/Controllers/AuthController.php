<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\CompanyHoliday;
use Hash;
class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check() && Auth::user()->role_id == 2) {
            $holidays = CompanyHoliday::select('title', 'holiday_date')->get();
            $leaves_count = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')->where('users.team_id',auth()->user()->team_id)->where('users.role_id',3)->groupBy('leave_requests.status')->selectRaw('leave_requests.status,COUNT(*) as total')->get();
            $leaves_count = $leaves_count->pluck('total', 'status');
            return view('admin.dashboard', compact('holidays', 'leaves_count'));
        }
        else if (Auth::check() && Auth::user()->role_id == 3) {
            $employee_leave = LeaveRequest::join('users','users.id','=','leave_requests.user_id')->where('leave_requests.user_id',auth()->user()->id)->groupBy('status')->selectRaw('leave_requests.status,COUNT(*) as total')->get();
            $leaves_count= $employee_leave->pluck('total','status');
            // dd($leaves_count);
            return view('admin.dashboard',compact('holidays','leaves_count'));
        }
        return view('auth.login');
    }
    public function register()
    {
        if (Auth::check()) {
            $holidays = CompanyHoliday::select('title', 'holiday_date')->get();
            $leave_pending = LeaveRequest::join('users', 'leave_requests.user_id', '=', 'users.id')
                ->where('users.team_id', auth()->user()->team_id)
                ->where('leave_requests.status', 'pending')
                ->groupBy('leave_requests.status')
                ->count();
            $leave_approved = LeaveRequest::join('users', 'leave_requests.user_id', '=', 'users.id')
                ->where('users.team_id', auth()->user()->team_id)
                ->where('leave_requests.status', 'approved')
                ->groupBy('leave_requests.status')
                ->count();
            $leave_rejected = LeaveRequest::join('users', 'leave_requests.user_id', '=', 'users.id')
                ->where('users.team_id', auth()->user()->team_id)
                ->where('leave_requests.status', 'rejected')
                ->groupBy('leave_requests.status')
                ->count();
            $leave_cancelled = LeaveRequest::join('users', 'leave_requests.user_id', '=', 'users.id')
                ->where('users.team_id', auth()->user()->team_id)
                ->where('leave_requests.status', 'cancelled')
                ->groupBy('leave_requests.status')
                ->count();
            // dd($leave_approved);
            return view('admin.dashboard', compact('holidays', 'leave_pending', 'leave_approved', 'leave_rejected', 'leave_cancelled'));
        }
        elseif(User::where('role_id',1)->exists()){
            abort(403);
        }
        return view('auth.register');
    }
    public function registerSubmit(Request $request)
    {
        // dd($request->toArray());
        $validated = $request->validate([
            'full_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'address' => 'nullable|string',
            'password' => 'required|min:8',
        ]);
        $validated['password'] = Hash::make($validated['password']);
        $validated['role_id'] = 1;
        $validated['job_title'] = 'Admin';
        // dd($validated);
        // $user= new User();
        // dd($user->getFillable());
        $user = User::create($validated);
        // dd($user);
        return redirect()->route('login');
    }
    public function loginSubmit(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // dd($request->toArray());
            $user = User::where('email', $request->email)->first();
            // dd(auth()->user()->name);
            return redirect()->route('dashboard');
        }
        return redirect()->route('login')->with('error', 'Incorrect Credentials');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
