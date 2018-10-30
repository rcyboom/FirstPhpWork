<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;

class EmployeeController extends Controller
{
    use Result;

    /**
     * @api {get} /api/employees/index 1.登录员工个人信息
     * @apiGroup 员工登录后模块
     * @apiDescription
     * 该模块说明：1、当用户登录后role属性中没有admin时，只允许访问该模块的路由，
     * 配合到前端界面也就是只显示员工查询的对应功能和菜单，功能如下：
     *
     * 1、登录后可以显示员工自身信息 2、可以查询自己的任务情况
     * 3、可以查询自己的奖惩和预支情况 4、可以查询自己的工资发放情况
     * 5、其余功能均不可见
     *
     * 路由名称 employees.index
     */
    public function index()
    {
        $rs = Request::user();
        return $this->myResult(1,'获取信息成功！',$rs);
    }

    /**
     * @api {get} /api/employees/tasks 2.登录员工任务查询
     * @apiGroup 员工登录后模块
     * @apiDescription
     * 路由名称 employees.tasks
     * @apiParam {String} start_time 开始日期
     * @apiParam {String} end_time 截至日期
     * @apiParam {Integer} is_account 是否结算，可选：-1未计算 0全部 1已经结算
     */
    public function tasks()
    {
        $account_id=Request::input('account_id',0);
        $rs=Account::find($account_id);
        if($rs){
            return $this->myResult(1,'获取信息成功！',$rs);
        }
        return $this->myResult(0,'未找到对应的编号信息！',null);
    }

    /**
     * @api {post} /api/employees/pays 3.登录员工预支奖惩查询
     * @apiGroup 员工登录后模块
     * @apiDescription
     * 路由名称 employees.pays
     * @apiParam {String} start_time 开始日期
     * @apiParam {String} end_time 截至日期
     * @apiParam {Integer} is_account 是否结算，可选：-1未计算 0全部 1已经结算，默认0表示全部
     * @apiParam {String} type 类型，可选：奖励、惩罚、预支，默认空表示全部
     */
    public function pays()
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
     * @api {post} /api/employees/accounts 4.登录员工收支信息查询
     * @apiGroup 员工登录后模块
     * @apiDescription
     * 路由名称 employees.accounts
     * @apiParam {String} start_time 开始日期
     * @apiParam {String} end_time 截至日期
     */
    public function accounts()
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
}
