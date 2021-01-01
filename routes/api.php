<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemsController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Middleware\IsManager;
use App\Http\Middleware\IsCooker;
use App\Http\Middleware\IsDeliveryMan;
use App\Http\Middleware\IsCustomer;


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
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
Route::post('register', [UserController::class, 'store']);
Route::middleware('auth:sanctum')->post('update', [UserController::class, 'update']);
Route::middleware('auth:sanctum')->put('password',[UserController::class, 'changePassword']);
Route::middleware('auth:sanctum')->put('userUnavailable/{id}', [UserController::class, 'updateUserToUnavailable']);
Route::middleware('auth:sanctum')->get('users', [UserController::class, 'users'])->middleware("manager");
Route::middleware('auth:sanctum')->get('usersEmp', [UserController::class, 'usersEmp'])->middleware("manager");
Route::middleware('auth:sanctum')->post('managerUpdateUsers', [UserController::class, 'managerUpdateUsers'])->middleware("manager");
Route::middleware('auth:sanctum')->post('managerCreateUser', [UserController::class, 'managerCreateUser'])->middleware("manager");
Route::middleware('auth:sanctum')->put('deleteUser/{id}/{type}', [UserController::class, 'deleteUser'])->middleware("manager");
Route::middleware('auth:sanctum')->put('blockUser/{id}', [UserController::class, 'blockUser'])->middleware("manager");
Route::middleware('auth:sanctum')->put('unblockUser/{id}', [UserController::class, 'unblockUser'])->middleware("manager");
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
Route::middleware('auth:sanctum')->post('updateProducts', [ProductController::class, 'updateProducts'])->middleware("manager");
Route::middleware('auth:sanctum')->post('newProduct', [ProductController::class, 'newProduct'])->middleware("manager");
Route::middleware('auth:sanctum')->put('deleteProduct/{id}', [ProductController::class, 'deleteProduct'])->middleware("manager");


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
Route::put('changeOrdertoC/{id}', [OrderController::class, 'changeOrdertoC']);
Route::middleware('auth:sanctum')->put('assignCook/{id}', [OrderController::class, 'assignCook'])->middleware("cooker");
Route::middleware('auth:sanctum')->post('orderStore', [OrderController::class, 'storeOrder'])->middleware("customer");;
Route::middleware('auth:sanctum')->get('orders', [OrderController::class, 'getAllOrders'])->middleware("cooker");//orders a espera 'H'
Route::middleware('auth:sanctum')->get('orders/{id}', [OrderController::class, 'getOrders']);//orders por entregar
Route::middleware('auth:sanctum')->get('ordersNot/{id}', [OrderController::class, 'getOrdersNot']);//orders cutomer entregues
Route::middleware('auth:sanctum')->get('ordersUncooked', [OrderController::class, 'getOrdersUncooked'])->middleware("cooker");//orders para o cozinheiro
Route::middleware('auth:sanctum')->get('cookOrders/{id}', [OrderController::class, 'getCookOrders'])->middleware("cooker");//orders do cozinheiro
Route::middleware('auth:sanctum')->get('cookOrdersInProgress/{id}', [OrderController::class, 'getCookOrdersInProgress'])->middleware("cooker");//orders do cozinheiro em progresso
Route::middleware('auth:sanctum')->put('changeOrderPtoR/{id}', [OrderController::class, 'changeOrderPtoR'])->middleware("cooker");
Route::middleware('auth:sanctum')->put('assignOnlineCook/{id}', [OrderController::class, 'assignOnlineCook'])->middleware("cooker");

//ORDER ITEMS 


Route::middleware('auth:sanctum')->get('orderItems/{id}', [OrderItemsController::class, 'getOrderItems']);






//ORDERS DELIVERYMAN//////////////////////////////////////

Route::middleware('auth:sanctum')->get('deliveryManOrders/', [OrderController::class, 'getDeliveryManOrders'])->middleware("deliveryman");//orders do deliveryman
Route::middleware('auth:sanctum')->put('assignDeliveryMan/{id}', [OrderController::class, 'assignDeliveryMan'])->middleware("deliveryman");
Route::middleware('auth:sanctum')->put('changeOrderRtoT/{id}', [OrderController::class, 'changeOrderRtoT'])->middleware("deliveryman");
Route::middleware('auth:sanctum')->put('changeOrderTtoD/{id}', [OrderController::class, 'changeOrderTtoD'])->middleware("deliveryman");
Route::middleware('auth:sanctum')->get('getDeliveryOrdersInProgress/{id}', [OrderController::class, 'getDeliveryOrdersInProgress'])->middleware("deliveryman");//orders do deliveryman current



//STATISTICS
Route::middleware('auth:sanctum')->get('numberofusers', [StatisticsController::class, 'numberofusers'])->middleware("manager");
Route::middleware('auth:sanctum')->get('numberoforders', [StatisticsController::class, 'numberoforders'])->middleware("manager");
Route::middleware('auth:sanctum')->get('revenue', [StatisticsController::class, 'revenue'])->middleware("manager");
Route::middleware('auth:sanctum')->get('typeofcategory', [StatisticsController::class, 'typeofcategory'])->middleware("manager");
Route::middleware('auth:sanctum')->get('averageSpentCustomer', [StatisticsController::class, 'averageSpentCustomer'])->middleware("manager");
Route::middleware('auth:sanctum')->get('avgtime', [StatisticsController::class, 'avgtime'])->middleware("manager");
Route::middleware('auth:sanctum')->get('salespermonth', [StatisticsController::class, 'salespermonth'])->middleware("manager");
Route::middleware('auth:sanctum')->get('ordersPerMonthYear', [StatisticsController::class, 'ordersPerMonthYear'])->middleware("manager");


