<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Product as ProductResource;
use App\Models\Product;
use Validator;
use Illuminate\Support\Facades\Storage;



class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(Product::all());
    }


    public function updateProducts(Request $request)
    {

        // return response()->json($request, 201);
            //    $user_id = Auth::user()->id;
               
            //    $customer_id=$user_id;
                $product = Product::find($request['id']);
                

               $validator = Validator::make($request->all(),[
                'price'=>'numeric',
              
            ]);

            if ($validator->fails()) {
                return response()->json(['error' =>$validator->errors()->first()],400);
             }
   
               $product->name = $request['name'];
               $product->type = $request['type'];
               $product->price = $request['price'];
                
              
               //$customer->save();
               //}
            //    $user->save();
               if ($request->hasfile('photo')) {
                   
                   $nov_nome = $product->id . "_" . time() . "." . $request->file('photo')->getClientOriginalExtension();
            
                    Storage::putFileAs("products", $request->file('photo'), $nov_nome);//364
                   $product['photo_url'] = $nov_nome;
               }
               $product->save();
               return response()->json($product, 201);
   
            //    $user->update();
   
              
   
            //   return response()->json($user, 201);
       // return response()->json( $user, 201);
    }


    public function showProduct($id)
    {
        $Product = Product::find($id);

        if($Product==null)   
            return response()->json("Product not found.", 404);
        
        return new Product($Product);
    }


    public function newProduct(Request $request)
    {
        // return response()->json($request, 201);
        $validator = Validator::make($request->all(),[
                 'name' => 'required|min:2|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
                 'type' => 'required',
                 'description' => 'required|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
                 'price' =>'numeric',
                 'photo'=>'required|mimes:jpeg,png|max:10000', 
             ]);
            
                
             if ($validator->fails()) {
                 return response()->json(['error' =>$validator->errors()->first()],400);
             }
             
             $product = new Product();
              
               $product->name = $request['name'];
               $product->type = $request['type'];
               $product->description = $request['description'];
               $product->price = $request['price'];

               //$request->file('photo')->move(storage_path('app/public/storage/fotos'), $path);
               if ($request->hasfile('photo')) {
               $nov_nome = $product->id . "_" . time() . "." . $request->file('photo')->getClientOriginalExtension();
               Storage::putFileAs("products", $request->file('photo'), $nov_nome);//364

               $product->photo_url = $nov_nome;
               }
               
               $product->save();

              return response()->json($product, 201);
       // return response()->json( $user, 201);
    }




}
