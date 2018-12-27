<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\DB;

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

    //AVG
    protected $appends = ['levelavg'];

    public function getLevelavgAttribute()
    {
        $rs = DB::select('select avg(score) as levelavg from usertasks where end_time is not null and  user_id= ?',[$this->id]);
        $rs=$rs[0]->levelavg;
        if($rs<5)
            return "初级";
        elseif ($rs>=5 and $rs<6)
            return "中级";
        elseif ($rs>=6 and $rs<7)
            return "高级";
        elseif ($rs>=7 and $rs<8)
            return "称职";
        elseif ($rs>=8 and $rs<9)
            return "良好";
        elseif ($rs>=9)
            return "优秀";
    }
}
