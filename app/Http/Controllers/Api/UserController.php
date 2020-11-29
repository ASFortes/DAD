<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Customer;
use Hash;
use Validator;




class UserController extends Controller

{
 public function me(Request $request)
 {
    //return $request->user();
    return Auth::user();
 }

 public function store(Request $request)
 {

     
   

     $validator = Validator::make($request->all(),[
              'name' => 'required|min:2|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
              'email' => 'required|email|unique:users,email',
              'password' => 'required|min:3',
             
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
            $user->type=='C';

         

            
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
 


 function uploadPhoto(Request $request){

        
        
    if (isset($request->photo)) {
        
        $user_id = Auth::guard('api')->user()->id;
        
        $user = User::find($user_id);


        $path = $user->id . '.jpg';
        $request->file('photo')->move(storage_path('app/public/storage/fotos'), $path);
        $user->photo = $path;
        $user->save();

        return response()->json("Photo uploaded", 200);
    }

    return response()->json("Photo not found", 400);


}



}