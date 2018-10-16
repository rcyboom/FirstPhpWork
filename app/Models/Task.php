<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Task  extends Model
{
    //
    protected $appends = ['customer_name'];

    public function getCustomerNameAttribute()
    {
            $rs = DB::select('select name as cc from customers where id = ?',[$this->customer_id]);
            if(count($rs)>0){
                return $rs[0]->cc;
            }else
                return null;
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }

    public function scopeCustomerName($query)
    {
        //customer_name 过滤
        $name = request()->input('customer_name');
        if (isset($name)) {
            $customer_id=Customer::where('name', 'like', '%'.$name.'%')->select('id');
            return $query = $query->whereIn('customer_id', $customer_id);
        } else {
            return $query;
        }
    }

    public function scopeTitle($query)
    {
        //task_title 过滤
        $title = request()->input('task_title');
        if (isset($title)) {
            return $query = $query->where('title', 'like', '%'.$title.'%');
        } else {
            return $query;
        }
    }

    public function scopeLinkMan($query)
    {
        //linkman 过滤
        $linkman = request()->input('linkman');
        if (isset($linkman)) {
            return $query = $query->where('linkman', 'like', '%'.$linkman.'%');
        } else {
            return $query;
        }
    }

}