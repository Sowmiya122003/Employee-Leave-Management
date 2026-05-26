<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function leaveType(){
        $leave_type = LeaveType::with('creator')->get();
        // dd($leave_type->toArray());
        // dd($leave_type->creator->full_name);
        return view('admin.leave.leave_type',compact('leave_type'));
    }
    public function leaveTypeForm(){
        return view('admin.leave.leave_type_form');
    }
    public function leaveTypeCreate(Request $request){
        // dd($request->toArray());
        $validate = $request->validate([
            'leave_type_name'=>'required',
            'per_month'=> 'required',
            'per_year'=>'required',
            'monthly_carry_forward'=>'required',
            'yearly_carry_forward'=>'required'
        ]);
        $validate['created_by'] = auth()->user()->id;
        $leave=LeaveType::create($validate);
        return redirect()->route('leave.type')->with('success','Created Successfully');
    }
    public function leaveRequest(){
        $leave_type = LeaveType::select('id','leave_type_name')->get();
        return view('employee.leaverequest',compact('leave_type'));
    }
    public function createLeaveRequest(Request $request){
        dd($request->toArray());
    }
}
