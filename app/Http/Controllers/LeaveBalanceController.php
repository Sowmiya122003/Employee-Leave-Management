<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LeaveBalanceController extends Controller
{
    public function index(Request $request)
    {
        if (! in_array(auth()->user()->role_id, [1, 2])) {
            return redirect()->route('dashboard')->with('error', 'Access Denied!');
        }
        $balances = User::leftJoin('leave_balances', function ($join) {
                $join->on('users.id', '=', 'leave_balances.user_id')
                    ->where('leave_balances.company_year', now()->year);
            })
            ->leftJoin('teams', 'teams.id', '=', 'users.team_id')
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->whereIn('users.role_id', [2, 3])
            ->when(auth()->user()->role_id == 2, function ($query) {
                $query->where('users.team_id', auth()->user()->team_id)
                    ->where('users.role_id', 3);
            })
            ->select(
                'users.id',
                'users.full_name',
                'roles.role_name',
                'teams.team_name',
                DB::raw(now()->year . ' as company_year'),
                DB::raw('COALESCE(SUM(leave_balances.allocated_leaves + leave_balances.carry_forward_days), 0) as total_leave_allocated'),
                DB::raw('COALESCE(SUM(leave_balances.total_leaves_taken), 0) as total_leave_taken'),
                DB::raw('COALESCE(SUM(leave_balances.allocated_leaves + leave_balances.carry_forward_days - leave_balances.total_leaves_taken), 0) as balance_leave')
            )
            ->groupBy('users.id', 'users.full_name', 'roles.role_name', 'teams.team_name');
        if ($request->ajax()) {
            return DataTables::of($balances)
                ->addIndexColumn()
                ->orderColumn('company_year', false)
                ->orderColumn('total_leave_allocated', 'COALESCE(SUM(leave_balances.allocated_leaves + leave_balances.carry_forward_days), 0) $1')
                ->orderColumn('total_leave_taken', 'COALESCE(SUM(leave_balances.total_leaves_taken), 0) $1')
                ->orderColumn('balance_leave', 'COALESCE(SUM(leave_balances.allocated_leaves + leave_balances.carry_forward_days - leave_balances.total_leaves_taken), 0) $1')
                ->editColumn('total_leave_allocated', function ($row) {
                    return number_format((float) $row->total_leave_allocated, 2);
                })
                ->editColumn('total_leave_taken', function ($row) {
                    return number_format((float) $row->total_leave_taken, 2);
                })
                ->editColumn('balance_leave', function ($row) {
                    $badge = $row->balance_leave < 0 ? 'text-bg-danger' : 'text-bg-success';

                    return '<span class="badge ' . $badge . '">' . number_format((float) $row->balance_leave, 2) . '</span>';
                })
                ->rawColumns(['balance_leave'])
                ->toJson();
        }
        return view('admin.leave.leave_balance');
    }
}
