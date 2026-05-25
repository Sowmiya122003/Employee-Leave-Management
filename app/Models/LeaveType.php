<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = [
        'leave_type_name',
        'per_month',
        'per_year',
        'monthly_carry_forward',
        'yearly_carry_forward',
        'created_by'
    ];
    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
