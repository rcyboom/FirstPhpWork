<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskCollection;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;

class TaskController extends Controller
{
    use Result;

    /**
     * @api {get} /api/tasks/index 1.任务列表
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.index
     * 2、可选参数
     * pageSize 分页数量，默认为15
     * customer_name 客户名称,默认为空,模糊匹配
     * task_title    任务名称，默认为空,模糊匹配
     * linkman       联系人，默认为空,模糊匹配
     * @apiParamExample 数据库表结构
     * increments('id')->comment('任务编号');
    * dateTime('check_time')->nullable(false)->default(now())->comment('登记时间');
    * integer('customer_id')->nullable(false)->comment('客户编号');
    * string('title', 30)->nullable(false)->comment('任务名称');
    * string('type', 20)->default('默认类型')->comment('类型');
    * string('state', 20)->default('已登记')->comment('状态');
    * string('linkman',20)->nullable()->comment('联系人');
    * string('phone', 20)->nullable()->comment('联系电话');
    * string('station', 60)->nullable()->comment('工作地点');
    * dateTime('start_time')->nullable()->default(now())->comment('开始时间');
    * dateTime('end_time')->nullable()->comment('结束时间');
    * decimal('work_hours', 8, 2)->default(1.0)->comment('任务工时');
    * decimal('equipment_cost', 8, 2)->default(0)->comment('设备费用');
    * decimal('other_cost', 8, 2)->default(0)->comment('其他费用');
    * decimal('receivables', 8, 2)->default(0)->comment('结算金额');
    * decimal('tax', 8, 2)->default(0)->comment('税费');
    * integer('account_id')->default(0)->comment('结算编号');
    * string('remark')->nullable()->comment('任务备注');
     */
    public function index()
    {
        $pageSize = (int)Request::input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:15;

        $tasks = Task::LinkMan()->Title()->CustomerName()->paginate($pageSize);
        return new TaskCollection($tasks);
    }


    /**
     * @api {post} /api/tasks/create 2.新增任务
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.create
     * 2、必选参数：
     * check_time 登记时间  datetime
     * customer_id 客户编号 integer
     * title 任务名称  string 30
     * 3、可选参数
     * type 任务类型 string 20  默认值"默认类型"
     * linkman 联系人 string 20
     * phone 联系电话 string 20
     * station 工作地点 string 60
     * start_time 开始时间 datetime
     * remark 任务备注 string
     */
    public function create()
    {
        $validator = Validator::make( Request::all(), [
            'check_time' => 'required|date',
            'customer_id' => 'required|integer|min:0',
            'title' => 'required',
            'start_time'=>'date'
        ]);

        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }


        $rs=new Task();
        $rs->check_time = Request::input('check_time');
        $rs->customer_id = Request::input('customer_id');
        $rs->title = Request::input('title');
        $rs->type = Request::input('type');
        $rs->linkman = Request::input('linkman');
        $rs->phone = Request::input('phone');
        $rs->station = Request::input('station');
        $rs->start_time = Request::input('start_time');
        $rs->remark = Request::input('remark');

        if($rs->save()){
            return $this->myResult(1,'更新成功！',$rs);
        }
        return $this->myResult(0,'操作失败，未知错误！',null);
    }

        /**
         * @api {post} /api/tasks/update 3.更新任务
         * @apiGroup 任务管理
         *@apiHeaderExample 简要说明
         * 1、路由名称 tasks.update
         * 2、还没写呢,这里主要还涉及更新一些任务的状态这种单一属性的操作
         */

    /**
     * @api {post} /api/tasks/allotCar 4.新增或更新指派出勤车辆
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.allotCar
     * 2、还没写呢，这里主要是指派车辆出勤，同时要对出勤记录进行编辑或者更新单一状态比如手工
     */

    /**
     * @api {post} /api/tasks/allotMan 5.新增或更新指派出勤人员
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.allotMan
     * 2、还没写呢，这里主要是对人员的出勤、手工、考核，不知道是单独写api还是集中到这里一起合适
     * 一会我把流程图发给你你看看
     */


}
