<?php

namespace App\Http\Requests;

use App\Models\Coupon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplyCouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'       => ['string', 'required', Rule::exists(Coupon::class, 'code')],
            'cart_value' => ['required', 'numeric', 'min:0'],
        ];
    }
}
