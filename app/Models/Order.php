<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{


    use HasFactory;

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id' ,
        'status',
        'customer_id' ,
        'date',
        'notes' ,
        'total_price',
        'prepared_by',
        'delivered_by',
        'opened_at',
        'current_status_at',
        'closed_at',
        'preparation_time',
        'updated_at',

        
 
    ];
    


    

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function customer_id(){
        return $this->belongsTo(Customer::class);
    }
}
