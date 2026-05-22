<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['team_name','description'];
    public function manager(){
        return $this->hasOne(User::class,'team_id','id')->where('role_id',2);
    }
}
