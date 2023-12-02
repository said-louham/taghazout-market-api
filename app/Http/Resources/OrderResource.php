<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'tracking_nbr'   => $this->tracking_nbr,
            'full_name'      => $this->full_name,
            'email'          => $this->email,
            'phone'          => $this->phone,
            'address'        => $this->address,
            'status_message' => $this->status_message,
            'payment_mode'   => $this->payment_mode,
            'shipping_cost'  => $this->shipping_cost,
            'tax'            => $this->tax,
            'user'           => $this->whenLoaded('user'),
            'order_items'    => $this->whenLoaded('order_items'),
        ];
    }
}
