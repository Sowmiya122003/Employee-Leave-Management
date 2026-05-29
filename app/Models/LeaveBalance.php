<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'company_year',
        'total_leave_taken',
        'unpaid_leaves',
        'carry_forward_days'
        ];
}
