<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'avatar',
        'phone_number','sex', 'state',
        'birthday','work_time','card_type',
        'card_number','duty','level',
        'from','fix_salary','work_salary',
        'extra_salary','family_address','personal_address',
        'remark'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeName($query)
    {
        $name = request()->input('name');
        if (isset($name)) {
            return $query = $query->where('name', 'like', '%'.$name.'%');
        } else {
            return $query;
        }
    }

    public function scopeEmail($query)
    {
        $email = request()->input('email');
        if (isset($email)) {
            return $query = $query->where('email', 'like', '%'.$email.'%');
        } else {
            return $query;
        }
    }

    public function scopeState($query)
    {
        $state = request()->input('state');
        if (isset($state)) {
            return $query = $query->where('state', 'like', '%'.$state.'%');
        } else {
            return $query;
        }
    }

    public function scopePhone($query)
    {
        $phone = request()->input('phone_number');
        if (isset($phone)) {
            return $query = $query->where('phone_number', 'like', '%'.$phone.'%');
        } else {
            return $query;
        }
    }
}
