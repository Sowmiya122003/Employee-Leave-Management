<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveMonthlyBalance extends Model
{
    protected $fillable = [
        'user_id',
        'type_of_leave_id',
        'company_year',
        'month',
        'allocated_leaves',
        'used_leaves',
        'unpaid_leaves',
        'carry_forward_days'
    ];
}
