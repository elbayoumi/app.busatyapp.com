<?php

namespace App\Http\Resources\attendants\trips;

use Illuminate\Http\Resources\Json\JsonResource;

class parentsResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            "id"          => $this->id,
            "name"        => $this->name,
            "phone"       => $this->phone,
            "email"       => $this->email,
            "address"     => $this->address,
            "logo_path"   => $this->logo_path,
        ];
    }
}





