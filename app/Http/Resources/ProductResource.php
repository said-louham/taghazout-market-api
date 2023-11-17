<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // add media
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'original_price' => $this->original_price,
            'selling_price' => $this->selling_price,
            'quantity' => $this->quantity,
            'trending' => $this->trending,
            'featured' => $this->featured,
            'category' => $this->whenLoaded('category'),
            'ratings' => $this->whenLoaded('ratings'),

        ];
    }
}
