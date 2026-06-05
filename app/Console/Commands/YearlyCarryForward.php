<?php

namespace App\Console\Commands;

use App\Models\LeaveBalance;
use App\Models\LeaveType;
use DB;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\User;

// #[Signature('app:yearly-carry-forward')]
// #[Description('Command description')]
class YearlyCarryForward extends Command
{
    /**
     * Execute the console command.
     */
    protected $signature = 'app:yearly-carry-forward';
    protected $description = 'Carry Forward Yearly Leave Balances';
    public function handle()
    {
        DB::transaction(function () {
            $previousyear = now()->subYear()->year;
            $currentyear = now()->year;

            $employees = User::whereIn('role_id', [2, 3])->get();
            $leaveTypes = LeaveType::all();
            foreach ($employees as $employee) {
                foreach ($leaveTypes as $leaveType) {
                    $previousBalance = LeaveBalance::where('user_id', $employee->id)->where('type_of_leave_id', $leaveType->id)->where('company_year', $previousyear)->first();
                    $remaining = 0;
                    if ($previousBalance) {
                        $remaining = $previousBalance->allocated_leaves - $previousBalance->total_leaves_taken;
                    }
                    $carryforward = max(0, min($remaining, $leaveType->yearly_carry_forward));
                    LeaveBalance::updateOrCreate(
                        [
                            'user_id' => $employee->id,
                            'type_of_leave_id' => $leaveType->id,
                            'company_year' => $currentyear,
                        ],
                        [
                            'allocated_leaves' => $leaveType->per_year + $carryforward,
                            'total_leaves_taken' => 0,
                            'unpaid_leaves' => 0,
                            'carry_forward_days' => $carryforward,
                        ],
                    );
                }
            }
        });
        $this->info('Yearly carry forward completed.');
        return self::SUCCESS;
    }
}
