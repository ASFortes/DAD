<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\OrderItems;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Http\Resources\Order as OrderResource;
use App\Models\Order;
use App\Models\Customer;

class OrderController extends Controller
{
        public function getAllOrders()//o cozinheiro vai buscar todas as orders em holding
        {
      
                $orders=Order::where('status','H')->get(); 
        
                return response()->json($orders, 201);

        }
        public function getOrders($id)
        {
      
                $orders=Order::where('customer_id', $id)->whereIn('status',['H','P','R','T'])->get(); 
        
                return response()->json($orders, 201);

        }
        public function getOrdersNot($id)
        {
      
                $orders=Order::where('customer_id', $id)->whereIn('status',['D','C'])->get(); 
        
                return response()->json($orders, 201);

        }
        
        public function getOrdersUncooked($id)
        {
      
                $orders=Order::where('customer_id', $id)->whereIn('status','H')->get(); 
        
                return response()->json($orders, 201);

        }

        public function getCookOrders($id)
        {
                $orders = Order::where('prepared_by', $id)->where('status', 'P')->get();
                for($i=0;$i<count($orders);$i++){
                        $user[$i] = User::findOrFail($orders[$i]->customer_id);
                };
                for($i=0;$i<count($orders);$i++){
                        $orders[$i]->customer_name=$user[$i]['name'];
                };

                return response()->json($orders, 201);
        }
     

        public function getCookOrdersInProgress($id)
        {
                $orders = Order::where('prepared_by', $id)->where('status','P')->get();
                for($i=0;$i<count($orders);$i++){
                        $user[$i] = User::findOrFail($orders[$i]->customer_id);
                };
                for($i=0;$i<count($orders);$i++){
                        $orders[$i]->customer_name=$user[$i]['name'];
                };

                return response()->json($orders, 201);
        }

        public function assignCook($id,$idOrder)
        {
                $order= Order::find($idOrder);
                $order->prepared_by=$id;
                $order->status='P';
                $order->current_status_at=date('Y-m-d H:i:s');
                $order->save();
                

                return response()->json($order, 201);
        }




    //
    public function storeOrder(Request $request)
        {           
                
                
                $user_id=Auth::user()->id;
                $order = new Order();
                
                $order->status='H';
                $order->notes=$request['notes'];
                $order->customer_id=$user_id;
                $total=0;

                if(count($request['products'][0])==0){
                        return response()->json(['error'=>"O carrinho está vazio"], 400);
                };
               
                for($i=0;$i<count($request['products']);$i++){
                    $produto_id[$i]=$request['products'][$i]['product']['id'];
                    $quantidade[$i]=$request['products'][$i]['quantity'];
                 };
                     
                
                $products=Product::whereIn('id',$produto_id)->get('price');
                $products1=Product::whereIn('id',$produto_id)->get('name');
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
                       // $order_Items->product_name=$products1[$i];

                        $order_Items->save();

                }
               
                

               

                return response()->json($order, 201);
        }



        //////////GETTER PARA O DELIVERYMAN
        public function getDeliveryManOrders()
        {
                //$orders=Order::where('customer_id', $id)->where('status','R')->get();                 
                //$orders = Order::where('delivered_by', $id)->where('status', 'R')->get();
                $orders=Order::where('status','R')->get(); 
                for($i=0;$i<count($orders);$i++){
                        $user[$i] = User::findOrFail($orders[$i]->customer_id);
                        $user1[$i] = Customer::findOrFail($orders[$i]->customer_id);
                        
                        
                        
                };
                for($i=0;$i<count($orders);$i++){
                        $orders[$i]->customer_name=$user[$i]['name'];
                        $orders[$i]->customer_address=$user1[$i]['address'];
                };

                return response()->json($orders, 201);
        }


        ////atribuir deliveryMan 
        public function assignDeliveryMan($id,$idOrder)
        {
                $order= Order::find($idOrder);
                $order->prepared_by=$id;
                $order->status='R';
                $order->current_status_at=date('Y-m-d H:i:s');
                $order->save();
                

                return response()->json($order, 201);
        }

     

}
