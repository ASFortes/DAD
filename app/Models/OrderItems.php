<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $fillable = [
        'id' ,
        'order_id' ,
        'product_id' ,
        'quantity' ,
        'unit_price'  ,
        'sub_total_price' ,
      
         ];
    


    public function product_id(){
        return $this->belongsTo(Product::class);
    }

    public function order_id()
    {
        return $this->HasOne(Order::class);
    }

   

}
