<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class OrderController extends Controller
{
    //
    public function storeOrder(Request $request)
        {           
           
                $user_id=Auth::user()->id;
                $order = new Order();
                
                $order->status='H';
                $order->notes=$request['notes'];
                $order->customer_id=$user_id;
                $total=0;
                
                for($i=0;$i<count($request['products'][0]);$i++){
                    $produto_id[$i]=$request['products'][$i]['product']['id'];
                    $quantidade[$i]=$request['products'][$i]['quantity'];
                 };
                     
                
                $products=Product::whereIn('id',$produto_id)->get('price');
                
                for($i=0;$i<count($products);$i++){
                        $total=$total+$products[$i]['price']*$quantidade[$i];
                 };
                
                $order->total_price=$total;
                $order->date=date('Y-m-d');
                $order->opened_at=date('Y-m-d H:i:s');
                $order->current_status_at=date('Y-m-d H:i:s');
                $order->updated_at=date('Y-m-d H:i:s');
                $order->save();
                //return response()->json($order->id, 201);
                
                for($i=0;$i<count($produto_id);$i++){
                        $order_Items= new OrderItems();
                        $order_Items->product_id=$produto_id[$i];
                        $order_Items->quantity=$quantidade[$i];
                        $order_Items->unit_price=$products[$i]['price'];
                        $order_Items->sub_total_price=$quantidade[$i]*$products[$i]['price'];
                        $order_Items->order_id=$order->id;
                        $order_Items->save();

                }
               
                

               

                return response()->json($order, 201);
        }
}
