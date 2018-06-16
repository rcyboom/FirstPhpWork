<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Customer;
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
        $isNew=false;
        if($id>0){
            $rs= Customer::find($id);
            if(!$rs){
                return $this->myResult(0,'更新失败，未找到该编号的客户！',null);
            }
        }else{
            $rs=new Customer();
            $isNew=true;
        }


        $validator = Validator::make( Request::all(), [
            'name' => 'required',
        ]);
        if($isNew || (!$isNew && Request::input('name')!=$rs->name)){
            $validator->sometimes('name', 'unique:customers',
                function(){
                    return true;
                });
        }


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
}
