<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use Hash;
use Validator;
use App\Http\Resources\User as UserResource; 




class UserController extends Controller

{

    public function index()
    {
        return ProductResource::collection(Product::all());
    }


 public function me(Request $request)
 {
    //return $request->user();
    $user=Auth::user();



    if($user->type=='C'){
    $customer_id=$user->id;
    $customer = Customer::findOrFail($customer_id);
    $user->address=$customer->address;
    $user->nif=$customer->nif;
    $user->phone=$customer->phone;
    }
   
    return response()->json($user, 201);
 }

 public function store(Request $request)
 {

     
   

     $validator = Validator::make($request->all(),[
              'name' => 'required|min:2|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
              'email' => 'required|email|unique:users,email',
              'password' =>'required|min:3',
              'address'=>'required|min:5',
              'phone'=>'required|numeric|min:9',
              'nif' => 'nullable|digits:9|unique:customers',
              'photo'=>'sometimes|required|mimes:jpeg,png|max:10000', 

          ]);
         

          if ($validator->fails()) {
             return response()->json(['error' =>$validator->errors()->first()],400);
          }
          
          $user = new User();
          $customer= new Customer();
           
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->password = $request['password'];
            // $user->photo_url=$request['photo'];
            $customer->nif = $request['nif'];
            $customer->address = $request['address'];
            $customer->phone = $request['phone'];

            $user->password = Hash::make($user->password);
            $user->blocked = 0;
            $user->type='C';

         

            
            //$request->file('photo')->move(storage_path('app/public/storage/fotos'), $path);
            
            $user->save();
            $customer->id=$user->id;
            // $path = $user->id . '.jpg';
            // $user->photo_url = $path;
            
            $customer->save();
            if ($request->hasfile('photo')) {
                
                $nov_nome = $user->id . "_" . time() . "." . $request->file('photo')->getClientOriginalExtension();
                Storage::putFileAs("fotos", $request->file('photo'), $nov_nome);//364
               // $request->file('photo')->move('app/public/storage/fotos', $nov_nome);362
            //    Storage::putFileAs('app/public/storage/fotos',$request->file('photo'),$nov_nome);
            //     $path = $request->file('photo')->storeAs(
            //         $nov_nome,
            //         $user->id,
            //         storage_path('app/public/storage/fotos')
            //     );
                
                
                $user['photo_url'] = $nov_nome;
            }
            $user->update();

           

           return response()->json($user, 201);
    // return response()->json( $user, 201);
 }

 public function update(Request $request)
 {

            $user_id = Auth::user()->id;
            
            $customer_id=$user_id;
            $user = User::find($user_id);
           


            
     $validator = Validator::make($request->all(),[
        'name' => 'required|min:2|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
        'email' => 'required|email|unique:users,email,'.$user_id,
        'password' =>'required|min:3',
        'address'=>'nullable|min:5',
        'phone'=>'nullable|numeric|min:9',
        'nif' => 'nullable|digits:9|unique:customers,nif,'.$user_id,
        'photo'=>'sometimes|required|mimes:jpeg,png|max:10000', 
        
    ]);

          if ($validator->fails()) {
             return response()->json(['error' =>$validator->errors()->first()],400);
          }

            $user->name = $request['name'];
            $user->email = $request['email'];
         
            $user->photo_url=$request['photo'];
            if($user->type=='C'){
                $customer = User::find($user_id);
            var_dump($customer);
            $customer->nif = $request['nif'];
            $customer->address = $request['address'];
            $customer->phone = $request['phone'];

           
            //$customer->save();
            }
            $user->save();
            if ($request->hasfile('photo')) {
                
                $nov_nome = $user->id . "_" . time() . "." . $request->file('photo')->getClientOriginalExtension();
                Storage::putFileAs("fotos", $request->file('photo'), $nov_nome);//364
                
                $user['photo_url'] = $nov_nome;
            }
            $user->update();

           

           return response()->json($user, 201);
    // return response()->json( $user, 201);
 }
 
 



public function changePassword(Request $request)
    {

        $user_id =  Auth::user()->id;
        
        $user = User::find($user_id);
    
     
        $password_nova = $request['new'];

        $request->validate([
            
            'new' => 'required|string|min:3|same:confirm',
            'confirm' => 'required|string',
        ]);

        if (!Hash::check($request['atual'], Auth::user()->password)) {
            return response()->json(['error'=>"Password doesn´t match old password"], 400);
        }

        $user->password = Hash::Make($password_nova);
        $user->save();
        return response()->json("Password changed", 201);






        return response()->json(new UserResource($user), 201);
    }
    
    public function updateUserToUnavailable($id){
        $user=User::find($id);
        $user->available_at=null;
        $user->update();


}

public function users()
    {

        return UserResource::collection(User::where('deleted_at',null)->get());
        // $products = Product::where('deleted_at',null)->get();
        return response()->json($users, 201);
        // return ProductResource::collection();

        // $Product = Product::where('id',$id)->where('deleted_at','=',null);
    }

    public function usersEmp()
    {
        //$orders=Order::where(); 
       $users_id= array();
        $users =User::where('deleted_at',null)->whereIn('type',['ED','EC','EM'])->get();

      

        for ($i=0; $i < count($users) ; $i++) { 
            $users_id[$i] = $users[$i]->id;
        }

        //return response()->json($users_id, 201);
       // $orders = Order::whereIn('status',['T','P'])->whereIn('delivered_by',$users_id)->orwhereIn('prepared_by',$users_id)->get();
      
        
        
        //$orders = Order::whereIn('status',['T','P'])->whereIn('delivered_by',$users_id)->orwhereIn('prepared_by',$users_id)->get();
        $orders = Order::whereIn('status',['T','P'])->whereIn('delivered_by',$users_id)->orwhereIn('status',['T','P'])->whereIn('prepared_by',$users_id)->get();
        $ordersActive = Order::whereIn('status',['T','P','H','R'])->get();
        $ordersCooker = Order::where('status','P')->get();
        $ordersDeliveryMan = Order::where('status','T')->get();

        

        for($i=0;$i<count($ordersActive);$i++){
           // $cooker[$i] = User::findOrFail($ordersActive[$i]->prepared_by);

            for ($j=0; $j < count($ordersCooker) ; $j++) { 
                
               if($ordersActive[$i]->id == $ordersCooker[$j]->id){
                   
               
               $ordersActive[$i]->name = User::where('id',$ordersActive[$i]->prepared_by)->get('name')[0]['name'];
               }
            };
           // return response()->json($ordersActive, 201);

            for ($h=0; $h < count($ordersDeliveryMan) ; $h++) { 

                if($ordersActive[$i]->id == $ordersDeliveryMan[$h]->id){
                $ordersActive[$i]->name = User::where('id',$ordersActive[$i]->delivered_by)->get('name')[0]['name'];
                }
               
            };
            //return response()->json($ordersActive, 201);
            //$userCustomer[$i] = Customer::findOrFail($orders[$i]->customer_id);
            
            
            
    };

   
       // $ordersCook =Order::whereIn('prepared_by',$users_id)->get();
        // $products = Product::where('deleted_at',null)->get();
      
        return response()->json(["users"=>$users, "orders"=>$orders, "ordersActive"=>$ordersActive],201);
        // return ProductResource::collection();

        // $Product = Product::where('id',$id)->where('deleted_at','=',null);
    }

    public function managerUpdateUsers(Request $request)
    {

        //  return response()->json($request, 201);
               $user_id = Auth::user()->id;
               
            //    $customer_id=$user_id;
                $user = User::find($request['id']);
                

               $validator = Validator::make($request->all(),[
                'name' => 'required|min:2|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
                'email' => 'required|email|unique:users,email,'.$user_id,
                'photo'=>'sometimes|required|mimes:jpeg,png|max:10000',
                'type'=>'required'
              
            ]);

            if ($validator->fails()) {
                return response()->json(['error' =>$validator->errors()->first()],400);
             }
   
               $user->name = $request['name'];
               $user->type = $request['type'];
               $user->email = $request['email'];
                
              
               //$customer->save();
               //}
            //    $user->save();
               if ($request->hasfile('photo')) {
                   
                   $nov_nome = $user->id . "_" . time() . "." . $request->file('photo')->getClientOriginalExtension();
            
                    Storage::putFileAs("fotos", $request->file('photo'), $nov_nome);//364
                   $user['photo_url'] = $nov_nome;
               }
               $user->save();
               return response()->json($user, 201);
   
            //    $user->update();
   
              
   
            //   return response()->json($user, 201);
       // return response()->json( $user, 201);
    }


    public function managerCreateUser(Request $request)
 {

     $validator = Validator::make($request->all(),[
              'name' => 'required|min:2|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
              'email' => 'required|email|unique:users,email',
              'type'=> 'required',
              'photo'=>'sometimes|required|mimes:jpeg,png|max:10000', 

          ]);
         

          if ($validator->fails()) {
             return response()->json(['error' =>$validator->errors()->first()],400);
          }
          
          $user = new User();
           
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->type = $request['type'];
            $user->password = '123';
            $user->password = Hash::make($user->password);



            $user->save();
            
            if ($request->hasfile('photo')) {
                
                $nov_nome = $user->id . "_" . time() . "." . $request->file('photo')->getClientOriginalExtension();
                Storage::putFileAs("fotos", $request->file('photo'), $nov_nome);//364
                
                
                $user['photo_url'] = $nov_nome;
            }
            $user->update();

           

           return response()->json($user, 201);
    // return response()->json( $user, 201);
 }

 public function deleteUser($id, $type)
 {
     $User = User::find($id);
     $customer = Customer::find($id);
     if($id==Auth::user()->id){
        return response()->json(['error'=>"You can't delete yourself"], 400);
     }
     if($User->type='C'){
        $customer->deleted_at=date('Y-m-d H:i:s');
        $customer->update();
     }
     
     $User->deleted_at=date('Y-m-d H:i:s');
     $User->type='1';
     $User->update();
     return response()->json($User, 201);
 }


}