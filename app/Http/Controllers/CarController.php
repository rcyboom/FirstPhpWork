<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;

class CarController extends Controller
{
    use Result;

    /**
     * @api {get} /api/cars/index 1.车辆列表
     * @apiGroup 车辆管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 cars.index
     * 2、可选参数
     * pageSize 分页数量，默认为15
     * car_type 车辆型号,默认为空,模糊匹配 like %car_type%
     * state    状态，默认为空,精确匹配 =state
     * @apiParamExample数据库表结构
     * integer('id')->comment('车辆编号');
     * $string('car_type', 20)->nullable(false)->comment('车辆型号');
     * $string('car_number', 20)->nullable(false)->unique()->comment('车牌号');
     * $string('state',20)->comment('状态')->nullable();
     * $string('linkman', 20)->nullable(false)->comment('联系人');
     * $string('phone', 20)->nullable()->comment('联系电话');
     * $integer('work_price')->default(0)->comment('租车价格');
     * $string('from',20)->comment('来源')->nullable();
     * $string('remark',100)->comment('备注')->nullable();
     */
    //Route::get('cars/index', 'CarController@index')->name('cars.index');
    public function index()
    {
        $pageSize = (int)Request::input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:15;
        $car_type=Request::input('car_type');
        $state=Request::input('state');

        $cars = Car::where('id','>',0);
        if($state){
            $cars = $cars->where('state',$state);
        }
        if($car_type){
            $cars = $cars->where('car_type','like','%'.$car_type.'%');
        }
        $cars = $cars->paginate($pageSize);
        return $this->myResult(1,'获取信息成功！',$cars);
    }

    /**
     * @api {get} /api/cars/{id} 2.获取指定车俩信息
     * @apiGroup 车辆管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 cars.getCar
     * 2、必须传递ID，正整数,
     * @apiSuccessExample {json} 成功返回:
     * {
     * "code": 1,
     * "info": "获取信息成功！",
     * "data": {
     * "id": 1,
     * "car_type": "SUV",
     * "car_number": "苏A88888",
     * "state": "在岗",
     * "linkman": "张三",
     * "phone": "12345678",
     * "work_price": 100,
     * "from": "员工",
     * "remark": "备注",
     * "created_at": "2018-06-11 23:40:24",
     * "updated_at": "2018-06-11 23:40:25"
     * }
     * }
     */
    //Route::get('cars/{id}', 'CarController@getCar')->name('cars.getCar');
    public function getCar($id)
    {
        $car=Car::find($id);
        if($car){
            return $this->myResult(1,'获取信息成功！',$car);
        }
        return $this->myResult(0,'未找到对应的ID！',null);
    }

    /**
     * @api {post} /api/cars/{id?} 3.新增或更新车俩信息
     * @apiGroup 车辆管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 cars.saveCar
     * 3、必选参数：
     * ID，车辆编号，作为URL必填,大于0表示更新，否则新增
     * car_type，车辆型号，字符串不能为空
     * car_number，车牌号，字符串不能为空,数据库唯一
     * linkman，联系人，字符串不能为空
     * work_price，租车价格,整数，最小值0
     */
    //Route::post('cars/{id?}', 'CarController@saveCar')->name('cars.saveCar');
    public function saveCar($id=0)
    {
        if($id>0){
            $car= Car::find($id);
            if(!$car){
                return $this->myResult(0,'更新失败，未找到该编号的车辆！',$car);
            }
        }else{
            $car=new Car();
        }

        //unique:table,column,except,idColumn
        $validator = Validator::make( Request::all(), [
            'car_type' => 'required',
            'car_number' => 'required|unique:cars,car_number,'.$car->id,
            'linkman' => 'required',
            'work_price'=>'required | integer | min:0'
        ]);

        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }


        $car->car_type = Request::input('car_type');
        $car->car_number = Request::input('car_number');
        $car->state = Request::input('state');
        $car->linkman = Request::input('linkman');
        $car->phone = Request::input('phone');
        $car->work_price = Request::input('work_price');
        $car->from = Request::input('from');
        $car->remark = Request::input('remark');
        if($car->save()){
            return $this->myResult(1,'更新成功！',$car);
        }
        return $this->myResult(0,'操作失败，未知错误！',null);
    }

    /**
     * @api {get} /api/cars/carNumberList 4.返回已有车牌号列表
     * @apiGroup 车辆管理
     *@apiHeaderExample 简要说明
     * 1、路由名称 cars.carNumberList
     * 2、无需参数
     * 3、返回对应的ID和车牌号，ID可用于编辑的时候判断是否是当前记录进行校验
     */
    public function carNumberList()
    {
        $car=Car::select('id','car_number')->get();
        return $this->myResult(1,'获取信息成功！',$car);
    }

    /**
     * @api {delete} /api/cars/:id  5.删除指定的车辆
     * @apiGroup 车辆管理
     *
     * @apiSuccessExample 简要说明
     * 路由名称 cars.delete
     * HTTP/1.1 200 OK
     * 作为URL的ID参数必填，成功code为1，否则为0
     */

    public function destroy($id)
    {
        $user = Car::find($id);
        if(!$user)
        {
            return $this->myResult(0,'删除失败,未找到该车辆！',null);
        }
        $rs=DB::select('select ((select count(*) from cartasks where car_id=?)+'.
            '(select count(*) from userpays where object_id=? and object_type=?)) as cs',[$id,$id,'车辆']);
        if($rs[0]->cs > 0){
            return $this->myResult(0,'删除失败,已经有任务记录或者支出记录的车辆不允许被删除！',null);
        }
        if ($user->delete()) {
            return $this->myResult(1,'删除成功！',null);
        } else {
            return $this->myResult(0,'删除失败！',null);
        }

    }
}
