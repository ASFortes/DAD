<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemsController;


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
//USERS
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->get('users/me', [UserController::class, 'me']);
Route::middleware('auth:sanctum')->post('profile/photo', [UserController::class, 'uploadPhoto']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('register', [UserController::class, 'store']);
Route::post('update', [UserController::class, 'update']);
Route::put('password',[UserController::class, 'changePassword']);


//PRODUCTS
Route::get('fotos/{filename}', function ($filename)
{
    $path = storage_path('app/public/products/' . $filename);

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->name('fotos_url');
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'showProduct']);



//ORDERS

Route::put('assignCook/{id}/{idOrder}', [OrderController::class, 'assignCook']);
Route::post('orderStore', [OrderController::class, 'storeOrder']);
Route::get('orders', [OrderController::class, 'getAllOrders']);//orders a espera 'H'
Route::get('orders/{id}', [OrderController::class, 'getOrders']);//orders por entregar
Route::get('ordersNot/{id}', [OrderController::class, 'getOrdersNot']);//orders cutomer entregues
Route::get('ordersUncooked', [OrderController::class, 'getOrdersUncooked']);//orders para o cozinheiro
Route::get('cookOrders/{id}', [OrderController::class, 'getCookOrders']);//orders do cozinheiro
Route::get('cookOrdersInProgress/{id}', [OrderController::class, 'getCookOrdersInProgress']);//orders do cozinheiro em progresso


//ORDER ITEMS 


Route::get('orderItems/{id}', [OrderItemsController::class, 'getOrderItems']);