<?php

namespace App\Http\Controllers;

use App\Models\Userpay;
use App\Models\User;
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
     * pay_type 奖惩类型,默认为空表示全部,模糊匹配 like %pay_type%
     * user_id  人员ID，默认为空表示全部,精确匹配 =user_id
     * @apiParamExample数据库表结构
    * increments('id')->comment('奖惩编号');
    * integer('user_id')->nullable(false)->comment('人员编号');
    * integer('account_id')->default(0)->comment('结算编号'); //最终发工资时一起合计后的结算编号
    * dateTime('time')->nullable(false)->comment('奖惩时间');
    * string('type',20)->nullable(false)->comment('奖惩类型');
    * decimal('money', 8, 2)->default(0)->comment('奖惩金额');
    * decimal('score', 8, 2)->default(0)->comment('奖惩评分');
    * string('reason')->nullable()->comment('奖惩原因');
     */
    public function index()
    {
        $pageSize = (int)Request::input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:15;

        $user_id=(Request::input('user_id');
        $pay_type=Request::input('pay_type');

        $rs = Userpay::where('id','>',0);
        if($user_id){
            $rs = $rs->where('user_id',user_id);
        }
        if($pay_type){
            $rs = $rs->where('$pay_type','like','%'.$pay_type.'%');
        }
        $rs = $rs->paginate($pageSize);
        return $this->myResult(1,'获取信息成功！',$rs);
    }

    /**
     * @api {get} /api/userpays/{id} 2.获取指定编号的奖惩信息
     * @apiGroup 人员奖惩
     *@apiHeaderExample 简要说明
     * 1、路由名称 userpays.getOne
     * 2、必须传递ID，正整数
     * @apiParamExample 关于奖惩的其他说明
     * 1、account_id为工资结算单的id，不允许修改的
     * 2、返回的数据多一个account_id，为0显示为未结算，大于0显示为已结算
     * 3、此模块的user_id也就是员工编号，且必须是员工表中真实存在的员工编号
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
     * 	user_id 正整数，人员编号，且必须真实存在
     * 	time 日期时间型，奖惩时间
     * 	type 字符串20，奖惩类型
     * 	money 数字类型，两位小数，奖惩金额
     * 	score 数字类型，两位小数，奖惩评分
     * 	reason 字符串，奖惩原因
     */
    public function saveOne($id=0)
    {
        $validator = Validator::make( Request::all(), [
            'user_id' => 'required | integer | min:1',
            'time' => 'required | date',
            'type' => 'required',
            'reason' => 'required',
            'money'=>'required | digits',
            'score'=>'required | digits',
        ]);

        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }

        $ur=User::find(Request::input('user_id'));
        if(!$ur){
            return $this->myResult(0,'更新失败，未找到对应的人员编号！',null);
        }

        if($id>0){
            $rs= Userpay::find($id);
            if(!$rs){
                return $this->myResult(0,'更新失败，未找到该编号的记录！',$id);
            }
        }else{
            $rs=new Userpay();
        }

        $rs->user_id = Request::input('user_id');
        $rs->time = Request::input('time');
        $rs->type = Request::input('type');
        $rs->money = Request::input('money');
        $rs->score = Request::input('score');
        $rs->reason = Request::input('reason');
        if($rs->save()){
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
            if($rs->account_id>0) {
                return $this->myResult(0, '已经结算的奖惩信息不允许被删除！', $rs);
            }
            $rs->delete();
            return $this->myResult(1,'删除成功！',$id);
        }
        return $this->myResult(0,'未找到对应的编号奖惩信息！',$id);
    }
}
