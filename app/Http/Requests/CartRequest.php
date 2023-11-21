<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->isMethod('post') ? true :
            $this->cart->user_id === auth()->id();
    }

    public function rules(): array
    {
        return $this->isMethod('put')
            ? [
                'quantity'   => ['bail', 'required', 'numeric', 'min:1'],
                'product_id' => ['bail', 'required', 'integer', Rule::exists(Product::class, 'id')],
            ]
            : [
                'items.*'            => ['bail', 'required', 'array', 'min:1'],
                'items.*.quantity'   => ['bail', 'required', 'numeric', 'min:1'],
                'items.*.product_id' => ['bail', 'required', 'integer', Rule::exists(Product::class, 'id')],
            ];
    }
}
