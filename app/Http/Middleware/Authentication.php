<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class Authentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $requestType
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next, $requestType='platform')
    {
        if(! Gate::allows($requestType)){
            throw new \Exception('当前访问未授权！');
        }

        return $next($request);
    }
}
