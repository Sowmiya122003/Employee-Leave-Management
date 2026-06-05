<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\CompanyHoliday;
use Hash;
class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }
    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } elseif (User::where('role_id', 1)->exists()) {
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
