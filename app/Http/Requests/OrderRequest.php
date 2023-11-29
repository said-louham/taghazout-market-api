<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tracking_nbr' => [
                'bail', 'required', 'string', 'max:250', 'min:1',
                $this->isMethod('put')
                    ? Rule::unique(Order::class, 'tracking_nbr')->ignore($this->order->id)
                    : Rule::unique(Order::class, 'tracking_nbr'),
            ],
            'full_name'         => ['bail', 'nullable', 'string'],
            'email'             => ['nullable', 'email', 'max:255'],
            'phone'             => ['nullable', 'string', 'max:255'],
            'address'           => ['nullable', 'string', 'max:255'],
            'payment_mode'      => ['nullable', 'integer', 'max:255'],
            'cart'              => ['bail', 'required', 'array', 'min:1'],
            'cart.*.product_id' => ['bail', 'required', 'integer', Rule::exists(Product::class, 'id')],
            'cart.*.quantity'   => ['bail', 'required', 'numeric', 'min:0'],
            'cart.*.price'      => ['bail', 'required', 'numeric'],
        ];
    }
}
