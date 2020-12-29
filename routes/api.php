<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemsController;
use App\Http\Controllers\Api\StatisticsController;


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
Route::put('userUnavailable/{id}', [UserController::class, 'updateUserToUnavailable']);
Route::get('users', [UserController::class, 'users']);
Route::post('managerUpdateUsers', [UserController::class, 'managerUpdateUsers']);
Route::post('managerCreateUser', [UserController::class, 'managerCreateUser']);
Route::put('deleteUser/{id}/{type}', [UserController::class, 'deleteUser']);
Route::put('blockUser/{id}', [UserController::class, 'blockUser']);
Route::put('unblockUser/{id}', [UserController::class, 'unblockUser']);

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
Route::post('updateProducts', [ProductController::class, 'updateProducts']);
Route::post('newProduct', [ProductController::class, 'newProduct']);
Route::put('deleteProduct/{id}', [ProductController::class, 'deleteProduct']);


//ORDERS

Route::put('assignCook/{id}', [OrderController::class, 'assignCook']);
Route::post('orderStore', [OrderController::class, 'storeOrder']);
Route::get('orders', [OrderController::class, 'getAllOrders']);//orders a espera 'H'
Route::get('orders/{id}', [OrderController::class, 'getOrders']);//orders por entregar
Route::get('ordersNot/{id}', [OrderController::class, 'getOrdersNot']);//orders cutomer entregues
Route::get('ordersUncooked', [OrderController::class, 'getOrdersUncooked']);//orders para o cozinheiro
Route::get('cookOrders/{id}', [OrderController::class, 'getCookOrders']);//orders do cozinheiro
Route::get('cookOrdersInProgress/{id}', [OrderController::class, 'getCookOrdersInProgress']);//orders do cozinheiro em progresso
Route::put('changeOrderPtoR/{id}', [OrderController::class, 'changeOrderPtoR']);
Route::put('assignOnlineCook/{id}', [OrderController::class, 'assignOnlineCook']);

//ORDER ITEMS 


Route::get('orderItems/{id}', [OrderItemsController::class, 'getOrderItems']);






//ORDERS DELIVERYMAN//////////////////////////////////////

Route::get('deliveryManOrders/', [OrderController::class, 'getDeliveryManOrders']);//orders do deliveryman
Route::put('assignDeliveryMan/{id}', [OrderController::class, 'assignDeliveryMan']);
Route::put('changeOrderRtoT/{id}', [OrderController::class, 'changeOrderRtoT']);
Route::put('changeOrderTtoD/{id}', [OrderController::class, 'changeOrderTtoD']);
Route::get('getDeliveryOrdersInProgress/{id}', [OrderController::class, 'getDeliveryOrdersInProgress']);//orders do deliveryman current



//STATISTICS
Route::get('numberofusers', [StatisticsController::class, 'numberofusers']);
Route::get('numberoforders', [StatisticsController::class, 'numberoforders']);
Route::get('revenue', [StatisticsController::class, 'revenue']);
Route::get('typeofcategory', [StatisticsController::class, 'typeofcategory']);
Route::get('averageSpentCustomer', [StatisticsController::class, 'averageSpentCustomer']);
Route::get('avgtime', [StatisticsController::class, 'avgtime']);


