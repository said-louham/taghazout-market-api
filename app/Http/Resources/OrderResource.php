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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'tracking_no' => $this->tracking_no,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'status_message' => $this->status_message,
            'payment_mode' => $this->payment_mode,
            'coupon_discount' => $this->coupon_discount,
            'shipping_cost' => $this->shipping_cost,
            'tax' => $this->tax,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'order_items' => OrderItemResource::collection($this->orderItems),
        ];
    }
}
