<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Result;
use Closure;

class CheckAdmin
{
    use Result;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //1、因为我前面没有指定其类型为\Illuminate\Http\Request，为什么$request有这个user方法？
        //2、获取后，为什么rs会有个role属性？
        $rs = $request->user();
        if ($rs && strpos($rs->role,'admin') !== false) {
            return $next($request);
        }else{
            return $this->myResult(0,'权限不足！',null);
        }
    }
}
