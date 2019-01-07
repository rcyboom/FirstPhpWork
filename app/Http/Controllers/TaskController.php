<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskCollection;
use App\Models\Account;
use App\Models\Cartask;
use App\Models\Task;
use App\Models\Usertask;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

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
     * title    任务名称，默认为空,模糊匹配
     * state    任务状态  默认为全部，我给你一个API，这个API返回的选项作为下拉列表
     * start_time 开始时间  默认为当前时间前推一个月
     * end_time   截至时间  默认为当前时间
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

        $tasks = Task::Other()->Title()->CustomerName()->orderBy('id', 'desc')->paginate($pageSize);
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
            'start_time'=>'nullable|date',
            'end_time'=>'nullable|date'
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
        $rs->end_time = Request::input('end_time');
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
         * 这些字段不可修改：check_time 登记时间,customer_id客户ID,title任务标题
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

        $rs->start_time = Request::input('start_time');
        $rs->end_time = Request::input('end_time');
        $rs->work_hours = Request::input('work_hours',0);
        $rs->equipment_cost = Request::input('equipment_cost',0);
        $rs->other_cost = Request::input('other_cost',0);
        $rs->receivables = Request::input('receivables',0);
        $rs->tax = Request::input('tax',0);
        $rs->type = Request::input('type');
        $rs->linkman = Request::input('linkman');
        $rs->phone = Request::input('phone');
        $rs->station = Request::input('station');
        $rs->remark = Request::input('remark');

        if($rs->save()){
            if($rs->account_id >0){
                $acc=Account::find($rs->account_id);
                if($acc){
                    $acc->money=$rs->receivables;
                    if($acc->save()){
                        return $this->myResult(1,'任务更新成功，并同步更新了收支记录！',$rs);
                    }
                }else
                    return $this->myResult(1,'任务更新成功，但未更新成功对应的收支记录！',$rs);
            }else
                return $this->myResult(1,'更新成功！',$rs);
        }else
            return $this->myResult(0,'操作失败，请刷新数据后重试！',null);
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

        $d1=new Carbon($rs->start_time);
        $d2=new Carbon($rs->end_time);
        $diff=$d2->diffInMinutes($d1);
        $diff=(floor($diff/60))+round($diff%60/60,2);

        $rs->post = Request::input('post');
        $rs->work_hours = $diff;
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
     * @api {post} /api/tasks/addTaskManList 51.批量新增出勤人员
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.addTaskMan
     * 2、必选参数
     *
     * 注意参数只有一个数组形式users，以下为数组参数的内容，数组数量必须大于0个
     * task_id 任务编号   必须大于0，且为真实存在的任务编号
     * user_id 出勤人员编号 必须大于0，且为存在的人员
     * post 岗位 string 20
     * start_time 开始时间 datetime
     * work_salary 岗位工资 2位小数
     */
    public function addTaskManList()
    {
        //开始输入校验
        $validator = Validator::make( Request::all(), [
            "users" => 'required|array|min:1',
            'users.*.task_id' => 'required|exists:tasks,id',
            'users.*.user_id' => 'required|exists:users,id',
            'users.*.post' => 'required',
            'users.*.start_time' => 'required|date',
            'users.*.work_salary' => 'required|numeric|min:0',
        ]);
        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }
        $users=Request::input('users');
        $task=Task::find($users[0]['task_id']);
        if($task)
        {
            $start=$task->start_time;
            $end=$task->end_time;
            $d1=new Carbon($start);
            $d2=new Carbon($end);
            $diff=$d2->diffInMinutes($d1);
            $diff=(floor($diff/60))+round($diff%60/60,2);
            foreach($users as $k=>$v){
                $users[$k]['start_time']=$start;
                $users[$k]['end_time']=$end;
                $users[$k]['work_hours']=$diff;
            }
            if(DB::table('usertasks')->insert($users)){
                return $this->myResult(1,'添加成功！',null);
            }
            return $this->myResult(0,'操作失败，未知错误！',null);
        }else
            return $this->myResult(0,'操作失败，未找到指定的任务记录！',null);
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

    /**
     * @api {post} /api/tasks/addTaskCarList 71.批量新增出勤车辆
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.addTaskCarList
     * 2、必选参数
     *
     * 注意参数只有一个数组形式cars，以下为数组参数的内容，数组数量必须大于0个
     * task_id 任务编号   必须大于0，且为真实存在的任务编号
     * car_id 出勤车辆编号 必须大于0，且为存在的车辆
     * start_time 开始时间 datetime
     * rent_cost 车费 2位小数
     */
    public function addTaskCarList()
    {
        //开始输入校验
        $validator = Validator::make( Request::all(), [
            "cars" => 'required|array|min:1',
            'cars.*.task_id' => 'required|exists:tasks,id',
            'cars.*.car_id' => 'required|exists:cars,id',
            'cars.*.start_time' => 'required|date',
            'cars.*.rent_cost' => 'required|numeric|min:0',
        ]);
        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }
        $users=Request::input('cars');

        if(DB::table('cartasks')->insert($users)){
            return $this->myResult(1,'添加成功！',null);
        }
        return $this->myResult(0,'操作失败，未知错误！',null);
    }

    /**
     * @api {get} /api/tasks/TaskCars 9.获取出勤车辆列表
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.TaskCars
     * 2、必选参数
     * task_id 必选参数，正整数
     * 特殊说明：
     * 1、只返回单个任务的出勤车辆，无需分页
     * 2、后期再增加返回相应关联字段
     */
    public function TaskCars()
    {
        $rs=DB::select('select cartasks.*,cars.car_type,cars.car_number,cars.phone,'.
            '(rent_cost+oil_cost+toll_cost+park_cost+award_salary) as total from cartasks '.
            'left join cars on cartasks.car_id=cars.id where task_id=?',[Request::input('task_id',0)]);

        return $this->myResult(1,'信息获取成功！',$rs);
    }

    /**
     * @api {get} /api/tasks/TaskMans 91.获取出勤人员列表
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.TaskMans
     * 2、必选参数
     * task_id 必选参数，正整数
     * 特殊说明：
     * 1、只返回单个任务的出勤人员，无需分页
     * 2、后期再增加返回相应关联字段
     */
    public function TaskMans()
    {
         $rs=DB::select('select usertasks.*,users.name,users.phone_number,'.
            '(usertasks.work_salary+usertasks.extra_salary+usertasks.award_salary) as total from usertasks '.
            'left join users on usertasks.user_id=users.id where task_id=?',[Request::input('task_id',0)]);

        return $this->myResult(1,'信息获取成功！',$rs);
    }

    /**
     * @api {get} /api/tasks/FreeMans 92.获取在岗空闲人员列表
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.FreeMans
     * 2、无需参数，无需分页
     * 特殊说明：
     * 1、只返回state状态为在岗的人员
     * 2、排除出勤任务中end_time为空也就是未收工的人员
     */
    public function FreeMans()
    {
        $rs=DB::select('select id,name,phone_number,duty,state,work_salary,level from users where state=? AND id not in '.
                '(select user_id from usertasks where end_time is null)',["在岗"]);

        return $this->myResult(1,'信息获取成功！',$rs);
    }

    /**
     * @api {get} /api/tasks/FreeCars 93.获取在岗空闲车辆列表
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.FreeCars
     * 2、无需参数，无需分页
     * 特殊说明：
     * 1、只返回state状态为在岗的车辆
     * 2、排除出勤任务中end_time为空也就是未收工的车辆
     */
    public function FreeCars()
    {
        $rs=DB::select("select id,car_type,car_number,linkman,phone,state,work_price as rent_cost from cars where state=? ".
            " and id not in (select car_id from cartasks where end_time is null)",['在岗']);

        return $this->myResult(1,'信息获取成功！',$rs);
    }

    /**
     * @api {get} /api/tasks/getStateList 94.获取任务各种状态选项
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.getStateList
     * 2、无需参数，无需分页
     * 特殊说明：
     * 1、返回的 states 作为任务状态下拉列表的值，你需要增加一个全部选项
     */
    public function getStateList()
    {
        $rs=DB::select("select distinct(state) from tasks ");
        return $this->myResult(1,'信息获取成功！',['states'=>$rs]);
    }

    /**
     * @api {get} /api/tasks/getOption 95.获取系统设置
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.getOption
     * 2、key_name 字符串，返回其对应的数组
     */
    public function getOption()
    {
        $options = DB::table('options')->where('key_name',Request::input('key_name','-'))->first();
        if($options){
            $rs= explode(',',$options->key_value);
            return $this->myResult(1,'信息获取成功！',$rs);
        }else
            return $this->myResult(0,'未找到对应的键值！',null);
    }

    /**
     * @api {post} /api/tasks/setOption 96.更新系统设置
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.setOption
     * 2、key_name 字符串
     * 3、key_value 逗号分隔的字符串
     */
    public function setOption()
    {
        $key = Request::input('key_name');
        if (isset($key)) {
            $val = Request::input('key_value');
            DB::table('options')->where('key_name', $key)->delete();
            DB::table('options')->insert(['key_name' => $key, 'key_value' => $val]);
            return $this->myResult(1, '更新成功！', null);
        } else
            return $this->myResult(0, '键值不能为空！', null);
    }

    /**
     * @api {get} /api/tasks/number 97.获取新的工单号
     * @apiGroup 任务管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 tasks.setOption
     * date 日期格式2018-01-01
     */
    public function number()
    {
        //开始输入校验
        $validator = Validator::make( Request::all(), [
            'date' => 'required|date',
        ]);
        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }

            $d=new Carbon(Request::input('date'));
            $cc=DB::table('tasks')->where('check_time', $d)->count()+1;
            if($cc<10)
                $rs='00'.$cc;
            elseif ($cc>=10 and $cc<100)
                $rs='0'.$cc;
            else
                $rs=$cc;

        if($d->day<10)
            $rs='0' .$d->day . $rs;
        else
            $rs=$d->day . $rs;

        if($d->month<10)
            $rs='0' .$d->month . $rs;
        else
            $rs=$d->month . $rs;

        $rs='GD'.$d->year . $rs;

        return $this->myResult(1, '更新成功！',$rs );
    }
}
