<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'id' ,  
        'name' ,
        'type' ,
        'description',
        'photo_url' ,
        'price' ,
        'created_at',
        'updated_at',
        'deleted_at'


    ];
}
