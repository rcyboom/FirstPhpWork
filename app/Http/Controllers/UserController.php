<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Import\UserImport;
use App\Http\Resources\UserCollection;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
   use Result;

    /**
     * @api {get} /api/admin 4.显示管理员列表
     * @apiGroup 用户管理
     *
     *
     * @apiSuccessExample 简要说明
     * 1、默认返回全部数据的分页显示
     * 2、可选参数：
     * name 值不为空时表示 like方式过滤
     * phone_number 值不为空时表示 like方式过滤
     * state 值不为空时表示 like方式过滤
     */
    public function index(Request $request)
    {
        //
        $pageSize = (int)$request->input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:10;
        $users = User::name()->state()->Phone()->paginate($pageSize);
        return new UserCollection($users);
    }


    public function create(Request $request)
    {

    }

    /**
     * @api {post} /api/admin  5.建立新的管理员
     * @apiGroup 用户管理
     * @apiParam {string} name 用户昵称 必须唯一
     * @apiParam {string} email 用户登录名　email格式 必须唯一
     * @apiParam {string} password 用户登录密码
     * @apiParam {string} password_confirmation 用户登录密码
     * @apiParam {string="admin","editor"} [role="editor"] 角色 内容为空或者其他的都设置为editor
     * @apiParam {string} [phone_number] 联系电话
     * @apiParam {string="男","女"} [sex="男"] 性别
     * @apiParam {string} [state] 状态
     * @apiParam {date} [birthday] 出生日期
     * @apiParam {date} [work_time] 入职日期
     * @apiParam {string} [card_type] 证件类型
     * @apiParam {string} [card_number] 证件号码
     * @apiParam {string} [duty] 职务
     * @apiParam {string} [level] 等级
     * @apiParam {string} [from] 来源
     * @apiParam {integer} [fix_salary] 固定工资
     * @apiParam {integer} [work_salary] 出班工资
     * @apiParam {integer} [extra_salary] 加班工资
     * @apiParam {string} [family_address] 家庭地址
     * @apiParam {string} [personal_address] 现住址
     * @apiParam {string} [remark]  备注
     * @apiParamExample 简要说明:
     * 1、除了必选参数，其余的参数可以为空或者不传
     * 2、出生日期和入职日期必须为日期型
     * 3、工资必须为不小于0的整数
     * 4、role 必须要以数组形式传递 ["editor","admin"]
     */
    public function store(Request $request)
    {
        //  新建管理员信息
        $data = $request->only(['name', 'role', 'password','password_confirmation', 'email', 'avatar',
            'phone_number' ,'sex' ,'state' ,'birthday' ,'work_time' ,'card_type' ,'card_number' ,'duty' ,
            'level' ,'from' ,'fix_salary' ,'work_salary' ,'extra_salary' ,'family_address' ,
            'personal_address' ,'remark' ]);
        $rules = [
            'name'=>'required|unique:users',
            'role' =>'nullable',
            'password' => 'required|confirmed',
            'email' => 'required|unique:users',
            'avatar' => 'nullable|string',
            'birthday' => 'nullable|date',
            'work_time' => 'nullable|date',
            'fix_salary' => 'nullable|integer',
            'work_salary' => 'nullable|integer',
            'extra_salary' => 'nullable|integer',
        ];
        $message = [
            'name.required' => '用户名是必填项',
            'name.unique' => '用户名必须唯一',
            'password.required' => '用户密码是必填项',
            'password.confirmed' => '两次输入的密码不匹配',
            'email.required' => '邮箱地址是必填项',
            'email.unique' => '邮箱地址已经存在，请重新填写',
        ];
        $validator = Validator::make($data, $rules, $message);
        if ($validator->fails()) {
            //$validator->errors($validator)
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }
        $data['password'] = bcrypt($data['password']);

        $role = $request->input('role', ['user']);
        if ($role === null || $role == [])
        {
            $role = ['user'];
        }
        if (! is_array($role)) {
            $roles = json_decode($role, true);
            $data['role'] = implode(',', $roles);
        } else {
            $data['role'] = implode(',', $role);
        }

        $us= User::create($data);

        if ($us) {
            return $this->myResult(1,'创建成功！',$us);
        }else{
            return $this->myResult(0,'创建失败！',null);
        }
    }


    /**
     * @api {get} /api/admin/:id 6.显示指定的管理员
     * @apiGroup 用户管理
     *
     *
     * @apiSuccessExample 返回管理员信息
     * HTTP/1.1 200 OK
     * {
     * "data": {
     *   "id": 1,
     *   "name": "wmhello",
     *   "email": "871228582@qq.com",
     *   "role": "admin",
     *   "avatar": ""
     * },
     * "status": "success",
     * "status_code": 200
     * }
     *
     */
    public function show($id)
    {
        //
       $user =  User::find($id);
       return new \App\Http\Resources\User($user);
    }

    public function edit($id)
    {
        //
    }


    /**
     * @api {put} /api/admin/:id  7.更新指定的管理员
     * @apiGroup 用户管理
     * @apiHeaderExample 简要说明:
     * 具体参数情况请参考创建用户的参数
     * 作为URL部分的ID必填
     */

    public function update(Request $request, $id)
    {
        $data = $request->only(['name', 'role', 'avatar','email',
            'phone_number' ,'sex' ,'state' ,'birthday' ,'work_time' ,'card_type' ,'card_number' ,'duty' ,
            'level' ,'from' ,'fix_salary' ,'work_salary' ,'extra_salary' ,'family_address' ,
            'personal_address' ,'remark' ]);
        $rules = [
            'name'=>'required|unique:users,name,'.$id,
            'email' => 'required|unique:users,email,'.$id,
            'avatar' => 'nullable|string',
            'birthday' => 'nullable|date',
            'work_time' => 'nullable|date',
            'fix_salary' => 'nullable|integer',
            'work_salary' => 'nullable|integer',
            'extra_salary' => 'nullable|integer',
        ];
        $message = [
            'name.required' => '用户名是必填项',
            'email.required' => '邮箱地址是必填项',
        ];
        $validator = Validator::make($data, $rules, $message);
        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }

        $role = $request->input('role', ['user']);
        if ($role === null || $role == [])
        {
            $role = ['user'];
        }
        if (! is_array($role)) {
            $roles = json_decode($role, true);
            $data['role'] = implode(',', $roles);
        } else {
            $data['role'] = implode(',', $role);
        }
        $bool = User::where('id', $id)->update($data);
        if ($bool) {
            return $this->myResult(1,'更新成功！',null);
        }else{
            return $this->myResult(0,'更新失败！','可能的原因是用户名和邮件与其他人重复或者该ID不存在！');
        }

    }

    /**
     * @api {delete} /api/admin/:id  8.删除指定的管理员
     * @apiGroup 用户管理
     *
     * @apiSuccessExample 简要说明
     * HTTP/1.1 200 OK
     * 作为URL的ID参数必填，成功code为1，否则为0
     */

    public function destroy($id)
    {
        if($id==1){
            return $this->myResult(0,'不允许删除管理员帐户！',null);
        }
        $user = User::find($id);
        if(!$user)
        {
            return $this->myResult(0,'删除失败,未找到该用户！',null);
        }
        $rs=DB::select('select ((select COALESCE(count(*),0) from usertasks where user_id=?)+'.
            '(select COALESCE(count(*),0) from userpays where object_id=? and object_type=?)) as cs',[$id,$id,'人员']);
        if($rs[0]->cs > 0){
            return $this->myResult(0,'删除失败,已经有任务记录或者支出记录的用户不允许被删除！',null);
        }
        if ($user->delete()) {
            return $this->myResult(1,'删除成功！',null);
        } else {
            return $this->myResult(0,'删除失败！',null);
        }

    }

    /**
     * @api {post} /api/admin/:id/reset  93.设置指定的管理员的密码
     * @apiGroup 用户管理
     * @apiSuccessExample 简要说明
     * 1、必填参数 password 用户新密码
     * 2、必填参数 password_confirmation 重复新密码
     * 3、作为URL部分的ID必填
     */
    public function reset(Request $request, $id)
    {
        $password = $request->input('password');
        $rules = [
            'password'=>'required|between:5,20|confirmed',
        ];
        $messages = [
            'required' => '密码不能为空',
            'between' => '密码必须是6~20位之间',
            'confirmed' => '新密码和确认密码不匹配'
        ];
        $validator = Validator::make($request->input(), $rules, $messages);
        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }
        $user = User::find($id);
        if($user){
            $user->password = bcrypt($password);
            $user->save();
            return $this->myResult(1,'密码修改成功！',null);
        }
        return $this->myResult(0,'操作失败！','可能的原因是ID没找到！');
    }

    /**
     * @api {post} /api/admin/uploadAvatar  9.头像图片上传
     * @apiGroup 用户管理
     * @apiHeaderExample {json} http头部请求:
     *     {
     *       "content-type": "application/form-data"
     *     }
     * 返回情况请看postman调试结果,上传成功后返回图片的URL，该地址可作为用户创建或更新接口的avatar参数
     */

    public function uploadAvatar(Request $request)
    {
        if ($request->isMethod('POST')) {
//            var_dump($_FILES);
            $file = $request->file('photo');
            //判断文件是否上传成功
            if ($file->isValid()) {
                //获取原文件名
                $originalName = $file->getClientOriginalName();
                //扩展名
                $ext = $file->getClientOriginalExtension();
                //文件类型
                $type = $file->getClientMimeType();
                //临时绝对路径
                $realPath = $file->getRealPath();

                $filename = date('YmdHiS') . uniqid() . '.' . $ext;

                $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));
                if ($bool) {
                    $filename = 'uploads/' . $filename;
                    return $this->myResult(1,'上传成功！',['url' => $filename]);
                } else {
                    return $this->myResult(0,'上传失败！',null);
                }
            }
        }
    }

    /**
     *  @api {post} /api/admin/modify 92.登录用户修改密码
     * @apiGroup 用户管理
     * @apiHeaderExample 简要说明
     * 1、必选参数
     * oldPassword  原来密码
     * password 新密码
     * password_confirmation 重复新密码
     * 2、只有登录后才能修改自己的密码，系统自动判断当前登录用户
     */
    public function modify(Request $request)
    {
        $oldPassword = $request->input('oldPassword');
        $password = $request->input('password');
        $data = $request->all();
        $rules = [
            'oldPassword'=>'required|between:5,20',
            'password'=>'required|between:5,20|confirmed',
        ];
        $messages = [
            'required' => '密码不能为空',
            'between' => '密码必须是6~20位之间',
            'confirmed' => '新密码和确认密码不匹配'
        ];
        $validator = Validator::make($data, $rules, $messages);
        $user = Auth::user();
        $validator->after(function($validator) use ($oldPassword, $user) {
            if (!\Hash::check($oldPassword, $user->password)) {
                $validator->errors()->add('oldPassword', '原密码错误');
            }
        });
        if ($validator->fails()) {
            return $this->myResult(0,'密码修改失败！',$validator->errors()->all());
        }
        $user->password = bcrypt($password);
        if ($user->save()) {
            return $this->myResult(1,'密码修改成功！',null);
        } else {
            return $this->myResult(0,'密码修改失败！','系统未知错误');
        }
        
    }

    /**
     * @api {get} /api/user 91.获取当前登录的用户信息
     * @apiGroup 用户管理
     *
     *
     * @apiSuccessExample 信息获取成功
     * HTTP/1.1 200 OK
     *{
     * "data": {
     *    "id": 1,
     *    "name": "xxx",
     *    "email": "xxx@qq.com",
     *    "roles": "xxx", //角色: admin或者editor
     *    "avatar": ""
     *  },
     *  "status": "success",
     *  "status_code": 200
     *}
     */
    public function getUserInfo(Request $request)
    {
        // 获取用户信息和用户组对应的用户权限
        // 用户权限
        $user = $request->user();
        $roles = explode(',',$user['role']);
        $data = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'avatar' => $user['avatar'],
            'phone_number'=>$user['phone_number'],
            'sex'=>$user['sex'],
            'state'=>$user['state'],
            'birthday'=>$user['birthday'],
            'work_time'=>$user['work_time'],
            'card_type'=>$user['card_type'],
            'card_number'=>$user['card_number'],
            'duty'=>$user['duty'],
            'level'=>$user['level'],
            'from'=>$user['from'],
            'fix_salary'=>$user['fix_salary'],
            'work_salary'=>$user['work_salary'],
            'extra_salary'=>$user['extra_salary'],
            'family_address'=>$user['family_address'],
            'personal_address'=>$user['personal_address'],
            'remark'=>$user['remark'],
            'role' => $roles
        ];
        // 用户权限
        $feature = \App\Models\Role::whereIn('name',$roles)->pluck('permission');
        $feature = $feature->toArray();
        $strPermission = implode(',', $feature);
        $permissions = explode(',', $strPermission);
        $feature = Permission::select(['route_name', 'method', 'route_match', 'id'])->whereIn('id',$permissions)->get();
        $feature = $feature->toArray();
        $data['permission'] = $feature;
        return $this->myResult(1,'信息获取成功！',$data);
    }

    public function upload(UserImport $import)
    {
        $bool = $import->handleImport($import);
        if ($bool) {
            return $this->success();
        } else {
            return $this->error();
        }
    }


    protected  function queryData($pageSize = null, $page = 1, $name, $email){
        // 查询条件  根据姓名或者电话号码进行查询
        $offset = $pageSize * ($page - 1) == 0? 0: $pageSize * ($page - 1);
        $model = $this->getModel();
        $lists = $model::select('name', 'email', 'role')
                       ->name()
                       ->email()
                       ->when($pageSize,function($query) use($offset, $pageSize) {
                              return $query->offset($offset)->limit($pageSize);
                       })
                       ->get();

        return $lists;
    }

    /**
     * 根据传入的数据生成内容
     * @param $data
     * @return array
     */
    protected function generatorData($data): array
    {
        $items = [];
        // $data = $data['data'];  // 数据库中的数据
        $arrRoles = Role::pluck('explain', 'name')->all();
        foreach ($data as $item) {
            $arr = [];
            $arr['name'] = $item['name'];
            $arr['email'] = $item['email'];
            $tmpRoles = explode(',', $item['role']);
            $strRoles = '';
            foreach ($tmpRoles as $tmp) {
              $strRoles .= $arrRoles[$tmp].',';
            }
            $arr['role'] = substr($strRoles,0, -1);
            array_push($items, $arr);
        }
        array_unshift($items, ['姓名', '登录名', '角色']);
        return $items;
    }

    public function test()
    {
        $str = 'abacde,';
        dump(substr($str,0,-1));
    }

    public function deleteAll()
    {
        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => '演示功能，暂时不提供批量删除功能'
        ], 200);
    }

    protected function  getExportFile()
    {
        // 导出文件的名称
        return '用户管理';
    }

    protected function getModel()
    {
        // 当前控制器所对应的模型
        return 'App\Models\User';
    }

    /**
     *  @api {get} /api/getIssues 94.获取建议内容列表
     * @apiGroup 用户管理
     * @apiHeaderExample 简要说明
     * type 类型
     * title 标题
     * start_time,end_time 日期格式
     */
    public function getIssues(Request $request)
    {
        //开始输入校验
        $validator = Validator::make( $request->all(), [
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);
        if ($validator->fails()) {
            return $this->myResult(0,'操作失败，参数不符合要求！',$validator->errors()->all());
        }

        $pageSize = (int)$request->input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:10;

        $type=$request->input('type');
        $title=$request->input('title');
        $start_time=new Carbon($request->input('start_time'));
        $end_time=new Carbon($request->input('end_time'));

        $rs=DB::table('issues')->where('created_at','>=',$start_time->startOfDay())
                ->where('created_at','<=',$end_time->endOfDay());
        if($type)
            $rs=$rs->where('type','like','*'.$type.'*');
        if($title)
            $rs=$rs->where('title','like','*'.$title.'*');

        return $this->myResult(1,'信息获取成功！',$rs->orderby('id','desc')->paginate($pageSize));
    }

    /**
     *  @api {post} /api/setIssues 95.更新建议内容
     * @apiGroup 用户管理
     * @apiHeaderExample 简要说明
     * context 字符串内容
     * id 具体某一条的ID，大于0表示更新，小于1表示新增
     * type 类型，字符串内容，不大于50长度
     * title 标题，字符串内容，不大于50长度
     */
    public function setIssues(Request $request)
    {
        $id=$request->input('id',0);
        $type=$request->input('type','默认类型');
        $title=$request->input('title','新日志');
        $context = $request->input('context','爱你哦！');
        if($id>0){
            DB::update('update issues set context = ?,type=?,title=?,updated_at=NOW()',[$context,$type,$title]);
            return $this->myResult(1,'信息更新成功！',DB::select('select * from issues WHERE id=?',[$id]));
        }else{
            DB::insert('insert into issues (context,created_at,type,title,updated_at) values(?,NOW(),?,?,NOW())',[$context,$type,$title]);
            return $this->myResult(1,'信息更新成功！',DB::select('select * from issues order by id DESC LIMIT 1'));
        }
    }
}
