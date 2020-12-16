<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'   => $this->id,
            'status' =>$this->status,
            'customer_id' => $this->customer_id,
            'date' =>$this->date,
            'notes' =>$this->notes,
            'total_price' =>$this->total_price    
        ];
    }
}
