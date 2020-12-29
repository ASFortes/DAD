<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;

class AuthController extends Controller
{
    public function login(Request $request)
    {
         
       // return $request
        //$user = User::find($user_id);

        //$order->current_status_at=date('Y-m-d H:i:s');
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if(Auth::user()->blocked==1){
                return response()->json(['message' => 'Your account is blocked.'], 403);
            }
            Auth::user()->logged_at = date('Y-m-d H:i:s');
            $order = Order::find(Auth::user()->id)->where('status','T')->first();
            if(Auth::user()->type == 'ED' && !empty($order)){
                Auth::user()->available_at = null;
            }else{
            Auth::user()->available_at = date('Y-m-d H:i:s');
            }
            Auth::user()->save();
            return Auth::user();
        } else {
            return response()->json(['message' => 'Invalid login credentials.'], 401);
        }
    }
    public function logout()
    {
        Auth::user()->logged_at =null;
        Auth::user()->available_at =null;
        Auth::user()->save();
        Auth::guard('web')->logout(); //check if Auth::logout(); works
        return response()->json(['msg' => 'User session closed'], 200);
    }
}
