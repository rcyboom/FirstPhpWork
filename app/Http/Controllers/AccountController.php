<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Car;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;

class AccountController extends Controller
{
    use Result;
    /**
     * @apiGroup 用户管理
     * @api {get} /api/testapi 0.测试api
     * @apiDescription
     * 测试api工作情况，直接返回用户输入
     * @apiParam {Number} [id=1] 参数说明
     * @apiHeaderExample 简要说明
     * 1、服务器工作正常的时候，用浏览器等访问可返回传入的任何信息
     */
    function test()
    {
        $mcv=Request::input('mcv');
        if(!empty($mcv))
            return $this->myResult(1,'获取信息成功！',['mcv'=>$mcv]);
        else
            return $this->myResult(1,'获取信息成功！',1234);
    }

    /**
     * @api {get} /api/accounts/index 1.财务收支列表
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.index
     * @apiParam {String} start_time 开始日期，格式：2018-01-01
     * @apiParam {String} end_time 截至日期
     * @apiParam {Integer} [account_type] 收支类型，精准匹配，范围：-1，0，1，分别表示支出、全部、收入，默认值0
     * @apiParam {String} [object_type] 收支对象类型，精准匹配，范围：员工结算、车辆结算、客户结算及自定义字符串，默认值空表示全部
     * @apiParam {Integer} [object_id] 收支对象ID，精准匹配，只有在object_type为员工结算或车辆结算或者客户结算时有意义，默认值空表示全部
     * @apiParam {String} [trade_type] 交易类型，精准匹配，默认值空，表示全部
     * @apiParam {String} [object_name] 收支对象名称，模糊匹配，默认值空表示全部
     * @apiParam {String} [handler] 经办人，模糊匹配，默认值空表示全部
     * @apiParam {String} [remark] 备注，模糊匹配，默认值空，表示全部
     * @apiParam {Number} [pageSize] 分页大小，默认值15
     */
    public function index()
    {
        //开始输入校验
        $validator = Validator::make( Request::all(), [
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);
        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }
        //获取参数
        $pageSize = (int)Request::input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:15;
        $start_time=Request::input('start_time');
        $end_time=Request::input('end_time');
        $account_type=Request::input('account_type',0);
        $object_type=Request::input('object_type');
        $object_id=Request::input('object_id');
        $trade_type=Request::input('trade_type');
        $object_name=Request::input('object_name');
        $handler=Request::input('handler');
        $remark=Request::input('remark');


        //构造查询
        $rs = Account::where('account_time','>=',$start_time);
        $rs = $rs->where('account_time','<=',$end_time);
        if($account_type>0){
            $rs = $rs->where('money','>',0);
        }elseif ($account_type<0){
            $rs = $rs->where('money','<',0);
        }
        if($object_type){
            $rs = $rs->where('object_type',$object_type);
        }
        if($object_id){
            $rs = $rs->where('object_id',$object_id);
        }
        if($trade_type){
            $rs = $rs->where('trade_type',$trade_type);
        }
        if($object_name){
            $rs = $rs->where('object_name','like','%'.$object_name.'%');
        }
        if($handler){
            $rs = $rs->where('handler','like','%'.$handler.'%');
        }
        if($remark){
            $rs = $rs->where('remark','like','%'.$remark.'%');
        }
        //返回数据
        $rs = $rs->paginate($pageSize);
        return $this->myResult(1,'获取信息成功！',$rs);
    }

    /**
     * @api {get} /api/accounts/getone 2.获取某条收支记录
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.getone
     * @apiParam {Integer} account_id 收支编号
     */
    public function getone()
    {
        $account_id=Request::input('account_id',0);
        $rs=Account::find($account_id);
        if($rs){
            return $this->myResult(1,'获取信息成功！',$rs);
        }
        return $this->myResult(0,'未找到对应的编号信息！',null);
    }

    /**
     * @api {post} /api/accounts/saveone 3.新增或更新一条手动收支信息
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.saveone
     *
     * 注意，当object_type为客户结算、员工结算、车辆结算时，object_type不允许更改且该记录不允许删除
     * @apiParam {Integer} id 收支编号，整数，小于1表示新增
     * @apiParam {String} account_time 收支时间,日期，格式2018-01-01
     * @apiParam {String} object_type 收支对象，可选项：客户结算、员工结算、车辆结算、自定义字符串
     * @apiParam {Integer} object_id 收支对象ID，当object_type为客户结算、员工结算、车辆结算的时候，表示客户、员工、车辆的ID，其余必须为0
     * @apiParam {String} object_name 对象名称，当object_type为客户结算、员工结算、车辆结算的时候，表示客户、员工、车辆的名称，其余为自定义字符串
     * @apiParam {String} handler 经办人，默认登录用户
     * @apiParam {Number} money 金额，2位小数
     * @apiParam {String} trade_type 交易账户类型，如支付宝、现金等
     * @apiParam {String} [trade_account] 交易账户号
     * @apiParam {String} [remark] 备注
     */
    public function saveone()
    {
        $validator = Validator::make( Request::all(), [
            'id' => 'required | integer | min:0',
            'account_time' => 'required | date',
            'object_type' => 'required',
            'object_id' => 'required | integer | min:0',
            'object_name' => 'required',
            'handler' => 'required',
            'trade_type' => 'required',
            'money'=>'required | digits'
        ]);

        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }
        $id = Request::input('id');
        if($id>0){
            $rs= Account::find($id);
            if(!$rs){
                return $this->myResult(0,'更新失败，未找到该编号的记录！',$id);
            }
        }else{
            $rs=new Account();
        }

        $rs->account_time = Request::input('account_time');
        $rs->object_type = Request::input('object_type');
        $rs->object_id = Request::input('object_id');
        $rs->object_name = Request::input('object_name');
        $rs->handler = Request::input('handler');
        $rs->money = Request::input('money');
        $rs->trade_type = Request::input('trade_type');
        $rs->trade_account = Request::input('trade_account');
        $rs->remark = Request::input('remark');
        if($rs->save()){
            return $this->myResult(1,'更新成功！',$rs);
        }
        return $this->myResult(0,'操作失败，未知错误！',null);
    }


    /**
     * @api {post} /api/accounts/delete 4.删除指定的手动收支信息
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.delete
     * 注意，此处id默认为0，如果没获取到id将返回错误消息：未找到对应编号0的收支信息
     * 只能删除收支对象object_type不为 客户结算、员工结算、车辆结算 的信息
     * @apiParam {Integer} id 收支编号，正整数
     */
    public function delete()
    {
        $id = Request::input('id',0);
        $rs=Account::find($id);
        if($rs){
            if(in_array($rs->object_type,['客户结算','员工结算','车辆结算'])){
                return $this->myResult(0,'不能删除收支对象为'.$rs->object_type.'的记录!',$rs);
            }
            $rs->delete();
            return $this->myResult(1,'删除成功！',['id'=>$id]);
        }
        return $this->myResult(0,'未找到对应的编号的收支信息！',['id'=>$id]);
    }

    /**
     * @api {post} /api/accounts/accounttask 5.与客户结算某个任务
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.accounttask
     * 注意，此接口只用于与客户结算某个任务，结算后不可删除结算记录，任务结算状态不可再更改
     * 结算金额自动为该任务的结算金额，如果以后修改任务后，需手动修改对应的收支记录
     * @apiParam {String} account_time 结算日期
     * @apiParam {Integer} task_id 任务ID编号
     * @apiParam {String} handler 经办人，默认登录用户
     * @apiParam {String} trade_type 交易类型，如：现金、支付宝、微信、银行卡、对公账户等
     * @apiParam {String} [trade_account] 交易账户号
     * @apiParam {String} [remark] 备注
     */
    public function accounttask()
    {
        $validator = Validator::make( Request::all(), [
            'account_time' => 'required | date',
            'task_id' => 'required | integer | min:1',
            'handler' => 'required',
            'trade_type' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        };

        //查找该任务是否存在
        $task=Task::find(Request::input('task_id'));
        if($task){
            if($task->account_id>0){
                return $this->myResult(0,'该任务已经结算！',['结算编号'=>$task->account_id]);
            }
            $acc=new Account();
            $acc->account_time=Request::input('account_time');
            $acc->object_type='客户结算';
            $acc->object_id=$task->id;
            $acc->object_name=$task->customer_name;
            $acc->handler=Request::input('handler');
            $acc->trade_type=Request::input('trade_type');
            $acc->trade_account=Request::input('trade_account');
            $acc->remark=Request::input('remark');
            $acc->money=$task->receivables;
            $acc->save();
            $task->account_id=$acc->id;
            $task->save();
            return $this->myResult(1,'结算成功，对应的收支记录为:'.$acc->id,$acc->id);
        }
        return $this->myResult(0,'未找到对应编号的任务信息！',Request::input('task_id'));
    }

    /**
     * @api {post} /api/accounts/accountcar 6.与车辆结算某个时间点之前的工资
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.accountcar
     * 注意，此接口只用于与车辆结算某个时间点之前创建的任务的工资，结算后不可删除结算记录，所有参与结算的任务和奖惩记录的结算状态均不可再更改
     * 结算金额自动为该时间点之前创建的出勤任务及奖惩记录的合计金额，如果以后修改任务情况和奖惩记录后，需手动修改对应的收支记录
     * 时间节点以出勤任务创建时间为准
     * @apiParam {String} end_time 截至日期
     * @apiParam {String} account_time 结算日期
     * @apiParam {Integer} car_id 车辆ID编号
     * @apiParam {String} handler 经办人，默认登录用户
     * @apiParam {String} trade_type 交易类型，如：现金、支付宝、微信、银行卡、对公账户等
     * @apiParam {String} [trade_account] 交易账户号
     * @apiParam {String} [remark] 备注
     */
    public function accountcar()
    {
        $validator = Validator::make( Request::all(), [
            'end_time' => 'required | date',
            'account_time' => 'required | date',
            'car_id' => 'required | integer | min:1',
            'handler' => 'required',
            'trade_type' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        };

        $car=Car::find(Request::input('car_id'));
        if($car){
            $end_time=Request::input('end_time');
            $account_time=Request::input('account_time');

            //查找所有时间段内已经完成但是还未结算的任务并予以结算
            $affected_task = DB::update('update cartasks set account_id = -1 where account_id = 0  AND car_id = ?  and created_at<=?',
                [$car->id,$end_time]);
            $affected_pay = DB::update('update userpays set account_id = -1 where account_id = 0  AND object_type=? AND object_id = ?  and created_at<=?',
                ['车辆',$car->id,$end_time]);
            if( ($affected_task+$affected_pay) ==0){
                return $this->myResult(0,'未找到指定车辆的待结算信息！',null);
            }
            $taskmoney = DB::select('select COALESCE(SUM(rent_cost+oil_cost+toll_cost+park_cost+award_salary),0) as cc from cartasks  where account_id = -1');
            $usermoney = DB::select('select COALESCE(SUM(money),0) as cc from userpays where account_id = -1');

            $acc=new Account();
            $acc->account_time=$account_time;
            $acc->object_type='车辆结算';
            $acc->object_id=$car->id;
            $acc->object_name=$car->car_number;
            $acc->handler=Request::input('handler');
            $acc->trade_type=Request::input('trade_type');
            $acc->trade_account=Request::input('trade_account');
            $acc->remark=Request::input('remark');
            $acc->money=$taskmoney[0]->cc+$usermoney[0]->cc;
            $acc->save();
            DB::update('update cartasks set account_id = ? where account_id = -1',[$acc->id]);
            DB::update('update userpays set account_id = ? where account_id = -1',[$acc->id]);

            return $this->myResult(1,'结算成功，对应的收支记录为:'.$acc->id,$acc);
        }
        return $this->myResult(0,'未找到对应编号的车辆信息！',Request::input('car_id'));
    }

    /**
     * @api {post} /api/accounts/accountuser 7.与员工结算某个时间点之前的工资
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.accountuser
     * 注意，此接口只用于与员工结算某个时间点之前的工资，结算后不可删除结算记录，所有参与结算的任务和奖惩记录的结算状态均不可再更改
     * 结算金额自动为该时间点之前的出勤任务及奖惩记录的合计金额，如果以后修改任务情况和奖惩记录后，需手动修改对应的收支记录
     * 时间节点以出勤任务创建时间为准
     * @apiParam {String} end_time 截至日期
     * @apiParam {String} account_time 结算日期
     * @apiParam {Integer} user_id 人员ID编号
     * @apiParam {String} handler 经办人，默认登录用户
     * @apiParam {String} trade_type 交易类型，如：现金、支付宝、微信、银行卡、对公账户等
     * @apiParam {String} [trade_account] 交易账户号
     * @apiParam {String} [remark] 备注
     */
    public function accountuser()
    {
        $validator = Validator::make( Request::all(), [
            'end_time' => 'required | date',
            'account_time' => 'required | date',
            'user_id' => 'required | integer | min:1',
            'handler' => 'required',
            'trade_type' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        };

        $usr=User::find(Request::input('user_id'));
        if($usr){
            $end_time=Request::input('end_time');
            $account_time=Request::input('account_time');

            //查找所有时间段内已经完成但是还未结算的任务并予以结算
            $affected_task = DB::update('update usertasks set account_id = -2 where account_id = 0  AND user_id = ? and created_at<=?',
                [$usr->id,$end_time]);
            $affected_pay = DB::update('update userpays set account_id = -2 where account_id = 0  AND object_type=? AND object_id = ? and created_at<=?',
                ['员工',$usr->id,$end_time]);
            if( ($affected_task+$affected_pay) ==0){
                return $this->myResult(0,'未找到指定员工的待结算信息！',null);
            }
            $taskmoney = DB::select('select COALESCE(SUM(work_salary+extra_salary+award_salary),0) as cc from usertasks  where account_id = -2');
            $usermoney = DB::select('select COALESCE(SUM(money),0) as cc from userpays where account_id = -2');

            $acc=new Account();
            $acc->account_time=$account_time;
            $acc->object_type='员工结算';
            $acc->object_id=$usr->id;
            $acc->object_name=$usr->name;
            $acc->handler=Request::input('handler');
            $acc->trade_type=Request::input('trade_type');
            $acc->trade_account=Request::input('trade_account');
            $acc->remark=Request::input('remark');
            $acc->money=$taskmoney[0]->cc+$usermoney[0]->cc;
            $acc->save();
            DB::update('update usertasks set account_id = ? where account_id = -2',[$acc->id]);
            DB::update('update userpays set account_id = ? where account_id = -2',[$acc->id]);

            return $this->myResult(1,'结算成功，对应的收支记录为:'.$acc->id,$acc);
        }
        return $this->myResult(0,'未找到对应编号的人员信息！',Request::input('user_id'));
    }

    /**
     * @api {post} /api/accounts/autoUpdateAccount 8.对于已经结算的：任务、车辆或人员出勤、奖惩、预支记录后，
     * 自动更新该记录所对应的财务收支记录的汇总金额。
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.autoUpdateAccount
     * 注意，此接口只用于手动修改已经结算的任务记录、人员或者车辆出勤记录、人员或者车辆的奖惩预支等记录的金额后，用于再次合计
     * 其对应的财务收支记录的汇总金额，以便明细和汇总相符。
     *
     * 该功能暂未实现
     * @apiParam {Integer} account_ id 对应的结算记录id
     * @apiParam {Integer} object_id 对应的客户、车辆、人员的id
     */
    public function autoUpdateAccount()
    {

    }

    /**
     * @api {post} /api/accounts/autoCheckAccount 9.校验某个财务记录中对于已经结算的：任务、车辆或人员出勤、奖惩、预支记录中明细汇总金额
     * 与财务收支金额是否相等
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.autoCheckAccount
     * 注意，此接口只用于对于已经结算的任务记录、人员或者车辆出勤记录、人员或者车辆的奖惩预支等记录的金额后，用于再次合计
     * 其对应的财务收支记录的汇总金额，校验是否相符，返回值为重新校验后的汇总金额。
     *
     * 该功能暂未实现
     * @apiParam {Integer} account_ id 对应的结算记录id
     */
    public function autoCheckAccount()
    {

    }
}
