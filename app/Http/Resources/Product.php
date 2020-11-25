<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
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
            'photo' =>$this->photo_url,
            'name' => $this->name,
            'type' =>$this->type,
            'description' =>$this->description,
            'price' =>$this->price
         
        ];
    }
}
