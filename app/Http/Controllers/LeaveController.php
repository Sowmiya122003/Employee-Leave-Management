<?php

namespace App\Http\Controllers;

use App\Mail\LeaveActionMail;
use App\Mail\LeaveRequestMail;
use App\Models\LeaveBalance;
use App\Models\CompanyHoliday;
use App\Models\LeaveMonthlyBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Notifications\LeaveAppliedNotofication;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Mail;
class LeaveController extends Controller
{
    public function leaveType(Request $request)
    {
        $leave_type = LeaveType::leftJoin('users', function ($join) {
            $join->on('users.id', '=', 'leave_types.created_by');
        })->select('leave_types.id as id', 'leave_types.leave_type_name as leave_type_name', 'leave_types.per_month as per_month', 'leave_types.per_year as per_year', 'leave_types.monthly_carry_forward as monthly_carry_forward', 'leave_types.yearly_carry_forward as yearly_carry_forward', 'users.full_name as name');
        if ($request->ajax()) {
            return DataTables::of($leave_type)
                ->addColumn('Action', function ($row) {
                    return "<a class='action-icon edit-icon' href = '" .
                        route('admin.edit.leavetype', $row->id) .
                        "'><i class='bi bi-pencil'></i></a>
                    <a class='action-icon delete-icon' href='" .
                        route('admin.delete.leavetype', $row->id) .
                        "' onclick=\"return confirm('Do you want to delete?')\">
                        <i class='bi bi-trash'></i></a>";
                })
                ->rawColumns(['Action'])
                ->toJson();
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
        $users = User::whereIn('role_id', [2, 3])->get();
        foreach ($users as $user) {
            LeaveBalance::create([
                'user_id' => $user->id,
                'type_of_leave_id' => $leave->id,
                'company_year' => now()->year,
                'allocated_leaves' => $this->allocatedLeavesForUser($user, $leave),
                'total_leaves_taken' => 0,
                'unpaid_leaves' => 0,
                'carry_forward_days' => 0,
            ]);
            LeaveMonthlyBalance::create([
                'user_id' => $user->id,
                'type_of_leave_id' => $leave->id,
                'company_year' => now()->year,
                'month' => now()->month,
                'allocated_leaves' => $this->allocatedLeavesMonthly($user, $leave),
                'used_leaves' => 0,
                'unpaid_leaves' => 0,
                'carry_forward_days' => 0,
            ]);
        }
        return redirect()->route('employee.leave.type')->with('success', 'Created Successfully');
    }
    public function leaveRequestForm()
    {
        $leave_type = LeaveType::select('id', 'leave_type_name')->get();
        $leavebalances = LeaveMonthlyBalance::where('user_id', auth()->id())
            ->get()
            ->keyBy('type_of_leave_id');
        $companyHolidays = CompanyHoliday::pluck('holiday_date')->map(fn($date) => Carbon::parse($date)->toDateString())->values();
        return view('employee.leaverequest', compact('leave_type', 'leavebalances', 'companyHolidays'));
    }
    public function createLeaveRequest(Request $request)
    {
        $validate = $request->validate([
            'type_of_leave_id' => 'required',
            'reason' => 'required',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);
        $validate['user_id'] = auth()->user()->id;
        $validate['status'] = 'pending';
        $alreadyexists = LeaveRequest::where('user_id', auth()->user()->id)
            ->where('from_date', '<=', $validate['to_date'])
            ->where('to_date', '>=', $validate['from_date'])
            ->whereIn('status', ['approved', 'pending'])
            ->exists();
        if ($alreadyexists) {
            return redirect()->route('dashboard')->with('error', 'You have already applied leave on this date range');
        }
        $from_date = Carbon::parse($request->from_date);
        $to_date = Carbon::parse($validate['to_date']);
        if ($request->leave_duration == 'full-day') {
            $validate['requested_leaves'] = $this->workingLeaveDays($from_date, $to_date);
        } else {
            if (!$this->isWorkingDay($from_date)) {
                return redirect()->back()->withInput()->with('error', 'Leave cannot be applied on weekends or company holidays.');
            }
            $validate['requested_leaves'] = 0.5;
        }
        if ($validate['requested_leaves'] <= 0) {
            return redirect()->back()->withInput()->with('error', 'Selected dates contain no working days.');
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
        $leaveBalance = LeaveBalance::where('user_id', auth()->id())
            ->where('type_of_leave_id', $request->type_of_leave_id)
            ->where('company_year', now()->year)
            ->first();
        if (!$leaveBalance) {
            return redirect()->back()->withInput()->with('error', 'Leave balance is not configured for this leave type.');
        }
        $availableLeaves = (float) $leaveBalance->allocated_leaves + (float) $leaveBalance->carry_forward_days - (float) $leaveBalance->total_leaves_taken;
        $unpaidLeaves = max(0, (float) $validate['requested_leaves'] - max(0, $availableLeaves));
        if ($unpaidLeaves > 0 && !$request->boolean('is_lop_accepted')) {
            return redirect()->back()->withInput()->with('error', 'Please accept Loss of Pay for excess leave days.');
        }
        if ($unpaidLeaves > 0) {
            $validate['unpaid_leaves'] = $unpaidLeaves;
        } else {
            $validate['unpaid_leaves'] = '0.00';
        }
        $leave = LeaveRequest::create($validate);
        $manager = null;
        if ($leave->user->role_id == 3) {
            $manager = User::where('team_id', $leave->user->team_id)->where('role_id', 2)->first();
        }
        $admins = User::where('role_id', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new LeaveAppliedNotofication($leave));
            Mail::to($admin->email)->queue(new LeaveRequestMail($leave, $admin));
        }
        if ($manager) {
            $manager->notify(new LeaveAppliedNotofication($leave));
            Mail::to($manager->email)->queue(new LeaveRequestMail($leave, $manager));
        }
        return redirect()->route('dashboard')->with('success', 'Leave Request Created Successfully!');
    }
    public function leaveRequest()
    {
        if (auth()->user()->role_id == 2) {
            $leaves_pending = LeaveRequest::join('users', 'leave_requests.user_id', '=', 'users.id')
                ->join('leave_types', 'leave_requests.type_of_leave_id', '=', 'leave_types.id')
                ->where('users.team_id', auth()->user()->team_id)
                ->where('users.role_id', 3)
                ->whereIn('leave_requests.status', ['pending', 'cancelled'])
                ->select('leave_requests.id as leave_request_id', 'users.full_name', 'leave_requests.attachments', 'leave_types.leave_type_name', 'leave_requests.from_date', 'leave_requests.to_date', 'leave_requests.requested_leaves', 'leave_requests.unpaid_leaves', 'leave_requests.reason as leave_reason', 'leave_requests.status as leave_status', 'leave_requests.applied_at as applied_at')
                ->paginate(10);
            foreach ($leaves_pending as $leave) {
                $leave->applied_at = Carbon::parse($leave->applied_at)->diffForHumans();
            }
            return view('manager.leaves_pending_list', compact('leaves_pending'));
        } elseif (auth()->user()->role_id == 3) {
            $leaves_pending = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')
                ->join('leave_types', 'leave_requests.type_of_leave_id', '=', 'leave_types.id')
                ->where('leave_requests.user_id', auth()->user()->id)
                ->select('leave_requests.id as leave_request_id', 'leave_requests.from_date', 'leave_requests.to_date', 'leave_requests.requested_leaves', 'leave_requests.reason as leave_reason', 'leave_requests.status as leave_status', 'leave_types.leave_type_name', 'users.full_name', 'leave_requests.approved_leaves as approved_leaves', 'leave_requests.attachments', 'leave_requests.rejection_reason as rejection_reason')
                ->get();
            return view('employee.leaves_list', compact('leaves_pending'));
        } else {
            $leaves_pending = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')->join('leave_types', 'leave_requests.type_of_leave_id', '=', 'leave_types.id')->select('leave_requests.id as leave_request_id', 'leave_requests.from_date', 'leave_requests.attachments', 'leave_requests.to_date', 'leave_requests.requested_leaves', 'leave_requests.unpaid_leaves', 'leave_requests.reason as leave_reason', 'leave_requests.status as leave_status', 'leave_types.leave_type_name', 'users.full_name', 'leave_requests.approved_leaves as approved_leaves', 'leave_requests.unpaid_leaves as unpaid_leaves', 'leave_requests.rejection_reason as rejection_reason', 'leave_requests.action_time as action_time')->paginate(10);
            foreach ($leaves_pending as $leave) {
                $leave->action_time = $leave->action_time ? Carbon::parse($leave->action_time)->diffForHumans() : '-';
            }
            return view('admin.leave.leave_list', compact('leaves_pending'));
        }
    }
    public function requestApproved(Request $request, string $id)
    {
        $request->validate([
            'approved' => 'required|numeric|min:0.5',
        ]);
        if ((float) $request->approved * 2 != floor((float) $request->approved * 2)) {
            return redirect()->back()->with('error', 'Approved leaves must be in half-day increments.');
        }
        $approvedrequest = LeaveRequest::with('user')->findOrFail($id);
        if (!$this->canActOnLeaveRequest($approvedrequest)) {
            return redirect()->back()->with('error', 'Access Denied!');
        }
        if ($approvedrequest->status == 'approved' || $approvedrequest->status == 'cancelled') {
            return redirect()->back()->with('error', 'This request cannot be approved.');
        }
        if ($approvedrequest->status == 'cancelled') {
            return redirect()->back()->with('error', 'Cancelled leave requests cannot be approved.');
        }
        if ((float) $request->approved > (float) $approvedrequest->requested_leaves) {
            return redirect()->back()->with('error', 'Approved leaves cannot exceed requested leaves.');
        }
        $updated = DB::transaction(function () use ($approvedrequest, $request) {
            $leave_yearly_balance = LeaveBalance::where('user_id', $approvedrequest->user_id)->where('type_of_leave_id', $approvedrequest->type_of_leave_id)->where('company_year', now()->year)->lockForUpdate()->first();
            if (!$leave_yearly_balance) {
                return false;
            }
            $approvedLeaves = (float) $request->input('approved');
            $leave_yearly_balance->total_leaves_taken += $approvedLeaves;
            $leave_yearly_balance->unpaid_leaves = max(0, $leave_yearly_balance->total_leaves_taken - ((float) $leave_yearly_balance->allocated_leaves + (float) $leave_yearly_balance->carry_forward_days));
            $leave_yearly_balance->save();
            $leave_monthly_balance = LeaveMonthlyBalance::firstOrCreate(
                [
                    'user_id' => $approvedrequest->user_id,
                    'type_of_leave_id' => $approvedrequest->type_of_leave_id,
                    'company_year' => now()->year,
                    'month' => now()->month,
                ],
                [
                    'allocated_leaves' => 0,
                    'used_leaves' => 0,
                    'unpaid_leaves' => 0,
                    'carry_forward_days' => 0,
                ],
            );
            $leave_monthly_balance->used_leaves += $approvedLeaves;
            $leave_monthly_balance->unpaid_leaves = max(0, $leave_monthly_balance->used_leaves - ((float) $leave_monthly_balance->allocated_leaves + (float) $leave_monthly_balance->carry_forward_days));
            $leave_monthly_balance->save();
            $approvedrequest->update([
                'approved_leaves' => $approvedLeaves,
                'unpaid_leaves' => $leave_monthly_balance->unpaid_leaves,
                'action_taken_by' => auth()->user()->id,
                'action_time' => Carbon::now(),
                'status' => 'approved',
                'rejection_reason' => null,
            ]);
            return true;
        });
        if (!$updated) {
            return redirect()->back()->with('error', 'Leave balance is not configured for this leave request.');
        }
        // dd($approvedrequest);
        $user = $approvedrequest->user;
        $user->notify(new LeaveAppliedNotofication($approvedrequest));
        Mail::to($user->email)->queue(new LeaveActionMail($approvedrequest));
        return redirect()->route('employee.leave.requests')->with('success', 'Leave Approved!');
    }
    public function requestRejected(Request $request, string $id)
    {
        $request->validate([
            'rejected' => 'required|string',
        ]);
        $rejectedRequest = LeaveRequest::with('user')->findOrFail($id);
        if (!$this->canActOnLeaveRequest($rejectedRequest)) {
            return redirect()->back()->with('error', 'Access Denied!');
        }
        if ($rejectedRequest->status == 'rejected' || $rejectedRequest->status == 'cancelled') {
            return redirect()->back()->with('error', 'This request cannot be rejected.');
        }
        if ($rejectedRequest->status == 'cancelled') {
            return redirect()->back()->with('error', 'Cancelled leave requests cannot be rejected.');
        }
        DB::transaction(function () use ($rejectedRequest, $request) {
            if ($rejectedRequest->status == 'approved') {
                $leaveBalance = LeaveBalance::where('user_id', $rejectedRequest->user_id)->where('type_of_leave_id', $rejectedRequest->type_of_leave_id)->where('company_year', now()->year)->lockForUpdate()->first();
                if ($rejectedRequest->status == 'approved') {
                    $approvedLeaves = (float) $rejectedRequest->approved_leaves;
                    $leaveDate = Carbon::parse($rejectedRequest->from_date);
                    $leaveYear = $leaveDate->year;
                    $leaveMonth = $leaveDate->month;
                    $leaveBalance = LeaveBalance::where('user_id', $rejectedRequest->user_id)->where('type_of_leave_id', $rejectedRequest->type_of_leave_id)->where('company_year', $leaveYear)->lockForUpdate()->first();
                    if ($leaveBalance) {
                        $leaveBalance->total_leaves_taken = max(0, (float) $leaveBalance->total_leaves_taken - $approvedLeaves);
                        $leaveBalance->unpaid_leaves = max(0, $leaveBalance->total_leaves_taken - ((float) $leaveBalance->allocated_leaves + (float) $leaveBalance->carry_forward_days));
                        $leaveBalance->save();
                    }
                    $leaveMonthlyBalance = LeaveMonthlyBalance::where('user_id', $rejectedRequest->user_id)->where('type_of_leave_id', $rejectedRequest->type_of_leave_id)->where('company_year', $leaveYear)->where('month', $leaveMonth)->lockForUpdate()->first();
                    if ($leaveMonthlyBalance) {
                        $leaveMonthlyBalance->used_leaves = max(0, (float) $leaveMonthlyBalance->used_leaves - $approvedLeaves);
                        $leaveMonthlyBalance->unpaid_leaves = max(0, $leaveMonthlyBalance->used_leaves - ((float) $leaveMonthlyBalance->allocated_leaves + (float) $leaveMonthlyBalance->carry_forward_days));
                        $leaveMonthlyBalance->save();
                    }
                }
            }
            $rejectedRequest->update([
                'rejection_reason' => $request->rejected,
                'action_taken_by' => auth()->user()->id,
                'action_time' => Carbon::now(),
                'status' => 'rejected',
                'approved_leaves' => '0.00',
                'unpaid_leaves' => '0.00',
            ]);
        });
        $user = $rejectedRequest->user;
        $user->notify(new LeaveAppliedNotofication($rejectedRequest));
        Mail::to($user->email)->queue(new LeaveActionMail($rejectedRequest));
        return redirect()->route('employee.leave.requests')->with('warning', 'Leave Rejected ');
    }
    public function requestCancel(string $id)
    {
        $cancelled = LeaveRequest::where('id', $id)->firstOrFail();
        if ($cancelled->user_id == auth()->user()->id) {
            if ($cancelled->status != 'pending') {
                return redirect()->back()->with('error', 'Only pending leave requests can be cancelled.');
            }
            $cancelled->update([
                'status' => 'cancelled',
                'action_time' => Carbon::now(),
            ]);
        } else {
            return redirect()->back()->with('error', 'Access Denied!');
        }
        $manager = User::where('team_id', auth()->user()->team_id)
            ->where('role_id', 2)
            ->first();
        $admins = User::where('role_id', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new LeaveAppliedNotofication($cancelled));
            Mail::to($admin->email)->queue(new LeaveActionMail($cancelled));
        }
        if ($manager) {
            $manager->notify(new LeaveAppliedNotofication($cancelled));
            Mail::to($manager->email)->queue(new LeaveActionMail($cancelled));
        } else {
            return redirect()->route('dashboard')->with('warning', 'Leave Request Created, but no manager is assigned to your team.');
        }
        return redirect()->back()->with('success', 'Leave Request Cancelled Successfully !');
    }
    public function editLeaveType(string $id)
    {
        if (auth()->user()->role_id == 1) {
            $leavetype = LeaveType::where('id', $id)->firstOrFail();
            return view('admin.leave.edit_leavetype', compact('leavetype'));
        }
        return redirect()->route('employee.leave.type')->with('error', 'Access Denied !');
    }
    public function updateLeaveType(Request $request, string $id)
    {
        if (auth()->user()->role_id == 1) {
            $leaveType = LeaveType::findOrFail($id);
            $leaveType->update([
                'leave_type_name' => $request->leave_type_name,
                'per_month' => $request->per_month,
                'per_year' => $request->per_year,
                'monthly_carry_forward' => $request->monthly_carry_forward,
                'yearly_carry_forward' => $request->yearly_carry_forward,
            ]);
            LeaveBalance::where('type_of_leave_id', $leaveType->id)
                ->where('company_year', now()->year)
                ->get()
                ->each(function ($balance) use ($leaveType) {
                    $user = User::find($balance->user_id);
                    if ($user) {
                        $balance->allocated_leaves = $this->allocatedLeavesForUser($user, $leaveType);
                        $balance->unpaid_leaves = max(0, (float) $balance->total_leaves_taken - ((float) $balance->allocated_leaves + (float) $balance->carry_forward_days));
                        $balance->save();
                    }
                });
            return redirect()->route('employee.leave.type')->with('success', 'Leave Type Updated Successfully!');
        }
        return redirect()->route('employee.leave.type')->with('error', 'Access Denied !');
    }
    public function destroyLeaveType(Request $request, string $id)
    {
        if (auth()->user()->role_id == 1) {
            $leaveType = LeaveType::where('id', $id)->firstOrFail();
            $leaveType->delete();
            return redirect()->back()->with('success', 'Leave Type Deleted Successfully!');
        }
        return redirect()->route('employee.leave.type')->with('error', 'Access Denied !');
    }
    private function workingLeaveDays(Carbon $fromDate, Carbon $toDate): float
    {
        $days = 0;
        $currentDate = $fromDate->copy();
        while ($currentDate->lte($toDate)) {
            if ($this->isWorkingDay($currentDate)) {
                $days++;
            }
            $currentDate->addDay();
        }
        return (float) $days;
    }
    private function isWorkingDay(Carbon $date): bool
    {
        if ($date->isSunday()) {
            return false;
        }
        return !CompanyHoliday::whereDate('holiday_date', $date->toDateString())->exists();
    }
    private function canActOnLeaveRequest(LeaveRequest $leaveRequest): bool
    {
        if (auth()->user()->role_id == 1) {
            return true;
        }
        return auth()->user()->role_id == 2 && $leaveRequest->user && $leaveRequest->user->role_id == 3 && $leaveRequest->user->team_id == auth()->user()->team_id;
    }
    private function allocatedLeavesForUser(User $user, LeaveType $leaveType): float
    {
        $joiningDate = Carbon::parse($user->created_at);
        $remainingMonths = $joiningDate->year == now()->year ? 12 - $joiningDate->month + 1 : 12;
        return round(((float) $leaveType->per_year / 12) * $remainingMonths, 2);
    }
    private function allocatedLeavesMonthly(User $user, LeaveType $leaveType): float
    {
        $joiningDate = Carbon::parse($user->created_at);
        if ($joiningDate->day <= 15) {
            return $leaveType->per_month;
        }
        return round($leaveType->per_month / 2);
    }
}
