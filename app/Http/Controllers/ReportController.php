<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Psy\Tests\Input\CodeArgumentTest;

class ReportController extends Controller
{
    use Result;

    /**
     * @api {get} /api/report/task 1.客户工单统计
     * @apiGroup 报表管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 report.task
     * 2、可选参数
     * pageSize 分页数量，默认为30
     * customer_id 客户ID,默认为空表示全部,这里是下拉列表（调用以前的接口获取列表），允许输入一个字进行自动完成提示，但是必须选择所选项目
     * state    任务状态，已登记、未结算、已结算 3个选项，默认为空表示全部
     * start_time 开始时间  前段请默认为年初1月1号
     * end_time   截至时间  前段请默认为当前时间，注意格式，显示到天即可
     */
    public function task()
    {
        $pageSize = (int)Request::input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:30;

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
        return $this->myResult(1,'获取成功！',['sum'=>$total,'tasks'=>$tasks]);
    }

    /**
     * @api {get} /api/report/user 2.员工出勤及工资发放统计
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
        $start_time= new Carbon(Request::input('start_time'));
        $start_time=$start_time->startOfDay();
        $end_time= new Carbon(Request::input('end_time'));
        $end_time=$end_time->endOfDay();
        $yf=$end_time->month - $start_time->month +1;
        $type=Request::input('type',0);
        if($type>0)
            $prm='where fix_salary>0';
        elseif ($type<0)
            $prm='where fix_salary<1';
        else
            $prm='';

        $tasks=DB::select(
        "select level,name,duty,fix_salary*? as 基本工资,taskcount,workhours,levelavg,taskmoney as 任务工资,worksalary,
extrasalary,awardsalary,应加,应减,预支,补助,罚款,奖励,ifnull(加减,0) 加减合计,
(ifnull(taskmoney,0)+ifnull(加减,0)+fix_salary*?) as 应发金额,
accountmoney as 已发金额,(ifnull(taskmoney,0)+ifnull(加减,0)+fix_salary*?+ifnull(accountmoney,0)) as 未发金额 
from (select id,level,name,duty,fix_salary from users $prm)n 
left join (select  user_id,count(*) as taskcount,avg(score) as levelavg,
sum(work_salary+extra_salary+award_salary) as taskmoney,sum(work_salary) as worksalary,
sum(extra_salary) as extrasalary,sum(award_salary) as awardsalary,sum(work_hours) as workhours  
from usertasks where start_time>=? and start_time<=? group by user_id) a on n.id=a.user_id 
left join (select object_id,sum(money) as accountmoney from accounts where object_type='员工结算' 
and account_time>=? and account_time<=? group by object_id) c on n.id=c.object_id 
left join (SELECT object_id,
  SUM(CASE type WHEN '应加' THEN money ELSE 0 END ) 应加,
  SUM(CASE type WHEN '应减' THEN money ELSE 0 END ) 应减,
  SUM(CASE type WHEN '预支' THEN money ELSE 0 END ) 预支,
  SUM(CASE type WHEN '补助' THEN money ELSE 0 END ) 补助,
  SUM(CASE type WHEN '罚款' THEN money ELSE 0 END ) 罚款,
  SUM(CASE type WHEN '奖励' THEN money ELSE 0 END ) 奖励,
  SUM(money) 加减 from userpays where object_type='员工' and time>=? and time<=? group by object_id) d 
on n.id=d.object_id order by taskcount desc",
            [$yf,$yf,$yf,$start_time,$end_time,$start_time,$end_time,$start_time,$end_time]);

        $sum=DB::select(
            "select sum(fix_salary*?) as 基本工资,sum(taskcount) as 任务次数,sum(workhours) as 总工时,
sum(taskmoney) as 任务金额,sum(应加) as 应加,sum(应减) as 应减,sum(预支) as 预支,sum(补助) as 补助,sum(罚款) as 罚款,
sum(奖励) as 奖励,sum(加减) 加减合计,
sum(ifnull(taskmoney,0)+ifnull(加减,0)+fix_salary*?) as 应发金额,
sum(accountmoney) as 已发金额,sum(ifnull(taskmoney,0)+ifnull(加减,0)+fix_salary*?+ifnull(accountmoney,0)) as 未发金额 
from (select id,fix_salary from users $prm)n 
left join (select  user_id,count(*) as taskcount,
sum(work_salary+extra_salary+award_salary) as taskmoney,sum(work_salary) as worksalary,
sum(extra_salary) as extrasalary,sum(award_salary) as awardsalary,sum(work_hours) as workhours  
from usertasks where start_time>=? and start_time<=? group by user_id) a on n.id=a.user_id 
left join (select object_id,sum(money) as accountmoney from accounts where object_type='员工结算' 
and account_time>=? and account_time<=? group by object_id) c on n.id=c.object_id 
left join (SELECT object_id,
  SUM(CASE type WHEN '应加' THEN money ELSE 0 END ) 应加,
  SUM(CASE type WHEN '应减' THEN money ELSE 0 END ) 应减,
  SUM(CASE type WHEN '预支' THEN money ELSE 0 END ) 预支,
  SUM(CASE type WHEN '补助' THEN money ELSE 0 END ) 补助,
  SUM(CASE type WHEN '罚款' THEN money ELSE 0 END ) 罚款,
  SUM(CASE type WHEN '奖励' THEN money ELSE 0 END ) 奖励,
  SUM(money) 加减 from userpays where object_type='员工' and time>=? and time<=? group by object_id) d 
on n.id=d.object_id order by taskcount desc",
            [$yf,$yf,$yf,$start_time,$end_time,$start_time,$end_time,$start_time,$end_time]);

        return $this->myResult(1,'获取成功！',['sum'=>$sum,'tasks'=>$tasks]);
    }

    /**
     * @api {get} /api/report/car 3.车辆出勤及工资发放统计
     * @apiGroup 报表管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 report.car
     * 2、可选参数 不分页
     * start_time 开始时间  前段请默认为年初1月1号
     * end_time   截至时间  前段请默认为当前时间，注意格式，显示到天即可
     */
    public function car()
    {
        $start_time= new Carbon(Request::input('start_time'));
        $start_time=$start_time->startOfDay();
        $end_time= new Carbon(Request::input('end_time'));
        $end_time=$end_time->endOfDay();


        $tasks=DB::select(
            "select car_type,car_number,work_price,taskcount,levelavg,taskmoney,rentcost,oilcost,
tollcost,parkcost,awardsalary,ifnull(加减,0) 加减金额,
(ifnull(taskmoney,0)+ifnull(加减,0)) as 应发金额,accountmoney as 已发金额,
(ifnull(taskmoney,0)+ifnull(加减,0)+ifnull(accountmoney,0)) as 未发金额  from cars 
left join (select  car_id,count(*) as taskcount,avg(score) as levelavg,
sum(rent_cost+oil_cost+toll_cost+park_cost+award_salary) as taskmoney, 
sum(rent_cost) as rentcost,sum(oil_cost) as oilcost,sum(toll_cost) as tollcost,
sum(park_cost) as parkcost,sum(award_salary) as awardsalary 
from cartasks where start_time>=? and start_time<=? group by car_id) a on cars.id=a.car_id 
left join (select object_id,sum(money) as accountmoney from accounts where object_type='车辆结算' 
and account_time>=? and account_time<=? group by object_id) c on cars.id=c.object_id 
left join (SELECT object_id,
  SUM(CASE type WHEN '应加' THEN money ELSE 0 END ) 应加,
  SUM(CASE type WHEN '应减' THEN money ELSE 0 END ) 应减,
  SUM(CASE type WHEN '预支' THEN money ELSE 0 END ) 预支,
  SUM(CASE type WHEN '补助' THEN money ELSE 0 END ) 补助,
  SUM(CASE type WHEN '罚款' THEN money ELSE 0 END ) 罚款,
  SUM(CASE type WHEN '奖励' THEN money ELSE 0 END ) 奖励,
  SUM(money) 加减 from userpays 
where object_type='车辆' and time>=? and time<=? group by object_id) d 
on cars.id=d.object_id 
order by taskcount desc",
            [$start_time,$end_time,$start_time,$end_time,$start_time,$end_time]);

        $sum=DB::select(
            "select sum(taskcount) as 出勤次数,sum(taskmoney) as 出勤费用,sum(rentcost) as 车费 ,sum(oilcost) as 油费,
sum(tollcost) as 过路费,sum(parkcost) as 停车费,sum(awardsalary) as 绩效金额,sum(加减) AS 加减金额,
sum(ifnull(taskmoney,0)+ifnull(加减,0)) as 应发金额,sum(accountmoney) as 已发金额,
sum(ifnull(taskmoney,0)+ifnull(加减,0)+ifnull(accountmoney,0)) as 未发金额  from cars 
left join (select  car_id,count(*) as taskcount,avg(score) as levelavg,
sum(rent_cost+oil_cost+toll_cost+park_cost+award_salary) as taskmoney, 
sum(rent_cost) as rentcost,sum(oil_cost) as oilcost,sum(toll_cost) as tollcost,
sum(park_cost) as parkcost,sum(award_salary) as awardsalary 
from cartasks where start_time>=? and start_time<=? group by car_id) a on cars.id=a.car_id 
left join (select object_id,sum(money) as accountmoney from accounts where object_type='车辆结算' 
and account_time>=? and account_time<=? group by object_id) c on cars.id=c.object_id 
left join (SELECT object_id,
  SUM(CASE type WHEN '应加' THEN money ELSE 0 END ) 应加,
  SUM(CASE type WHEN '应减' THEN money ELSE 0 END ) 应减,
  SUM(CASE type WHEN '预支' THEN money ELSE 0 END ) 预支,
  SUM(CASE type WHEN '补助' THEN money ELSE 0 END ) 补助,
  SUM(CASE type WHEN '罚款' THEN money ELSE 0 END ) 罚款,
  SUM(CASE type WHEN '奖励' THEN money ELSE 0 END ) 奖励,
  SUM(money) 加减 from userpays 
where object_type='车辆' and time>=? and time<=? group by object_id) d 
on cars.id=d.object_id 
order by taskcount desc",
            [$start_time,$end_time,$start_time,$end_time,$start_time,$end_time]);

        return $this->myResult(1,'获取成功！',['sum'=>$sum,'tasks'=>$tasks]);
    }

    /**
     * @api {get} /api/report/userlist 4.人员出勤统计
     * @apiGroup 报表管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 report.userlist
     * 2、可选参数 分页
     * pageSize 分页参数，默认为30
     * start_time 开始时间  前段请默认为年初1月1号
     * end_time   截至时间  前段请默认为当前时间，注意格式，显示到天即可
     * id 用户ID，默认为空表示全部,这里是下拉列表（调用以前的接口获取列表），允许输入一个字进行自动完成提示，但是必须选择所选项目
     */
    public function userlist()
    {
        $pageSize = (int)Request::input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:30;
        $uid=Request::input('id');
        $start_time= new Carbon(Request::input('start_time'));
        $start_time=$start_time->startOfDay();
        $end_time= new Carbon(Request::input('end_time'));
        $end_time=$end_time->endOfDay();

        $tasks=DB::Table('rusertask')
            ->where('start_time','>=',$start_time)
            ->where('start_time','<=',$end_time);
        if($uid)
            $tasks=$tasks->where('user_id',$uid);
        $tasks=$tasks->orderby('user_id')->paginate($pageSize);

        $sum=DB::table('rusertask')
            ->select(DB::raw('count(*) as count,sum(work_hours) as workhours,sum(work_salary) as worksalary,'
                .'sum(extra_salary) as extrasalary,sum(award_salary) as awardsalary,sum(salary) as salary'))
            ->where('start_time','>=',$start_time)
            ->where('start_time','<=',$end_time);
        if($uid)
            $sum=$sum->where('user_id',$uid);
        $sum=$sum->first();


        return $this->myResult(1,'获取成功！',['sum'=>$sum,'tasks'=>$tasks]);
    }

    /**
     * @api {get} /api/report/carlist 5.车辆出勤统计
     * @apiGroup 报表管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 report.carlist
     * 2、可选参数 分页
     * pageSize 分页参数，默认为30
     * start_time 开始时间  前段请默认为年初1月1号
     * end_time   截至时间  前段请默认为当前时间，注意格式，显示到天即可
     * id 车辆ID，默认为空表示全部,这里是下拉列表（调用以前的接口获取列表），允许输入一个字进行自动完成提示，但是必须选择所选项目
     */
    public function carlist()
    {
        $pageSize = (int)Request::input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:30;
        $uid=Request::input('id');
        $start_time= new Carbon(Request::input('start_time'));
        $start_time=$start_time->startOfDay();
        $end_time= new Carbon(Request::input('end_time'));
        $end_time=$end_time->endOfDay();

        $tasks=DB::Table('rcartask')
            ->where('start_time','>=',$start_time)
            ->where('start_time','<=',$end_time);
        if($uid)
            $tasks=$tasks->where('car_id',$uid);
        $tasks=$tasks->orderby('car_id')->paginate($pageSize);

        $sum=DB::table('rcartask')
            ->select(DB::raw('count(*) as count,sum(rent_cost) as rentcost,sum(oil_cost) as oilcost,
            sum(toll_cost) as tollcost,sum(park_cost) as parkcost,sum(award_salary) as awardsalary,sum(salary) as salary'))
            ->where('start_time','>=',$start_time)
            ->where('start_time','<=',$end_time);
        if($uid)
            $sum=$sum->where('car_id',$uid);
        $sum=$sum->first();

        return $this->myResult(1,'获取成功！',['sum'=>$sum,'tasks'=>$tasks]);
    }

    /**
     * @api {get} /api/report/objecttype 60.获取可选的收支对象类型
     * @apiGroup 报表管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 report.objecttype
     * 2、不要任何参数，用于接口6使用
     */
    public function objecttype()
    {
        $sum=DB::table('accounts')->select('object_type')->distinct()->pluck('object_type');
        return $this->myResult(1,'获取成功！',$sum);
    }

    /**
     * @api {get} /api/report/accountlist 6.财务收支统计
     * @apiGroup 报表管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 report.accountlist
     * 2、可选参数 分页
     * pageSize 分页参数，默认为30
     * start_time 开始时间  前段请默认为年初1月1号
     * end_time   截至时间  前段请默认为当前时间，注意格式，显示到天即可
     * account_type 收支类型，默认空表示全部，可选项 1表示收入 -1表示支出
     * trade_type 账户类型，这个应该是从你那个配置选项获取下拉框，默认空表示全部
     * object_type 收支对象类型，接口61返回所有可选类型，然后允许输入自动匹配但是必须是选择项里面的一条或者为空表示全部
     * object_name 对象名称，手动输入字符串，默认空，模糊匹配
     * 注意，如果是员工结算，需要可以弹出那个PDF页面明细
     */
    public function accountlist()
    {
        $pageSize = (int)Request::input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:30;
        $start_time= new Carbon(Request::input('start_time'));
        $start_time=$start_time->startOfDay();
        $end_time= new Carbon(Request::input('end_time'));
        $end_time=$end_time->endOfDay();

        $account_type=Request::input('account_type');
        $trade_type=Request::input('trade_type');
        $object_type=Request::input('object_type');
        $object_name=Request::input('object_name');


        $tasks=DB::Table('accounts')
            ->select('id','account_type','account_time','object_type','object_name','money','trade_type','trade_account','handler','remark')
            ->where('account_time','>=',$start_time)
            ->where('account_time','<=',$end_time);
        if($account_type)
            $tasks=$tasks->where('account_type',$account_type);
        if($trade_type)
            $tasks=$tasks->where('trade_type',$trade_type);
        if($object_type)
            $tasks=$tasks->where('object_type',$object_type);
        if($object_name)
            $tasks=$tasks->where('object_name','like','%'.$object_name.'%');

        $tasks=$tasks->orderby('account_time')->paginate($pageSize);

        $sum=DB::select("
        select *,预支工资+员工结算+客户结算+车辆结算+公司运营费+其他收入-设备费用-税费 as 总利润 
from (SELECT  SUM(CASE object_type WHEN '客户结算' THEN money ELSE 0 END ) 客户结算,
  SUM(CASE object_type WHEN '其他收入' THEN money ELSE 0 END ) 其他收入,
  SUM(CASE object_type WHEN '预支工资' THEN money ELSE 0 END ) 预支工资,
  SUM(CASE object_type WHEN '员工结算' THEN money ELSE 0 END ) 员工结算,
  SUM(CASE object_type WHEN '车辆结算' THEN money ELSE 0 END ) 车辆结算,
  SUM(CASE object_type WHEN '公司运营费' THEN money ELSE 0 END ) 公司运营费 
FROM accounts WHERE account_time>=? and account_time<=?) a,
(SELECT sum(equipment_cost) as 设备费用,sum(tax) as 税费 
 from tasks where start_time>=? and start_time<=?) b",[$start_time,$end_time,$start_time,$end_time]);
        $sum=$sum[0];
        return $this->myResult(1,'获取成功！',['sum'=>$sum,'tasks'=>$tasks]);
    }
}
