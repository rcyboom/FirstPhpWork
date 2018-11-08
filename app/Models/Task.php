<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
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
        //title 过滤
        $title = request()->input('title');
        if (isset($title)) {
            return $query = $query->where('title', 'like', '%'.$title.'%');
        } else {
            return $query;
        }
    }

    public function scopeOther($query)
    {
        // state    任务状态  默认为全部，我给你一个API，这个API返回的选项作为下拉列表
        // startTime 开始时间  默认为当前时间前推一个月
        // endTime   截至时间  默认为当前时间
        $state = request()->input('state','全部');
        if ($state!='全部') {
            $query = $query->where('state', $state);
        }
        $startTime=request()->input('startTime',Carbon::today()->subDays(30));
        $endTime=request()->input('endTime',Carbon::now());
        $query = $query->where('check_time','>=', $startTime)
            ->where('check_time','<=', $endTime);
        return $query;
    }

}