<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyHoliday extends Model
{
    protected $fillable = [
        'title',
        'holiday_date',
        'reason',
        'created_by'
    ];
}
