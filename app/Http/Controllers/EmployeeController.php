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
     * 配合到前端界面也就是只显示员工查询的对应功能和菜单
     *
     * 1、登录后可以显示员工自身信息 2、可以查询自己的任务情况
     * 3、可以查询自己的奖惩和预支情况 4、可以查询自己的工资发放情况
     * 5、其余功能均不可见
     *
     * 路由名称 employees.index
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
     * @api {get} /api/employees/tasks 2.登录员工任务查询
     * @apiGroup 员工登录后模块
     * @apiDescription
     * 暂未实现
     * 路由名称 employees.tasks
     * @apiParam {Integer} account_id 收支编号
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
     * 暂未实现
     * 路由名称 employees.pays
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
     * 暂未实现
     * 路由名称 employees.accounts
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
