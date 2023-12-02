<?php

namespace App\Http\Requests;

use App\Enums\CouponTypes;
use App\Models\Coupon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
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
            'code' => [
                'bail', 'required', 'string',
                Rule::unique(Coupon::class, 'code')->ignore($this->isMethod('put') ? $this->coupon->id : null),
            ],
            'type'       => ['required', Rule::in(CouponTypes::cases())],
            'value'      => ['required', 'numeric', 'min:0'],
            'cart_value' => ['required', 'numeric', 'min:0'],
        ];
    }
}
