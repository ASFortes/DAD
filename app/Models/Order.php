<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;


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
    public function prepared_by(){
        return $this->hasOne(User::class);
    }


        //stats sales per month year
        public function sellsPerMonthYear($year) {
            $sellsPerYear = $this->sellsSumPerYearAndMonth($year);
            return $sellsPerYear == null ? "" : $sellsPerYear;
        }
    
        private function sellsSumPerYearAndMonth($year) {
            return Order::selectRaw("month(DATE) AS MONTH, SUM(total_price) AS TOTAL")
                         ->whereYear('date','=', $year)
                         ->groupBy(DB::raw('month(date)'))
                         ->orderBy(DB::raw('month(date)'))
                         ->get();
        }

        //stats orders per month year
        public function ordersPerMonthYear($year) {
            $ordersPerYear = $this->ordersSumPerYearAndMonth($year);
            return $ordersPerYear == null ? "" : $ordersPerYear;
        }
    
        private function ordersSumPerYearAndMonth($year) {
            return Order::selectRaw("month(DATE) AS MONTH, COUNT(id) AS ORDERS")
                         ->whereYear('date','=', $year)
                         ->groupBy(DB::raw('month(date)'))
                         ->orderBy(DB::raw('month(date)'))
                         ->get();
        }


        //stats sales by products
        public function bestSellers($year) {
            $bestProduct = $this->productSumPerYearAndMonth($year);
            return $bestProduct == null ? "" : $bestProduct;
        }
    
        private function productSumPerYearAndMonth($year) {
            return OrderItems::groupBy('product_id')
                            ->selectRaw('product_id, sum(quantity) as quantity')
                            ->get();
                        // select('product_id')->all()->sum('quantity')->groupBy('product_id');
                        //  ->join('products', 'order_items.product_id', '=', 'products.id')
                        //  ->whereYear('date','=', $year)
                        //  ->groupBy('product_id')
                        //  ->get();
        }


}