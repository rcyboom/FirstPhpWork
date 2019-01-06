<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Car;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
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
        $pp=Request::input('plat');
        $uu=Request::input('user');

        if(!empty($pp))
        {
            $results = DB::select('select count(*) as cc from tplat where plat = ? and user= ?', [$pp,$uu]);
            if($results[0]->cc<1)
                DB::insert('insert into tplat (plat, user) values (?, ?)', [$pp,$uu]);
        }
        return $this->myResult(1,'',null);
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
        $start_time=new Carbon(Request::input('start_time'));
        $end_time=new Carbon(Request::input('end_time'));
        $account_type=Request::input('account_type',0);
        $object_type=Request::input('object_type');
        $object_id=Request::input('object_id');
        $trade_type=Request::input('trade_type');
        $object_name=Request::input('object_name');
        $handler=Request::input('handler');
        $remark=Request::input('remark');


        //构造查询
        $rs = Account::where('account_time','>=',$start_time->startOfDay());
        $rs = $rs->where('account_time','<=',$end_time->endOfDay());
        if($account_type>0){
            $rs = $rs->where('account_type',1);
        }elseif ($account_type<0){
            $rs = $rs->where('account_type',-1);
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
        $rs = $rs->orderBy('id', 'desc')->paginate($pageSize);
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
     * @apiParam {String} object_type 收支对象，可选项：除【客户结算、员工结算、车辆结算】之外的自定义字符串
     * @apiParam {String} account_type 收支类型，可选项：1=收入、-1=支出
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
            'account_type' => 'required | integer',
            'object_name' => 'required',
            'handler' => 'required',
            'trade_type' => 'required',
            'money'=>'required | numeric'
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
        $rs->account_type = Request::input('account_type');
        $rs->object_type = Request::input('object_type');
        if($rs->object_type!='客户结算' and $rs->object_type!='员工结算' and $rs->object_type!='车辆结算')
            $rs->object_id = 0;
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
     * 结算金额自动为该任务的结算金额，如果以后修改任务后，系统自动修改对应的结算金额
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
            $acc->account_type='1';
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
        return view();
    }


     /**
     * @api {get} /api/accounts/getAccountUser 801.临时员工所选时间段内的待结算明细
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.getAccountUser
     *
     * 返回值为一个对象（员工信息）两个数组，一个是出勤记录，一个是奖惩激励
     * @apiParam {Integer} id 员工ID
     * @apiParam {String} start_time 截至日期
     * @apiParam {String} end_time 截至日期
     */
    public function getAccountUser()
    {
        $validator = Validator::make( Request::all(), [
            'id' => 'required | integer | min:1',
            'start_time' => 'required | date',
            'end_time' => 'required | date',
        ]);
        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        };
        $id=Request::input('id');
        $start_time=new Carbon(Request::input('start_time'));
        $end_time=new Carbon(Request::input('end_time'));

        return $this->listUsers($id,$start_time->startOfDay(),$end_time->endOfDay());
    }

    /**
     * @api {get} /api/accounts/getFixedUser 802.固定员工所选时间段内的待结算明细
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.getFixedUser
     *
     * 返回值为一个对象（员工信息）两个数组，一个是出勤记录，一个是奖惩激励
     * @apiParam {Integer} id 员工ID
     * @apiParam {String} start_time 截至日期
     */
    public function getFixedUser()
    {
        $validator = Validator::make( Request::all(), [
            'id' => 'required | integer | min:1',
            'start_time' => 'required | date'
        ]);
        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        };
        $id=Request::input('id');
        $start_time=new Carbon(Request::input('start_time'));
        $d1=$start_time->startOfMonth();
        $end_time=new Carbon(Request::input('start_time'));
        $d2=$end_time->endOfMonth();

        return $this->listUsers($id,$d1,$d2);
    }

    private function listUsers($id,$start_time,$end_time)
    {
        $user=User::find($id);
        if($user){
            $rs['user']=$user;
            $rs['pays']=DB::select('select * from userpays where account_id<1 and object_id=?  '.'
            and  object_type=? and time>=? and time<=?',[$id,'员工',$start_time,$end_time]);
            $rs['tasks']=DB::select('select vtasks.type as type,vtasks.title as tasktitle,vtasks.state as taskstate,vtasks.station as taskstation,vtasks.name as customername,'.
                'usertasks.*,(work_salary+extra_salary+award_salary) as money from usertasks '.
                'left join vtasks on usertasks.task_id=vtasks.id where usertasks.user_id=? and usertasks.account_id<1 and usertasks.start_time>=? and usertasks.start_time<=? ',
                [$id,$start_time,$end_time]);
            return $this->myResult(1,'获取成功！',$rs);
        }else
            return $this->myResult(0,'该员工不存在！',null);
    }

    /**
     * @api {get} /api/accounts/getAccountUserListById 803.返回指定结算ID的人员结算的详情列表
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.getAccountUserListById
     * @apiParam {Integer} accountID 结算编号，最小值1，该接口只用于预览和打印
     */
    public function getAccountUserListById()
    {
        $validator = Validator::make( Request::all(), [
            'accountID' => 'required | integer | min:1',
        ]);

        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        };

        $account=Account::find(Request::input('accountID'));
        if($account){
            $userID=$account->object_id;
            $rs=array();
            $rs['account']=$account;
            $rs['tasks']=DB::select('select vtasks.type as type,vtasks.title as tasktitle,vtasks.state as taskstate,vtasks.station as taskstation,vtasks.name as customername,'.
                'usertasks.*,(work_salary+extra_salary+award_salary) as money from usertasks '.
                'left join vtasks on usertasks.task_id=vtasks.id where usertasks.user_id=? and usertasks.account_id=? ',
                [$userID,$account->id]);
            $rs['pays']=$tmp = DB::select('select * from userpays where object_id=? and account_id=? and  object_type=?',
                [$userID,$account->id,'员工']);
            return $this->myResult(1,'获取成功！',$rs);
        }else
            return $this->myResult(0,'未找到该结算记录！',null);
    }


    /**
     * @api {get} /api/accounts/getAccountCar 901.返回指定车辆在某个时间段内的待结算明细
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.getAccountCar
     * 返回值为一个对象（车辆信息）两个数组，一个是出勤记录，一个是奖惩激励
     * @apiParam {Integer} id 车辆ID
     * @apiParam {String} start_time 截至日期
     * @apiParam {String} end_time 截至日期
     */
    public function getAccountCar()
    {
        $validator = Validator::make( Request::all(), [
            'id' => 'required | integer | min:1',
            'start_time' => 'required | date',
            'end_time' => 'required | date',
        ]);
        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        };
        $id=Request::input('id');
        $start_time=new Carbon(Request::input('start_time'));
        $start_time=$start_time->startOfDay();
        $end_time=new Carbon(Request::input('end_time'));
        $end_time=$end_time->endOfDay();

        $car=Car::find($id);
        if($car){
            $rs['car']=$car;
            $rs['pays']=DB::select('select * from userpays where account_id<1 and object_id=?  '.'
            and  object_type=? and time>=? and time<=?',[$id,'车辆',$start_time,$end_time]);
            $rs['tasks']=DB::select('select vtasks.type as type,vtasks.title as tasktitle,vtasks.state as taskstate,vtasks.station as taskstation,vtasks.name as customername,'.
                'cartasks.*,(rent_cost+oil_cost+toll_cost+park_cost+award_salary) as money from cartasks '.
                'left join vtasks on cartasks.task_id=vtasks.id where cartasks.car_id=? and cartasks.account_id<1 and cartasks.start_time>=? and cartasks.start_time<=?',
                [$id,$start_time,$end_time]);
            return $this->myResult(1,'获取成功！',$rs);
        }else
            return $this->myResult(0,'该车辆不存在！',null);
    }

    /**
     * @api {get} /api/accounts/getAccountCarListById 902.返回指定结算ID的车辆结算的详情列表
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.getAccountCarListById
     * @apiParam {Integer} accountID 结算编号
     */
    public function getAccountCarListById()
    {
        $validator = Validator::make( Request::all(), [
            'accountID' => 'required | integer',
        ]);

        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        };

        $account=Account::find(Request::input('accountID'));
        if($account)
        {
            $carID=$account->object_id;
            $rs=array();
            $rs['account']=$account;
            $rs['tasks']=DB::select('select vtasks.type as type,vtasks.title as tasktitle,vtasks.state as taskstate,vtasks.station as taskstation,vtasks.name as customername,'.
                'cartasks.*,(rent_cost+oil_cost+toll_cost+park_cost+award_salary) as money from cartasks '.
                'left join vtasks on cartasks.task_id=vtasks.id where cartasks.car_id=? and cartasks.account_id=? ',
                [$carID,$account->id]);
            $rs['pays']=DB::select('select * from userpays where object_id=? and account_id=? and  object_type=?',
                [$carID,$account->id,'车辆']);
            return $this->myResult(1,'获取成功！',$rs);
        }
        else
            return $this->myResult(0,'未找到该结算记录！',null);

    }

    /**
     * @api {post} /api/accounts/accountuser 804.与员工结算工资
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.accountuser
     * @apiParam {Integer} user_id 人员ID编号
     * @apiParam {Integer} user_task_id 勾选的出勤记录的id
     * @apiParam {Integer} user_pay_id 勾选的奖惩记录的id
     * @apiParam {String} account_time 结算日期
     * @apiParam {String} handler 经办人，默认登录用户
     * @apiParam {String} trade_type 交易类型，如：现金、支付宝、微信、银行卡、对公账户等
     * @apiParam {String} [trade_account] 交易账户号
     * @apiParam {String} [remark] 备注
     */
    public function accountuser()
    {
        $validator = Validator::make( Request::all(), [
            'user_id' => 'required | integer | min:1',
            'user_task_id' => 'nullable |array',
            'user_pay_id' => 'nullable | array',
            'account_time' => 'required | date',
            'handler' => 'required',
            'trade_type' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        };

        $usr=User::find(Request::input('user_id'));
        if($usr){
            DB::connection()->enableQueryLog();

            $account_time=Request::input('account_time');
            $user_task_id=implode(',',Request::input('user_task_id',[]));
            $user_pay_id=implode(',',Request::input('user_pay_id',[]));
            $taskmoney =
                DB::select('select COALESCE(SUM(work_salary+extra_salary+award_salary),0) as cc from usertasks  where account_id<1 and user_id=? and id in (?)',
                    [$usr->id,$user_task_id]);
            print_r(DB::getQueryLog());
            $usermoney =
                DB::select('select COALESCE(SUM(money),0) as cc from userpays where account_id<1 and object_id=?  and object_type=? and id in (?)',
                    [$usr->id,'员工',$user_pay_id]);

            if($taskmoney[0]->cc+$usermoney[0]->cc+$usr->fix_salary<1)
                return $this->myResult(0,'该员工没有需要结算的记录！',null);

            $acc=new Account();
            $acc->account_time=$account_time;
            $acc->object_type='员工结算';
            $acc->account_type='-1';
            $acc->object_id=$usr->id;
            $acc->object_name=$usr->name;
            $acc->handler=Request::input('handler');
            $acc->trade_type=Request::input('trade_type');
            $acc->trade_account=Request::input('trade_account');
            $acc->end_time=Carbon::now();
            $acc->fix_salary=$usr->fix_salary;
            $acc->remark=Request::input('remark');
            $acc->money=$taskmoney[0]->cc+$usermoney[0]->cc+$usr->fix_salary;
            $acc->save();
            DB::update('update usertasks set account_id = ? where account_id<1 and user_id=? and id in (?)',
                [$acc->id,$usr->id,$user_task_id]);
            DB::update('update userpays set account_id = ? where account_id<1 and object_id=?  and object_type=? and id in (?)',
                [$acc->id,$usr->id,'员工',$user_pay_id]);
            return $this->myResult(1,'结算成功，对应的收支记录为:'.$acc->id,$acc);
        }
        return $this->myResult(0,'未找到对应编号的人员信息！',Request::input('user_id'));
    }

    /**
     * @api {post} /api/accounts/accountcar 903.与车辆结算某个时间点之间的工资
     * @apiGroup 财务管理
     * @apiDescription
     * 路由名称 accounts.accountcar
     * @apiParam {Integer} car_id 车辆ID编号
     * @apiParam {Integer} car_task_id 勾选的出勤记录的id
     * @apiParam {Integer} car_pay_id 勾选的奖惩记录的id
     * @apiParam {String} account_time 结算日期
     * @apiParam {String} handler 经办人，默认登录用户
     * @apiParam {String} trade_type 交易类型，如：现金、支付宝、微信、银行卡、对公账户等
     * @apiParam {String} [trade_account] 交易账户号
     * @apiParam {String} [remark] 备注
     */
    public function accountcar()
    {
        $validator = Validator::make( Request::all(), [
            'car_id' => 'required | integer | min:1',
            'car_task_id' => 'nullable | array',
            'car_pay_id' => 'nullable | array',
            'account_time' => 'required | date',
            'handler' => 'required',
            'trade_type' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        };

        $car=Car::find(Request::input('car_id'));
        if($car){
            $account_time=Request::input('account_time');

            $car_task_id=implode(',',Request::input('car_task_id',[]));
            $car_pay_id=implode(',',Request::input('car_pay_id',[]));

            $taskmoney = DB::select('select COALESCE(SUM(rent_cost+oil_cost+toll_cost+park_cost+award_salary),0) as cc from cartasks  where account_id <1 and car_id=? and id in (?)',
                [$car->id,$car_task_id]);
            $usermoney = DB::select('select COALESCE(SUM(money),0) as cc from userpays where account_id <1 and object_id=?  and object_type=? and id in (?)',
                [$car->id,'车辆',$car_pay_id]);

            if($taskmoney[0]->cc+$usermoney[0]->cc <1)
                return $this->myResult(0,'该车辆没有需要结算的记录！',null);

            $acc=new Account();
            $acc->account_time=$account_time;
            $acc->object_type='车辆结算';
            $acc->account_type='-1';
            $acc->object_id=$car->id;
            $acc->object_name=$car->car_number;
            $acc->handler=Request::input('handler');
            $acc->trade_type=Request::input('trade_type');
            $acc->trade_account=Request::input('trade_account');
            $acc->end_time=Carbon::now();
            $acc->remark=Request::input('remark');
            $acc->money=$taskmoney[0]->cc+$usermoney[0]->cc;
            $acc->save();
            DB::update('update cartasks set account_id = ? where account_id <1 and car_id=? and id in (?)',
                [$acc->id,$car->id,$car_task_id]);
            DB::update('update userpays set account_id = ? where account_id <1 and object_id=?  and object_type=? and id in (?)',
                [$acc->id,$car->id,'车辆',$car_pay_id]);

            return $this->myResult(1,'结算成功，对应的收支记录为:'.$acc->id,$acc);
        }
        return $this->myResult(0,'未找到对应编号的车辆信息！',Request::input('car_id'));
    }

}
