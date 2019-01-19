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

class ReportController extends Controller
{
    use Result;

    /**
     * @api {get} /api/report/task 1.工单查询列表
     * @apiGroup 报表管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 report.task
     * 2、可选参数
     * pageSize 分页数量，默认为15
     * customer_id 客户ID,默认为空表示全部,这里是下拉列表（调用以前的接口获取列表），允许输入一个字进行自动完成提示，但是必须选择所选项目
     * state    任务状态，已登记、未结算、已结算 3个选项，默认为空表示全部
     * start_time 开始时间  前段请默认为年初1月1号
     * end_time   截至时间  前段请默认为当前时间，注意格式，显示到天即可
     */
    public function task()
    {
        $pageSize = (int)Request::input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:15;

        $customer_id=Request::input('customer_id');
        $state=Request::input('state');
        $start_time= new Carbon(Request::input('start_time'));
        $start_time=$start_time->startOfDay();
        $end_time= new Carbon(Request::input('end_time'));
        $end_time=$end_time->endOfDay();

        $tasks=DB::Table('rtask')
            ->where('start_time','>=',$start_time)
            ->where('start_time','<=',$end_time);
        if($customer_id)
            $tasks=$tasks->where('customer_id',$customer_id);
        if($state)
            $tasks=$tasks->where('state',$state);
        $tasks=$tasks->orderby('start_time','DESC')->paginate($pageSize);

        $total=DB::table('rtask')
            ->select(DB::raw('count(*) as count,sum(receivables) as receivables,sum(money) as money,'
                .'sum(equipment_cost) as equipment_cost,sum(other_cost) as other_cost,sum(tax) as tax, '
                .'sum(usermoney) as usermoney,sum(carmoney) as carmoney'))
            ->where('start_time','>=',$start_time)
            ->where('start_time','<=',$end_time);
        if($customer_id)
            $total=$total->where('customer_id',$customer_id);
        if($state)
            $total=$total->where('state',$state);
        $total=$total->get();
        return $this->myResult(1,'获取成功！',['tasks'=>$tasks,'sum'=>$total[0]]);
    }

    /**
     * @api {get} /api/report/user 2.员工出勤及工资发放分析
     * @apiGroup 报表管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 report.user
     * 2、可选参数 不分页
     * type 员工类型，1为固定员工，0为全部员工，-1为临时员工
     * start_time 开始时间  前段请默认为年初1月1号
     * end_time   截至时间  前段请默认为当前时间，注意格式，显示到天即可
     */
    public function user()
    {
        $pageSize = (int)Request::input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:15;

        $start_time= new Carbon(Request::input('start_time'));
        $start_time=$start_time->startOfDay();
        $end_time= new Carbon(Request::input('end_time'));
        $end_time=$end_time->endOfDay();
        $type=Request::input('type',0);
        if($type>0)
            $prm='where fix_salary>0';
        elseif ($type<0)
            $prm='where fix_salary<1';
        else
            $prm='';

        $tasks=DB::select(
        "select level,name,duty,state,fix_salary,work_time,taskcount,levelavg,taskmoney,worksalary,
extrasalary,awardsalary,total_salary from (select id,level,name,duty,state,fix_salary,work_time from users $prm)n 
left join (select  user_id,count(*) as taskcount,avg(score) as levelavg,
sum(work_salary+extra_salary+award_salary) as taskmoney,sum(work_salary) as worksalary,
sum(extra_salary) as extrasalary,sum(award_salary) as awardsalary 
from usertasks where start_time>=? and start_time<=? group by user_id) a on n.id=a.user_id 
left join (select object_id,sum(money) as total_salary from accounts where object_type='员工结算' 
and account_time>=? and account_time<=? group by object_id) c on n.id=c.object_id order by taskcount desc",
            [$start_time,$end_time,$start_time,$end_time]);

        return $this->myResult(1,'获取成功！',$tasks);
    }

    /**
     * @api {get} /api/report/car 3.车辆出勤及工资发放分析
     * @apiGroup 报表管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 report.car
     * 2、可选参数 不分页
     * start_time 开始时间  前段请默认为年初1月1号
     * end_time   截至时间  前段请默认为当前时间，注意格式，显示到天即可
     */
    public function car()
    {
        $pageSize = (int)Request::input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:15;

        $start_time= new Carbon(Request::input('start_time'));
        $start_time=$start_time->startOfDay();
        $end_time= new Carbon(Request::input('end_time'));
        $end_time=$end_time->endOfDay();


        $tasks=DB::select(
            "select car_type,car_number,work_price,taskcount,levelavg,taskmoney,rentcost,oilcost,tollcost,parkcost,awardsalary,total_salary from cars 
left join (select  car_id,count(*) as taskcount,avg(score) as levelavg,sum(rent_cost+oil_cost+toll_cost+park_cost+award_salary) as taskmoney, 
sum(rent_cost) as rentcost,sum(oil_cost) as oilcost,sum(toll_cost) as tollcost,sum(park_cost) as parkcost,sum(award_salary) as awardsalary 
from cartasks where start_time>=? and start_time<=? group by car_id) a on cars.id=a.car_id 
left join (select object_id,sum(money) as total_salary from accounts where object_type='车辆结算' 
and account_time>=? and account_time<=? group by object_id) c on cars.id=c.object_id order by taskcount desc",
            [$start_time,$end_time,$start_time,$end_time]);

        return $this->myResult(1,'获取成功！',$tasks);
    }

}
