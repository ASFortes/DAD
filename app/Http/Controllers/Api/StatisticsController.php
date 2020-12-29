<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\Customer;
use Hash;
use Illuminate\Support\Carbon;
use DB;
use Validator;
use App\Http\Resources\User as UserResource; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\StatsPerMonth as StatsPerMonth;


class StatisticsController extends Controller
{
    

    //stats sales per month year
    public function salespermonth()
    {
        return new StatsPerMonth(Order::find(444));
    }

    //stats orders per month year
    public function ordersPerMonthYear()
    {
        return new StatsPerMonth(Order::find(444));
    }

    

    public function numberofusers()
    {
        $userStats = [];
        $userStats['customers'] = User::where('type','C')->count();
        $userStats['cookers'] = User::where('type','EC')->count();
        $userStats['deliverers'] = User::where('type','ED')->count();
        $userStats['managers'] = User::where('type','EM')->count();
        $userStats['total'] = User::all()->count();
        // return response()->json( $userStats, 201);
        return $userStats;
    }

    public function numberoforders()
    {
        //return Movement::whereYear('date', '=', 2019)->get()->sum('value');
        return Order::where('date', '>=', date("Y-m-d", strtotime("-1 week")))->count();
    }

    public function revenue()
    {
        //return Movement::whereYear('date', '=', 2019)->get()->sum('value');
        return Order::where('date', '>=', date("Y-m-d", strtotime("-1 week")))->get()->sum('total_price');
    }

    public function averageSpentCustomer()
    {
        $aux = count(User::where('type','C')->get());
        return Order::all()->sum('total_price') / $aux;
    }
    
    public function typeofcategory(){
        $order_items = OrderItems::all();
        // return response()->json($order_items[0]->product_id, 201);
        // $product = Product::find($order_items[0]->product_id);
        //     return response()->json($product, 201);
        $drinks = 0;
        $cold = 0;
        $hot = 0;
        $dessert = 0;
        for ($i=0; $i < count($order_items); $i++) { 
            $product = Product::find($order_items[$i]->product_id);
            // return response()->json($product, 201);
            if ($product->type=='drink') {
                $drinks += $order_items[$i]->quantity;
            }
            if ($product->type=='hot dish') {
                $hot += $order_items[$i]->quantity;
            }
            if ($product->type=='dessert') {
                $dessert += $order_items[$i]->quantity;
            }
            if ($product->type=='cold dish') {
                $cold += $order_items[$i]->quantity;
            }

        }
        $typeOfCateg = [];
        $typeOfCateg['drink'] = $drinks;
        $typeOfCateg['hotdish'] = $hot;
        $typeOfCateg['dessert'] = $dessert;
        $typeOfCateg['colddish'] = $cold;
        return $typeOfCateg;
    }


    public function avgtime()
    {
        $vet_med = [];
        $num_orders = count(Order::all());
        $medPrep = Order::all()->sum('preparation_time') / $num_orders;
        $medDeliver = Order::all()->sum('delivery_time') / $num_orders;
        $vet_med['medPrep'] = $medPrep;
        $vet_med['medDeliver'] = $medDeliver;

        return $vet_med;
    }


}

