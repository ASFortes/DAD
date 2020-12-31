<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsCustomer
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
        return Auth::user()->type == 'C' ? $next($request) : response()->json(['error'=>"Only customers can perform this action."], 401);
    }
}