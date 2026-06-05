<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeaveType::insert([
            [
                'id' => 1,
                'leave_type_name' => 'Casual Leave',
                'created_by' => null,
                'per_month'=> 1,
                'per_year'=>12,
                'monthly_carry_forward'=>1,
                'yearly_carry_forward'=>5
            ],
            [
                'id' => 2,
                'leave_type_name' => 'Sick Leave',
                'created_by' => null,
                'per_month'=> 2,
                'per_year'=>24,
                'monthly_carry_forward'=>1,
                'yearly_carry_forward'=>5
            ],
            [
                'id' => 3,
                'leave_type_name' => 'Earned Leave',
                'created_by' => null,
                'per_month'=> 2,
                'per_year'=>24,
                'monthly_carry_forward'=>2,
                'yearly_carry_forward'=>5
            ],
            [
                'id' => 4,
                'leave_type_name' => 'Emergency Leave',
                'created_by' => null,
                'per_month'=> 2,
                'per_year'=>24,
                'monthly_carry_forward'=>0,
                'yearly_carry_forward'=>0
            ],
        ]);
    }
}
