<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    public $timestamps = false;
protected $fillable = [
    'user_id',
    'type_of_leave_id',
    'from_date',
    'to_date',
    'requested_leaves',
    'approved_leaves',
    'unpaid_leaves',
    'applied_at',
    'reason',
    'status',
    'action_taken_by',
    'action_time',
    'attachments',
    'rejection_reason'
];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function leave_type(){
        return $this->belongsTo(LeaveType::class,'type_of_leave_id');
    }
}
