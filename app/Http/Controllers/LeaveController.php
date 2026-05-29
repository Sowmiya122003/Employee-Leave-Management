<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LeaveController extends Controller
{
    public function leaveType(Request $request)
    {
        $leave_type = LeaveType::leftJoin('users', function ($join) {
            $join->on('users.id', '=', 'leave_types.created_by');
        })->select('leave_types.leave_type_name as leave_type_name',
                   'leave_types.per_month as per_month',
                   'leave_types.per_year as per_year',
                   'leave_types.monthly_carry_forward as monthly_carry_forward',
                   'leave_types.yearly_carry_forward as yearly_carry_forward',
                   'users.full_name as name');
        if ($request->ajax()) {
            return DataTables::of($leave_type)->toJson();
        }
        return view('admin.leave.leave_type', compact('leave_type'));
    }
    public function leaveTypeForm()
    {
        return view('admin.leave.leave_type_form');
    }
    public function leaveTypeCreate(Request $request)
    {
        $validate = $request->validate([
            'leave_type_name' => 'required',
            'per_month' => 'required',
            'per_year' => 'required',
            'monthly_carry_forward' => 'required',
            'yearly_carry_forward' => 'required',
        ]);
        $validate['created_by'] = auth()->user()->id;
        $leave = LeaveType::create($validate);
        return redirect()->route('employee.leave.type')->with('success', 'Created Successfully');
    }
    public function leaveRequestForm()
    {
        $leave_type = LeaveType::select('id', 'leave_type_name')->get();
        return view('employee.leaverequest', compact('leave_type'));
    }
    public function createLeaveRequest(Request $request)
    {
        $alreadyexists = LeaveRequest::where('from_date', '<=', $request->to_date)->where('to_date', '>=', $request->from_date)->where('status',['approved','pending'])->exists();
        if ($alreadyexists) {
            return redirect()->route('dashboard')->with('error', 'You have already applied leave on this date range');
        }
        $validate = $request->validate([
            'type_of_leave_id' => 'required',
            'reason' => 'required',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);
        $validate['user_id'] = auth()->user()->id;
        $from_date = Carbon::parse($request->from_date);
        $to_date = Carbon::parse($request->to_date);
        if ($request->leave_duration == 'full-day') {
            $validate['requested_leaves'] = $from_date->diffInDays($to_date) + 1;
        } else {
            $validate['requested_leaves'] = 0.5;
        }
        $file_name = null;
        if ($request->hasFile('attachments')) {
            $original_name = $request->file('attachments')->getClientOriginalName();
            $file_name = time() . '.' . $original_name;
            $request->file('attachments')->move(public_path('/uploads'), $file_name);
        }
        if (!empty($file_name)) {
            $validate['attachments'] = json_encode('uploads/' . $file_name);
        }
        $leave = LeaveRequest::create($validate);
        return redirect()->route('dashboard')->with('success', 'Leave Request Created and Sent to Manager Successfully!');
    }
    public function leaveRequest()
    {
        if (auth()->user()->role_id == 2) {
            $leaves_pending = LeaveRequest::join('users', 'leave_requests.user_id', '=', 'users.id')
                ->join('leave_types', 'leave_requests.type_of_leave_id', '=', 'leave_types.id')
                ->where('users.team_id', auth()->user()->team_id)
                ->where('leave_requests.status', 'pending')
                ->select('leave_requests.id as leave_request_id', 'users.full_name', 'leave_requests.attachments', 'leave_types.leave_type_name', 'leave_requests.from_date', 'leave_requests.to_date', 'leave_requests.requested_leaves', 'leave_requests.reason as leave_reason', 'leave_requests.status as leave_status', 'leave_requests.applied_at as applied_at')
                ->get();
            foreach ($leaves_pending as $leave) {
                $leave->applied_at = Carbon::parse($leave->applied_at)->diffForHumans();
            }
            return view('manager.leaves_pending_list', compact('leaves_pending'));
        }
        elseif (auth()->user()->role_id == 3) {
            $leaves_pending = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')
                ->join('leave_types', 'leave_requests.type_of_leave_id', '=', 'leave_types.id')
                ->where('leave_requests.user_id', auth()->user()->id)
                ->select('leave_requests.id as leave_request_id', 'leave_requests.from_date', 'leave_requests.to_date', 'leave_requests.requested_leaves', 'leave_requests.reason as leave_reason', 'leave_requests.status as leave_status', 'leave_types.leave_type_name', 'users.full_name', 'leave_requests.approved_leaves as approved_leaves', 'leave_requests.attachments', 'leave_requests.rejection_reason as rejection_reason')
                ->get();
            return view('employee.leaves_list', compact('leaves_pending'));
        }
        else {
            $leaves_pending = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')->join('leave_types', 'leave_requests.type_of_leave_id', '=', 'leave_types.id')->select('leave_requests.id as leave_request_id', 'leave_requests.from_date', 'leave_requests.attachments', 'leave_requests.to_date', 'leave_requests.requested_leaves', 'leave_requests.reason as leave_reason', 'leave_requests.status as leave_status', 'leave_types.leave_type_name', 'users.full_name', 'leave_requests.approved_leaves as approved_leaves', 'leave_requests.rejection_reason as rejection_reason', 'leave_requests.action_time as action_time')->get();
            foreach ($leaves_pending as $leave) {
                $leave->action_time = Carbon::parse($leave->action_time)->diffForHumans();
            }
            return view('admin.leave.leave_list', compact('leaves_pending'));
        }
    }

    public function requestApproved(Request $request, string $id)
    {
        $approved = LeaveRequest::where('id', $id)->update([
            'approved_leaves' => $request->approved,
            'action_taken_by' => auth()->user()->id,
            'action_time' => Carbon::now(),
            'status' => 'approved',
            'rejection_reason' => null,
        ]);
        return redirect()->route('employee.leave.requests')->with('success', 'Leave Approved!');
    }
    public function requestRejected(Request $request, string $id)
    {
        $rejected = LeaveRequest::where('id', $id)->update([
            'rejection_reason' => $request->rejected,
            'action_taken_by' => auth()->user()->id,
            'action_time' => Carbon::now(),
            'status' => 'rejected',
            'approved_leaves' => null,
        ]);
        return redirect()->route('employee.leave.requests')->with('warning', 'Leave Rejected ');
    }
    public function requestCancel(string $id)
    {
        $cancelled = LeaveRequest::where('id', $id)->update([
            'status' => 'cancelled',
            'action_time' => Carbon::now(),
        ]);
        return redirect()->back()->with('success', 'Leave Request Cancelled Successfully !');
    }
}
