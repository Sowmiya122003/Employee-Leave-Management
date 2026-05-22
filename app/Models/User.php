<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


// #[Fillable(['full_name', 'email','role_id','team_id','job_title','phone','date_of_birth','address','gender','created_by','status','password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'role_id',
        'team_id',
        'job_title',
        'phone',
        'date_of_birth',
        'address',
        'gender',
        'created_by',
        'status',
        'password'
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status'=>'boolean'
        ];
    }
    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }
    // public function teamManager(){
    //     return $this->belongsTo(User::class,'team_id','team_id')->where('role_id',2);
    // }
}
