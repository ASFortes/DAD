<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
 public function me(Request $request)
 {
    //return $request->user();
    return Auth::user();
 }
}