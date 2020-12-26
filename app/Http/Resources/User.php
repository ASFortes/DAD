<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'photo' => $this->photo_url,
            'password' => $this->password,
            'available_at'=>$this->available_at,
            'deleted_at'=>$this->deleted_at,
     
        ];
    }
}


