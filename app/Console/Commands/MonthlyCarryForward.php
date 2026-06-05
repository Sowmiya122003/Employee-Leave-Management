<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\LeaveType;
use App\Models\LeaveMonthlyBalance;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonthlyCarryForward extends Command
{
    protected $signature = 'app:monthly-carry-forward';
    protected $description = 'Carry forward monthly leave balances';
    public function handle()
    {
        DB::transaction(function () {
            // $previousMonthDate = now()->subMonth();
            // $previousYear = $previousMonthDate->year;
            // $previousMonth = $previousMonthDate->month;
            $runDate = now();
            $previousMonthDate = $runDate->copy()->subMonth();
            $previousYear = $previousMonthDate->year;
            $previousMonth = $previousMonthDate->month;
            $currentYear = $runDate->year;
            $currentMonth = $runDate->month;

            // $currentYear = now()->year;
            // $currentMonth = now()->month;'
            $employees = User::whereIn('role_id', [2,3])->get();
            $leaveTypes = LeaveType::all();
            foreach ($employees as $employee) {
                foreach ($leaveTypes as $leaveType) {
                    $previousBalance = LeaveMonthlyBalance::where('user_id', $employee->id)->where('type_of_leave_id', $leaveType->id)->where('company_year', $previousYear)->where('month', $previousMonth)->first();
                    $remaining = 0;
                    if ($previousBalance) {
                        $remaining = $previousBalance->allocated_leaves - $previousBalance->used_leaves;
                    }
                    $carryForward = max(0, min($remaining, $leaveType->monthly_carry_forward));
                    LeaveMonthlyBalance::updateOrCreate(
                        [
                            'user_id' => $employee->id,
                            'type_of_leave_id' => $leaveType->id,
                            'company_year' => $currentYear,
                            'month' => $currentMonth,
                        ],
                        [
                            'allocated_leaves' => $leaveType->per_month + $carryForward,
                            'used_leaves' => 0,
                            'carry_forward_days' => $carryForward,
                            'unpaid_leaves' => 0,
                        ],
                    );
                }
            }
        });
        $this->info('Monthly carry forward completed.');
        return self::SUCCESS;
    }
}
