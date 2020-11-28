

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
        'id' => $this->id,
        'name' => $this->name,
        'email' => $this->email,
        'type' => $this->type,
        'blocked' => $this->blocked,
        'photo' => $this->photo_url
        
    ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

