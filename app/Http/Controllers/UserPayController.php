<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Car;
use App\Models\Userpay;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;

class UserPayController extends Controller
{
    use Result;

    /**
     * @api {get} /api/userpays/index 1.人员奖惩列表
     * @apiGroup 人员奖惩
     *@apiHeaderExample 简要说明
     * 1、路由名称 userpays.index
     * 2、可选参数
     * pageSize 分页数量，默认为15
     * object_type 奖惩对象类型，范围：车辆、员工,默认为空表示全部
     * object_id  奖惩对象ID，人员或者车辆ID，默认为空表示全部,精确匹配
     * account_id  结算ID，默认为空表示全部,精确匹配，0表示未结算
     * @apiParamExample数据库表结构
     * id 奖惩编号
     * object_id 奖惩对象编号
     * object_name 奖惩对象名称
     * account_id 结算编号，也就是收支记录编号
     * time 奖惩时间
     * type 奖惩类型，奖励、惩罚、预支
     * money 金额
     * score 奖惩评分
     * reason 奖惩原因说明
     * created_at 创建记录时间，服务器时间，不可更改
     */
    public function index()
    {
        $pageSize = (int)Request::input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:15;

        $object_name=Request::input('object_name');
        $object_id=Request::input('object_id');
        $account_id=Request::input('account_id');

        $rs = Userpay::where('id','>',0);
        if($object_name){
            $usrID =  DB::table('users')->where('name','like', '%'.$object_name.'%')->pluck('id');
            $rs = $rs->whereIn('object_id',$usrID);
        }
        if($object_id){
            $rs = $rs->where('object_id',$object_id);
        }
        if($account_id){
            $rs = $rs->where('account_id',$account_id);
        }

        $rs = $rs->orderBy('id', 'desc')->paginate($pageSize);
        return $this->myResult(1,'获取信息成功！',$rs);
    }

    /**
     * @api {get} /api/userpays/{id} 2.获取指定编号的奖惩信息
     * @apiGroup 人员奖惩
     *@apiHeaderExample 简要说明
     * 1、路由名称 userpays.getOne
     * 2、必须传递ID，正整数
     * @apiParamExample 关于奖惩的其他说明
     * account_id为工资结算单的id，不允许修改的，为0显示为未结算，大于0显示为已结算
     */
    public function getOne($id)
    {
        $rs=Userpay::find($id);
        if($rs){
            return $this->myResult(1,'获取信息成功！',$rs);
        }
        return $this->myResult(0,'未找到对应的编号信息！',null);
    }

    /**
     * @api {post} /api/userpays/{id?} 3.新增或更新一条奖惩信息
     * @apiGroup 人员奖惩
     *@apiHeaderExample 简要说明
     * 1、路由名称 userpays.saveOne
     * 2、必选参数：
	 *  id，奖惩编号，作为url必填,大于0表示更新，否则新增
     * 	object_id 正整数，人员或者车辆编号，且必须真实存在
     * 	object_type 可选项：员工、车辆
     * 	time 日期时间型，奖惩时间
     * 	type 字符串20，奖惩类型，可选项：应加、应减，补贴，预支
     * 	money 数字类型，两位小数，奖惩金额
     * 	score 数字类型，两位小数，奖惩评分
     * 	reason 字符串，奖惩原因
     */
    public function saveOne($id=0)
    {
        $validator = Validator::make( Request::all(), [
            'object_id' => 'required | integer | min:1',
            'object_type' => 'required |in:员工,车辆',
            'time' => 'required | date',
            'type' => 'required',
            'reason' => 'required',
            'money'=>'required | numeric',
            'score'=>'required | numeric',
        ]);

        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }
        $usrName='';
        if(Request::input('object_type')=='员工') {
            $ur = User::find(Request::input('object_id'));
            $usrName=$ur->name;
            if (!$ur) {
                return $this->myResult(0, '更新失败，未找到对应的人员编号！', null);
            }
        }else{
            $ur = Car::find(Request::input('object_id'));
            $usrName=$ur->car_number;
            if (!$ur) {
                return $this->myResult(0, '更新失败，未找到对应的车辆编号！', null);
            }
        }

        if($id>0){
            $rs= Userpay::find($id);
            if(!$rs){
                return $this->myResult(0,'更新失败，未找到该编号的记录！',$id);
            }
        }else{
            $rs=new Userpay();
        }
        $oldType=$rs->type;
        $rs->object_id = Request::input('object_id');
        $rs->object_type = Request::input('object_type');
        $rs->time = Request::input('time');
        $rs->type = Request::input('type');
        $rs->money = Request::input('money');
        $rs->score = Request::input('score');
        $rs->reason = Request::input('reason');
        if($rs->save()){
            if($oldType=='预支' and $rs->type!='预支')
            {
                Account::where('fix_salary',-$rs->id)->where('object_id',$rs->object_id)->delete();
            }
            if($rs->type=='预支'){
                $fix_salary=-$rs->id;
                $acc=Account::where('fix_salary',$fix_salary)->where('object_id',$rs->object_id)->first();
                if(!$acc){
                    $acc=new Account();
                }
                $acc->account_time=$rs->time;
                $acc->object_type='预支工资';
                $acc->account_type=-1;
                $acc->object_id=$rs->object_id;
                $acc->object_name=$usrName;
                $acc->handler='自动记录';
                $acc->trade_type='自动记录';
                $acc->trade_account='自动记录';
                $acc->end_time=Carbon::now();
                $acc->remark=$rs->reason;
                $acc->money=$rs->money;
                $acc->fix_salary=$fix_salary;
                $acc->save();
            }
            return $this->myResult(1,'更新成功！',$rs);
        }
        return $this->myResult(0,'操作失败，未知错误！',null);
    }

    /**
     * @api {post} /api/userpays/delete 4.删除指定编号的奖惩信息
     * @apiGroup 人员奖惩
     *@apiHeaderExample 简要说明
     * 1、路由名称 userpays.delete
     * 2、必选参数
     * id，正整数,需要删除的记录编号
     * 注意，此处id默认为0，如果没获取到id将返回错误消息：未找到对应的编号奖惩信息
     */
    public function delete()
    {
        $id = Request::input('id',0);
        $rs=Userpay::find($id);
        if($rs){
            if($rs->account_id != 0) {
                return $this->myResult(0, '已经结算的奖惩信息不允许被删除！', $rs);
            }
            if('预支' == $rs->type)
            {
                Account::where('fix_salary',-$rs->id)->where('object_id',$rs->object_id)->delete();
            }
            $rs->delete();
            return $this->myResult(1,'删除成功！',$id);
        }
        return $this->myResult(0,'未找到对应的编号奖惩信息！',$id);
    }
}
