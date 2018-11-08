<?php

use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', 'UserController@getUserInfo')->name('admin.userInfo');
Route::post('/login', 'Auth\LoginController@login')->name('login.login');
Route::post('/loginWithThree', 'Auth\LoginController@loginWithThree')->name('login.loginWithThree');
Route::post('/token/refresh', 'Auth\LoginController@refresh')->name('login.refresh');
Route::post('/logout', 'Auth\LoginController@logout')->name('login.logout');

//管理员路由
Route::middleware('auth:api','checkAdmin')->group(function() {
    Route::any('/testapi', 'AccountController@test')->name('api.test');
    //日志路由
    Route::get('getIssues', 'UserController@getIssues')->name('getIssues.getIssues');
    Route::post('setIssues', 'UserController@setIssues')->name('setIssues.setIssues');
    // 用户管理
    Route::Resource('admin', 'UserController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::post('/admin/modify', 'UserController@modify' )->name('admin.modify');
    Route::post('/admin/{id}/reset', 'UserController@reset')->name('admin.reset');
    Route::post('/admin/uploadAvatar', 'UserController@uploadAvatar')->name('admin.uploadAvatar');
    Route::post('/admin/upload', 'UserController@upload')->name('admin.upload');
    Route::post('/admin/export', 'UserController@export')->name('admin.export');
    Route::post('/admin/exportAll', 'UserController@exportAll')->name('admin.exportAll');
   // Route::post('/admin/deleteAll', 'UserController@deleteAll')->name('admin.deleteAll');

    // 角色管理
    Route::Resource('role', 'RoleController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::get('getRoles', 'RoleController@getRoles')->name('role.get');

    // 程序功能管理
    Route::Resource('permissions', 'PermissionController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::post('/permissions/addGroup', 'PermissionController@addGroup')->name('permissions.addGroup');
    Route::post('/permissions/getGroup', 'PermissionController@getGroup')->name('permissions.getGroup');
    Route::post('/permissions/deleteAll', 'PermissionController@deleteAll')->name('permissions.deleteAll') ;
    Route::post('/permissions/getPermissionByTree', 'PermissionController@getPermissionByTree')->name('Permission.getPermissionByTree');

    // 手机信息管理
    //Route::post('/sms/send', 'SmsController@send')->name('sms.send');
    //Route::post('/sms/verify', 'SmsController@verify')->name('sms.verify');

    // 车辆信息管理
    Route::get('cars/index', 'CarController@index')->name('cars.index');
    Route::get('cars/carNumberList', 'CarController@carNumberList')->name('cars.carNumberList');
    Route::get('cars/{id}', 'CarController@getCar')->name('cars.getCar');
    Route::delete('cars/{id}', 'CarController@destroy')->name('cars.delete');
    Route::post('cars/{id?}', 'CarController@saveCar')->name('cars.saveCar');


    // 客户信息管理
    Route::get('customers/index', 'CustomerController@index')->name('customers.index');
    Route::get('customers/customersList', 'CustomerController@customersList')->name('customers.customersList');
    Route::get('customers/{id}', 'CustomerController@getone')->name('customers.getOne');
    Route::delete('customers/{id}', 'CustomerController@destroy')->name('customers.delete');
    Route::post('customers/{id?}', 'CustomerController@saveone')->name('customers.saveOne');

    //任务管理
    Route::get('tasks/index', 'TaskController@index')->name('tasks.index');
    Route::get('tasks/getStateList', 'TaskController@getStateList')->name('tasks.getStateList');
    Route::post('tasks/create', 'TaskController@create')->name('tasks.create');
    Route::post('tasks/update', 'TaskController@update')->name('tasks.update');
    Route::post('tasks/delete', 'TaskController@delete')->name('tasks.delete');

    Route::get('tasks/FreeCars', 'TaskController@FreeCars')->name('tasks.FreeCars');
    Route::post('tasks/addTaskCar', 'TaskController@addTaskCar')->name('tasks.addTaskCar');
    Route::get('tasks/TaskCars', 'TaskController@TaskCars')->name('tasks.TaskCars');
    Route::post('tasks/delTaskCar', 'TaskController@delTaskCar')->name('tasks.delTaskCar');

    Route::get('tasks/FreeMans', 'TaskController@FreeMans')->name('tasks.FreeMans');
    Route::post('tasks/addTaskMan', 'TaskController@addTaskMan')->name('tasks.addTaskMan');
    Route::get('tasks/TaskMans', 'TaskController@TaskMans')->name('tasks.TaskMans');
    Route::post('tasks/delTaskMan', 'TaskController@delTaskMan')->name('tasks.delTaskMan');

    // 员工奖惩
    Route::get('userpays/index', 'UserPayController@index')->name('userpays.index');
    Route::get('userpays/{id}', 'UserPayController@getOne')->name('userpays.getOne');
    Route::post('userpays/delete', 'UserPayController@delete')->name('userpays.delete');
    Route::post('userpays/{id?}', 'UserPayController@saveOne')->name('userpays.saveOne');
    // 财务管理
    Route::get('accounts/index', 'AccountController@index')->name('accounts.index');
    Route::get('accounts/getone', 'AccountController@getone')->name('accounts.getone');
    Route::post('accounts/saveone', 'AccountController@saveone')->name('accounts.saveone');
    Route::post('accounts/delete', 'AccountController@delete')->name('accounts.delete');
    Route::post('accounts/accounttask', 'AccountController@accounttask')->name('accounts.accounttask');
    Route::post('accounts/accountcar', 'AccountController@accountcar')->name('accounts.accountcar');
    Route::post('accounts/accountuser', 'AccountController@accountuser')->name('accounts.accountuser');

});
// 员工路由
Route::middleware('auth:api')->group(function() {
    Route::get('employees/index', 'EmployeeController@index')->name('employees.index');
    Route::get('employees/tasks', 'EmployeeController@tasks')->name('employees.tasks');
    Route::get('employees/pays', 'EmployeeController@pays')->name('employees.pays');
    Route::get('employees/accounts', 'EmployeeController@accounts')->name('employees.accounts');
});
