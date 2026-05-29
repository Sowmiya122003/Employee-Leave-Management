<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use App\Models\LeaveBalance;
class LeaveBalanceController extends Controller
{
    public function leaveBalance(){
        $leavebalance = LeaveRequest::join('leave_types','leave_types.id','=','leave_requests.type_of_leave_id')->groupBy('user_id','leave_requests.id')
        ->where('leave_requests.status','approved')
            ->selectRaw('leave_types.id,leave_requests.type_of_leave_id,leave_types.leave_type_name,user_id, SUM(approved_leaves) as total_leave_taken')
            ->orderBy('user_id')->get();

        // dd($leavebalance->toArray());
    }
}
