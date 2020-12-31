<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatsPerMonth extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $year = date("Y");

        $totalSells    = $this->sellsPerMonthYear($year);
        $totalorders    = $this->ordersPerMonthYear($year);
        $productsFormated    = $this->bestSellers($year);
        

        $graphFormattedSells = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $orderFormated = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        foreach ($totalSells as $key=>$value) {
            $graphFormattedSells[$value->MONTH-1] = (float)($value->TOTAL);
        }

        foreach ($totalorders as $key=>$value) {
            $orderFormated[$value->MONTH-1] = (float)($value->ORDERS);
        }

        return [
            'year'          => $year,
            'total_price'   => $graphFormattedSells,
            'total_order'   => $orderFormated,
            'total_products'=> $productsFormated

        ];
    }
}
