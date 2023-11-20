<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FavoriteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) auth()->id();
    }

    public function rules(): array
    {
        return [
            'product_id' => ['bail', 'required', 'integer', Rule::exists(Product::class, 'id')],
        ];
    }
}
