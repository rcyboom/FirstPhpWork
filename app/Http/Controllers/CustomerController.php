<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;

class CustomerController extends Controller
{
    use Result;

    /**
     * @api {get} /api/customers/index 1.客户列表
     * @apiGroup 客户管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 customer.index
     * 2、可选参数
     * pageSize 分页数量，默认为15
     * name 客户名称,默认为空,模糊匹配 like %name%
     * @apiParamExample数据库表结构
     * increments('id')->comment('客户编号');
     * string('name', 20)->unique()->comment('客户名称');
     * string('from', 20)->nullable()->comment('客户来源');
     * string('linkman',20)->nullable()->comment('联系人');
     * string('phone', 20)->nullable()->comment('联系电话');
     * string('email', 50)->nullable()->comment('电子邮件');
     * string('card_type',20)->nullable()->comment('证件类型');
     * string('card_number', 30)->nullable()->comment('证件号码');
     * string('fax', 20)->nullable()->comment('传真号码');
     * string('remark')->nullable()->comment('备注');
     */
    public function index()
    {
        $pageSize = (int)Request::input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:15;
        $name=Request::input('name');

        $rs = Customer::where('id','>',0);
        if($name){
            $rs = $rs->where('name','like','%'.$name.'%');
        }
        $rs = $rs->paginate($pageSize);
        return $this->myResult(1,'获取信息成功！',$rs);
    }

    /**
     * @api {get} /api/customers/{id} 2.获取指定客户信息
     * @apiGroup 客户管理
     * @apiHeaderExample 简要说明
     * 1、路由名称 customer.getOne
     * 2、必须传递ID，正整数,
     * }
     */
    public function getOne($id)
    {
        $rs=Customer::find($id);
        if($rs){
            return $this->myResult(1,'获取信息成功！',$rs);
        }
        return $this->myResult(0,'未找到对应的ID！',null);
    }

    /**
     * @api {post} /api/customers/{id?} 3.新增或更新客户信息
     * @apiGroup 客户管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 customers.saveOne
     * 2、必选参数：
     * ID，客户编号，作为URL必填,大于0表示更新，否则新增
     * name，客户名称，字符串不能为空
     * 3、可选参数参照客户列表中的数据库结构
     */
    public function saveOne($id=0)
    {
        if($id>0){
            $rs= Customer::find($id);
            if(!$rs){
                return $this->myResult(0,'更新失败，未找到该编号的客户！',null);
            }
        }else{
            $rs=new Customer();
        }

        $validator = Validator::make( Request::all(), [
            'name' => 'required|unique:customers,name,'.$id,
        ]);


        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }

        $rs->name = Request::input('name');
        $rs->from = Request::input('from');
        $rs->linkman = Request::input('linkman');
        $rs->phone = Request::input('phone');
        $rs->email = Request::input('email');
        $rs->card_type = Request::input('card_type');
        $rs->card_number = Request::input('card_number');
        $rs->fax = Request::input('fax');
        $rs->remark = Request::input('remark');
        if($rs->save()){
            return $this->myResult(1,'更新成功！',$rs);
        }
        return $this->myResult(0,'操作失败，未知错误！',null);
    }

    /**
     * @api {get} /api/customers/customersList 4.返回已有客户名称
     * @apiGroup 客户管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 customers.customersList
     * 2、无需参数
     * 3、返回对应的ID和名称，ID可用于编辑的时候判断是否是当前记录进行校验
     */
    public function customersList()
    {
        $car=Customer::select('id','name')->get();
        return $this->myResult(1,'获取信息成功！',$car);
    }

    /**
     * @api {delete} /api/customers/:id  5.删除指定的客户
     * @apiGroup 客户管理
     * @apiSuccessExample 简要说明
     * 路由名称 customers.delete
     * HTTP/1.1 200 OK
     * 作为URL的ID参数必填，成功code为1，否则为0
     */

    public function destroy($id)
    {
        $user = Customer::find($id);
        if(!$user)
        {
            return $this->myResult(0,'删除失败,未找到该客户！',null);
        }
        $rs=DB::select('select count(*) as cs from tasks where customer_id=?',[$id]);
        if($rs[0]->cs > 0){
            return $this->myResult(0,'删除失败,已经有任务记录的客户不允许被删除！',null);
        }
        if ($user->delete()) {
            return $this->myResult(1,'删除成功！',null);
        } else {
            return $this->myResult(0,'删除失败！',null);
        }

    }

    /**
     * @api {post} /api/linkmans/:id  51.删除指定的客户联系人
     * @apiGroup 客户管理
     * @apiSuccessExample 简要说明
     * 路由名称 linkmans.delete
     * HTTP/1.1 200 OK
     * 作为URL的ID参数必填，始终返回成功，注意这个ID是联系人ID不是客户ID
     */

    public function delLinkMan()
    {
        DB::table('linkmans')->where('id',Request::input('id'))->delete();
        return $this->myResult(1,'删除成功！',null);

    }

    /**
     * @api {post} /api/linkmans/save  52.新建或者更新联系人
     * @apiGroup 客户管理
     * @apiSuccessExample 简要说明
     * 路由名称 linkmans.save
     * HTTP/1.1 200 OK
     * id 联系人ID，整数，大于等于0
     * customer_id 整数，客户ID
     * name 字符串，必填
     * phone 电话，必填
     */

    public function saveLinkMan()
    {
        DB::table('linkmans')::updateOrCreate(
            ['id' => Request::input('id')],
            ['customer_id' => Request::input('customer_id'),
                'name' => Request::input('name'),
                'phone' => Request::input('phone')]);
        return $this->myResult(1,'保存成功！',null);

    }

    /**
     * @api {post} /api/linkmans/list  53.获取某一个客户端联系人列表
     * @apiGroup 客户管理
     * @apiSuccessExample 简要说明
     * 路由名称 linkmans.list
     * HTTP/1.1 200 OK
     * customer_id 整数，客户ID
     */

    public function list()
    {
        $rs=DB::table('linkmans')::where('customer_id' , Request::input('customer_id'))->get();
        return $this->myResult(1,'获取成功！',$rs);
    }
}

