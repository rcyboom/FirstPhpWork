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
        $rs = $request->user();
        if ($rs && strpos($rs->role,'admin') !== false) {
            return $next($request);
        }else{
            return $this->myResult(0,'权限不足！',null);
        }
    }
}
