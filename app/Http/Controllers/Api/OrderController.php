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
                //return response()->json($orders, 201);
                for($i=0;$i<count($orders);$i++){
                        $user[$i] = User::findOrFail($orders[$i]->customer_id);
                        $userCustomer[$i] = Customer::findOrFail($orders[$i]->customer_id);
                };
                for($i=0;$i<count($orders);$i++){
                        $orders[$i]->customer_name=$user[$i]['name'];
                        $orders[$i]->customer_address=$userCustomer[$i]['address'];
                        $orders[$i]->customer_phone=$userCustomer[$i]['phone'];
                        $orders[$i]->customer_email=$user[$i]['email'];
                        $orders[$i]->customer_photo=$user[$i]['photo_url'];
                };

                return response()->json($orders, 201);
        }
     
        
        public function getCookOrdersInProgress($id)
        {
                $orders = Order::where('prepared_by', $id)->where('status','P')->get();
                for($i=0;$i<count($orders);$i++){
                        $user[$i] = User::findOrFail($orders[$i]->customer_id);
                        $userCustomer[$i] = Customer::findOrFail($orders[$i]->customer_id);
                };
                for($i=0;$i<count($orders);$i++){
                        $orders[$i]->customer_name=$user[$i]['name'];
                        $orders[$i]->customer_address=$userCustomer[$i]['address'];
                        $orders[$i]->customer_phone=$userCustomer[$i]['phone'];
                        $orders[$i]->customer_email=$user[$i]['email'];
                        $orders[$i]->customer_photo=$user[$i]['photo_url'];
                };

                return response()->json($orders, 201);
        }

        public function assignCook($id)
        {

              

                $user=User::find($id);
              //  $user->available_at=date('Y-m-d H:i:s');
                $orderHold= Order::where('status','H')->orderBy('opened_at')->first();
                
                if(empty($orderHold)){
                        $user->available_at=date('Y-m-d H:i:s');
                        $user->save();
                        return response()->json( [], 201);
                }
               
                $cookOrdersInProgress = Order::where('prepared_by', $id)->where('status','P')->count();
                if(empty($cookOrdersInProgress) && !empty($orderHold)){
                $orderHold->prepared_by=$id;
                $orderHold->status='P';
                $orderHold->current_status_at=date('Y-m-d H:i:s');
                $orderHold->save();
                $user->available_at=null;
                
                }
                $user->save();
                
                return response()->json($orderHold, 201);
                
                
        }
        public function assignOnlineCook($id)
        {
               
                $cooker=User::where('available_at','<>',null)->where('type','EC')->orderBy('available_at','asc')->first();
                  
                if(empty($cooker)){
                 return response()->json( [], 201);
                }
               
                $order= Order::find($id);
                $cooker->available_at=null;
                $order->prepared_by=$cooker->id;
                $order->status='P';
                $order->current_status_at=date('Y-m-d H:i:s');
                $order->save();
                $cooker->save();

               
               
                        $user = User::findOrFail($order->customer_id);
               
                
                        $order->customer_name=$user['name'];
                
                
                return response()->json($order, 201);
         
        }
      

        public function changeOrderPtoR($id)
        {
                
                $order= Order::find($id);
                $order->status='R';
                $order->preparation_time= strtotime(date('Y-m-d H:i:s')) - strtotime($order->current_status_at);
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
                $deliveryMan_id = Auth::user()->id;
                $orderInTransit = Order::where('status','T')->where('delivered_by',$deliveryMan_id)->get(); 
                //return response()->json($orderInTransit, 201);
               
                //return response()->json(empty($orderInTransit[0]), 201);
               
                if(empty($orderInTransit[0])){

                
                $orders=Order::where('status','R')->get(); 
                for($i=0;$i<count($orders);$i++){
                        $user[$i] = User::findOrFail($orders[$i]->customer_id);
                        $userCustomer[$i] = Customer::findOrFail($orders[$i]->customer_id);
                        
                        
                        
                };
                for($i=0;$i<count($orders);$i++){
                        $orders[$i]->customer_name=$user[$i]['name'];
                        $orders[$i]->customer_address=$userCustomer[$i]['address'];
                        $orders[$i]->customer_phone=$userCustomer[$i]['phone'];
                        $orders[$i]->customer_email=$user[$i]['email'];
                        $orders[$i]->customer_photo=$user[$i]['photo_url'];
                };
                


                return response()->json($orders, 201);
                 }else{
                         $user = User::findOrFail($orderInTransit[0]->customer_id);
                         $userCustomer = Customer::findOrFail($orderInTransit[0]->customer_id);

                        $orderInTransit[0]->customer_name=$user['name'];
                        $orderInTransit[0]->customer_address=$userCustomer['address'];
                        $orderInTransit[0]->customer_phone=$userCustomer['phone'];
                        $orderInTransit[0]->customer_email=$user['email'];
                        $orderInTransit[0]->customer_photo=$user['photo_url'];
                         return response()->json($orderInTransit, 201);
                 }
        }


        ////atribuir deliveryMan 
        public function assignDeliveryMan($id)
        {       
                $userWorker=User::where('available_at','<>',null)->where('type','ED')->first();
                $user_id = Auth::user()->id;
                $order= Order::find($id);
                $user = User::findOrFail($order->customer_id);
                $userCustomer = Customer::findOrFail($order->customer_id);
                $order->delivered_by=$user_id;
                $order->status='T';
                $order->current_status_at=date('Y-m-d H:i:s');
                $userWorker->available_at=null;
                //$order->$this->changeOrderRtoT($id);
                
               

                
                $order->save();
                $userWorker->save();

                $order->customer_name=$user['name'];
                $order->customer_address=$userCustomer['address'];
                $order->customer_phone=$userCustomer['phone'];
                $order->customer_email=$user['email'];
                $order->customer_photo=$user['photo_url'];


                
                

                return   response()->json($order, 201); //&& $this->changeOrderRtoT($id)
        }

        public function changeOrderRtoT($id)
        {
                
                $order= Order::find($id);
                $order->status='T';
                //$order->preparation_time= strtotime(date('Y-m-d H:i:s')) - strtotime($order->current_status_at);
                $order->current_status_at=date('Y-m-d H:i:s');
                $order->save();
                

                return 
                response()->json($order, 201);
        }


        public function changeOrderTtoD($id)
        {
                
                $order= Order::find($id);
                $userWorker=User::where('available_at',null)->where('type','ED')->where('id',$order->delivered_by)->first();
                
                $order->status='D';
                $order->delivery_time= strtotime(date('Y-m-d H:i:s')) - strtotime($order->current_status_at);
                $order->current_status_at=date('Y-m-d H:i:s');
                $order->closed_at=date('Y-m-d H:i:s');
                $userWorker->available_at=date('Y-m-d H:i:s');
                $order->save();
                $userWorker->save();
                

                return response()->json($order, 201);
        }


        public function getDeliveryOrdersInProgress($id)
        {
                $orders = Order::where('delivered_by', $id)->where('status','T')->get();
                for($i=0;$i<count($orders);$i++){
                        $user[$i] = User::findOrFail($orders[$i]->customer_id);
                };
                for($i=0;$i<count($orders);$i++){
                        $orders[$i]->customer_name=$user[$i]['name'];
                };

                return response()->json($orders, 201);
        }



        public function changeOrdertoC($id)
        {
                
                $order= Order::find($id);
               // $userWorker=User::where('available_at',null)->where('type','ED')->where('id',$order->delivered_by)->first();
                
                $order->status='C';
                //$order->delivery_time= strtotime(date('Y-m-d H:i:s')) - strtotime($order->current_status_at);
                $order->current_status_at=date('Y-m-d H:i:s');
                $order->updated_at=date('Y-m-d H:i:s');
                $order->closed_at=date('Y-m-d H:i:s');
                //$userWorker->available_at=date('Y-m-d H:i:s');
                $order->save();
               // $userWorker->save();
                

                return response()->json($order, 201);
        }


     

}
