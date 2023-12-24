<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            "product_id"=>$this->product_id,
            'quantity' => $this->quantity,
            "user_id"=>$this->user_id,
            'product' => new ProductResource($this->product),

        ];
    }
}
