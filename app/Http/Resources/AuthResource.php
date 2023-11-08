<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    private $token;

    public function toArray(Request $request): array
    {
        $phone = $this->Profile ? $this->Profile->phone : "";
        $adress = $this->Profile ? $this->Profile->adress : "";
        return [
            'message' => 'Connected successfully',
            'user' => [
                'id' => $this->resource->id,
                'full_name' => $this->resource->full_name,
                'email' => $this->resource->email,
                'role' => $this->resource->role,
                'email_verified_at' => $this->resource->email_verified_at,
                'created_at' => $this->resource->created_at,
                'updated_at' => $this->resource->updated_at,
            ],
            'token' => $this->token,
            'status' => 200,
            'phone' => $phone,
            'adress' => $adress,
        ];
     
    
    }
    public function withToken($token)
    {
        $this->token = $token;
        return $this;
    }
}
