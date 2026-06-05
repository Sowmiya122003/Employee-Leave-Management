<?php

namespace Database\Seeders;

use App\Models\LeaveMonthlyBalance;
use App\Models\LeaveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class LeaveMonthlyBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = User::whereIn('role_id', [2, 3])->get();
        $leaveTypes = LeaveType::all();
        foreach ($employees as $employee) {
            $joiningDate = Carbon::parse($employee->created_at);
            foreach ($leaveTypes as $leaveType) {
                $daysInMonth = $joiningDate->daysInMonth;
                $remainingDays = $daysInMonth - $joiningDate->day + 1;
                $allocatedLeaves = round(($leaveType->per_month / $daysInMonth) * $remainingDays, 2);
                LeaveMonthlyBalance::firstOrCreate(
                    [
                        'user_id' => $employee->id,
                        'type_of_leave_id' => $leaveType->id,
                        'company_year' => now()->year,
                        'month' => now()->month,
                    ],
                    [
                        'allocated_leaves' => $allocatedLeaves,
                        'used_leaves' => 0,
                        'unpaid_leaves' => 0,
                        'carry_forward_days' => 0,
                    ]
                );
            }
        }
    }
}
