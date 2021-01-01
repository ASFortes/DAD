<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsCooker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return Auth::user()->type == 'EC' ? $next($request) : response()->json(['error'=>"Only cookers can perform this action."], 401);
    }
}