<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Userpay  extends Model
{
    protected $table = 'userpays';
    protected $appends = ['obj_name'];

    public function getObjNameAttribute()
    {
        if($this->object_type == '车辆'){
            $rs = DB::select('select car_number as cc from cars where id = ?',[$this->object_id]);
            if(count($rs)>0){
                return $rs[0]->cc;
            }else
                return null;
        }else if ($this->object_type == '员工'){
            $rs = DB::select('select name as cc from users where id = ?',[$this->object_id]);
            if(count($rs)>0){
                return $rs[0]->cc;
            }else
                return null;
        }else
            return null;
    }
}