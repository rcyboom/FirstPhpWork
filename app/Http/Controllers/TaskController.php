<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskCollection;
use App\Models\Cartask;
use App\Models\Task;
use App\Models\Userpay;
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
            'start_time'=>'nullable|date'
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
         * 2、必填参数：
         * ID 任务编号 正整数
         * 3、可选参数
         * start_time 开始时间 日期时间型
         * end_time 结束时间 日期时间型
         * work_hours 工时 2位小数
         * equipment_cost 设备费用 2位小数
         * other_cost 其他费用 2位小数
         * receivables 结算费用 2位小数
         * tax 税费 2位小数
         * type 任务类型 字符串
         * linkman 联系人 字符串
         * phone 联系电话 字符串
         * station 工作地点 字符串
         * remark 任务备注 字符串
         * 4、特别说明：
         * check_time 登记时间,customer_id客户ID,title任务标题
         */
    public function update()
    {
        $rs=Task::find(Request::input('id',0));
        if(! $rs)
        {
            return $this->myResult(0,'未找到对应的任务记录！',null);
        }

        $validator = Validator::make( Request::all(), [
            'start_time'=>'nullable|date',
            'end_time'=>'nullable|date',
            'work_hours'=>'nullable|numeric | min:0',
            'equipment_cost'=>'nullable|numeric | min:0',
            'other_cost'=>'nullable|numeric | min:0',
            'receivables'=>'nullable|numeric | min:0',
            'tax'=>'nullable|numeric | min:0',
        ]);

        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }

        $rs->check_time = Request::input('start_time');
        $rs->customer_id = Request::input('end_time');
        $rs->title = Request::input('work_hours',0);
        $rs->type = Request::input('equipment_cost',0);
        $rs->linkman = Request::input('other_cost',0);
        $rs->phone = Request::input('receivables',0);
        $rs->station = Request::input('tax',0);
        $rs->type = Request::input('type');
        $rs->linkman = Request::input('linkman');
        $rs->phone = Request::input('phone');
        $rs->station = Request::input('station');
        $rs->remark = Request::input('remark');

        if($rs->save()){
            return $this->myResult(1,'更新成功！',$rs);
        }
        return $this->myResult(0,'操作失败，未知错误！',null);
    }

    /**
     * @api {post} /api/tasks/delete 4.删除任务
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.delete
     * 必填参数：
     * ID 任务编号 正整数
     * 2、code为1正常删除，为0请读取info显示原因
     */
    public function delete()
    {
        $rs=Task::find(Request::input('id',0));
        if(! $rs)
        {
            return $this->myResult(0,'未找到对应的任务记录！',null);
        }
        //已经和客户结算过的任务不能被删除
        if($rs->account_id >0)
        {
            return $this->myResult(0,'已经和客户结算过的任务不能被删除！',null);
        }
        //已经和员工结算过的任务不能被删除
        $n=DB::table('usertasks')
            ->where('task_id','=',$rs->id)->where('account_id','>','0')->count();
        if($n >0)
        {
            return $this->myResult(0,'已经和员工结算过的任务不能被删除！',null);
        }
        //已经和车辆结算过的任务不能被删除
        $n=DB::table('cartasks')
            ->where('task_id','=',$rs->id)->where('account_id','>','0')->count();
        if($n >0)
        {
            return $this->myResult(0,'已经和车辆结算过的任务不能被删除！',null);
        }

        //删除员工出勤记录
        DB::table('usertasks')
            ->where('task_id','=',$rs->id)->delete();
        //删除车辆出勤记录
        DB::table('cartasks')
            ->where('task_id','=',$rs->id)->delete();
        //删除任务本身
        $rs->delete();
        return $this->myResult(1,'该任务已经被成功删除！',null);
    }

    /**
     * @api {post} /api/tasks/addTaskMan 5.新增或编辑出勤人员
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.addTaskMan
     * 2、必选参数
     * id  人员出勤编号    大于0表示编辑，否则为新增
     * task_id 任务编号   必须大于0，且为真实存在的任务编号
     * user_id 出勤人员编号 必须大于0，且为存在的人员
     * start_time 开始时间 datetime
     * post 岗位 string 20
     * 3、可选参数
     * end_time 结束时间 datetime
     * work_hours 任务工时 2位小数
     * work_salary 岗位工资 2位小数
     * extra_hours 加班工时 2位小数
     * extra_salary 加班工资 2位小数
     * award_salary 奖惩工资 2位小数
     * score 任务评分 2位小数
     * remark 出勤备注 string
     */
    public function addTaskMan()
    {
        $mid=Request::input('id',0);
        //判断任务是否存在
        $rs=Task::find(Request::input('task_id',0));
        if(! $rs)
        {
            return $this->myResult(0,'未找到对应的任务信息！',null);
        }
        //判断人员是否存在
        $rs=User::find(Request::input('user_id',0));
        if(! $rs)
        {
            return $this->myResult(0,'未找到对应的人员信息！',null);
        }
        //如果是修改，判断出勤信息是否存在,否则新建一个模型对象
        if($mid>0){
            $rs=Usertask::find($mid);
            if(! $rs){
                return $this->myResult(0,'未找到对应的人员出勤信息！',null);
            }
        }else{
            $rs=new Usertask();
        }
        //开始输入校验
        $validator = Validator::make( Request::all(), [
            'start_time' => 'required|date',
            'end_time' => 'nullable|date',
            'post' => 'required',
            'work_hours' => 'nullable|numeric | min:0',
            'work_salary' => 'nullable|numeric | min:0',
            'extra_hours' => 'nullable|numeric | min:0',
            'extra_salary' => 'nullable|numeric | min:0',
            'award_salary' => 'nullable|numeric',
            'score' => 'nullable|numeric | min:0',
        ]);
        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }
        //更新字段信息
        $rs->task_id = Request::input('task_id');
        $rs->user_id = Request::input('user_id');
        $rs->start_time = Request::input('start_time');
        $rs->end_time = Request::input('end_time',null);
        $rs->post = Request::input('post');
        $rs->work_hours = Request::input('work_hours',0);
        $rs->work_salary = Request::input('work_salary',0);
        $rs->extra_hours = Request::input('extra_hours',0);
        $rs->extra_salary = Request::input('extra_salary',0);
        $rs->award_salary = Request::input('award_salary',0);
        $rs->score = Request::input('score',6);
        $rs->remark = Request::input('remark');

        if($rs->save()){
            return $this->myResult(1,'更新成功！',$rs);
        }
        return $this->myResult(0,'操作失败，未知错误！',null);
    }

    /**
     * @api {post} /api/tasks/delTaskMan 6.删除出勤人员
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.delTaskMan
     * 2、必选参数
     * id  人员出勤编号
     */
    public function delTaskMan()
    {
        $rs=Usertask::find(Request::input('id',0));
        if( !$rs ){
            return $this->myResult(0,'未找到对应的人员出勤信息！',null);
        }
        if($rs->account_id > 0 ){
            return $this->myResult(0,'该出勤记录已经与员工结算，不允许被删除！',null);
        }
        $rs->delete();
        return $this->myResult(1,'该出勤记录已经被成功删除！',null);
    }

    /**
     * @api {post} /api/tasks/delTaskCar 8.删除出勤车辆
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.delTaskCar
     * 2、必选参数
     * id 车辆出勤编号
     */
    public function delTaskCar()
    {
        $rs=Cartask::find(Request::input('id',0));
        if( !$rs ){
            return $this->myResult(0,'未找到对应的车辆出勤信息！',null);
        }
        if($rs->account_id > 0 ){
            return $this->myResult(0,'该出勤记录已经与车辆结算，不允许被删除！',null);
        }
        $rs->delete();
        return $this->myResult(1,'该出勤记录已经被成功删除！',null);
    }

    /**
     * @api {post} /api/tasks/addTaskCar 7.新增或编辑出勤车辆
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.addTaskCar
     * 2、必选参数
     * id  车辆出勤编号    大于0表示编辑，否则为新增
     * task_id 任务编号   必须大于0，且为真实存在的任务编号
     * car_id 出勤车辆编号 必须大于0，且为存在的车辆
     * start_time 开始时间 datetime
     * 3、可选参数
     * end_time 结束时间 datetime
     * rent_cost 车费 2位小数
     * oil_cost 油费 2位小数
     * toll_cost 路费 2位小数
     * park_cost 停车费 2位小数
     * award_salary 奖惩工资 2位小数
     * score 任务评分 2位小数
     * remark 出勤备注 string
     */
    public function addTaskCar()
    {
        $mid=Request::input('id',0);
        //判断任务是否存在
        $rs=Task::find(Request::input('task_id',0));
        if(! $rs)
        {
            return $this->myResult(0,'未找到对应的任务信息！',null);
        }
        //判断车辆是否存在
        $rs=Car::find(Request::input('car_id',0));
        if(! $rs)
        {
            return $this->myResult(0,'未找到对应的车辆信息！',null);
        }
        //如果是修改，判断出勤信息是否存在,否则新建一个模型对象
        if($mid>0){
            $rs=Cartask::find($mid);
            if(! $rs){
                return $this->myResult(0,'未找到对应的车辆出勤信息！',null);
            }
        }else{
            $rs=new Cartask();
        }
        //开始输入校验
        $validator = Validator::make( Request::all(), [
            'start_time' => 'required|date',
            'end_time' => 'nullable|date',
            'rent_cost' => 'nullable|numeric | min:0',
            'oil_cost' => 'nullable|numeric | min:0',
            'toll_cost' => 'nullable|numeric | min:0',
            'park_cost' => 'nullable|numeric | min:0',
            'award_salary' => 'nullable|numeric',
            'score' => 'nullable|numeric | min:0',
        ]);
        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }
        //更新字段信息
        $rs->task_id = Request::input('task_id');
        $rs->car_id = Request::input('car_id');
        $rs->start_time = Request::input('start_time');
        $rs->end_time = Request::input('end_time',null);
        $rs->rent_cost = Request::input('rent_cost',0);
        $rs->oil_cost = Request::input('oil_cost',0);
        $rs->toll_cost = Request::input('toll_cost',0);
        $rs->park_cost = Request::input('park_cost',0);
        $rs->award_salary = Request::input('award_salary',0);
        $rs->score = Request::input('score',6);
        $rs->remark = Request::input('remark');

        if($rs->save()){
            return $this->myResult(1,'更新成功！',$rs);
        }
        return $this->myResult(0,'操作失败，未知错误！',null);
    }
}
