<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\OrderItemsController;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
class OrderItemsController extends Controller
{
    //

    public function getOrderItems($id)
    {    
            
            // $products=Product::whereIn('id',[2,3])->get('price');
            // return response()->json($products, 201);
            $ordersItems=OrderItems::where('order_id', $id)->get();
            $ordersItemsIDs=OrderItems::where('order_id', $id)->get('product_id');
            $products=Product::whereIn('id',$ordersItemsIDs)->get('name');
          //
         
            // for($i=0;$i<count($ordersItems);$i++){
            //     $ordersItems[$i]->product_name=$products[$i];
            //  };
            

                return response()->json($ordersItems, 201);
               
                
         
            // foreach($orderItems as $ordersItems){
            //    // $orderItemsNames=Order::where('id',$item->product_id)->get();
            //     $products=Product::whereIn('id',$item->product_id)->get('name');
            // }
            // return response()->json($products, 201);
            // return response()->json($ordersItems, 201);

    }
}
