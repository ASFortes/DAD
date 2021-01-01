<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsDeliveryMan
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
        return Auth::user()->type == 'ED' ? $next($request) : response()->json(['error'=>"Only delivery mans can perform this action."], 401);
    }
}