<?php

namespace App\Http\Resources;

use App\Http\Resources\ChargingResource;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'middlename' => $this->middlename,
            'lastname' => $this->lastname,
            'suffix' => $this->suffix,
            'username' => $this->username,
        //    'password' => $this->password,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'role_id' => new RoleResource($this->whenLoaded('role')),
            'charging_id' => new ChargingResource($this->whenLoaded('charging')),
            ];
    }
}
