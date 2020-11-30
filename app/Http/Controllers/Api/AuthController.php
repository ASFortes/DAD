<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if(Auth::user()->blocked==1){
                return response()->json(['message' => 'Your account is blocked.'], 403);
            }
            return Auth::user();
        } else {
            return response()->json(['message' => 'Invalid login credentials.'], 401);
        }
    }
    public function logout()
    {
        Auth::guard('web')->logout(); //check if Auth::logout(); works
        return response()->json(['msg' => 'User session closed'], 200);
    }
}
