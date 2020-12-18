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
            
         
            $ordersItems=OrderItems::where('order_id', $id)->get();
            $ordersItemsIDs=OrderItems::where('order_id', $id)->get('product_id');
            $products=Product::whereIn('id',$ordersItemsIDs)->get('name');

             for($i=0;$i<count($ordersItems);$i++){
                $ordersItems[$i]->product_name=$products[$i]['name'];
             };
            

                return response()->json($ordersItems, 201);
               
                

    }
}
