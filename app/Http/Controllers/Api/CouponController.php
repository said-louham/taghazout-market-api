<?php

namespace App\Http\Controllers\Api;

use App\Enums\CouponTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyCouponRequest;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Response;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Coupon::all();

        return response()->json($data);
    }

    public function applyCoupon(ApplyCouponRequest $request)
    {

        $data   = $request->validated();
        $coupon = Coupon::where('code', $data['code'])->first();

        if ($data['cart_value'] < $coupon->cart_value) {
            return response()->json([
                'error' => 'Your Cart Total must be greater than $' . $coupon->cart_value,
            ], Response::HTTP_NOT_FOUND);
        }

        $discount = ($coupon->type == CouponTypes::FIXED->value) ? $coupon->value : ($data['cart_value'] * $coupon->value / 100);

        return response()->json([
            'discount' => $discount,
            'coupon'   => $coupon,
        ]);
    }

    public function store(CouponRequest $request)
    {
        $data = $request->validated();

        Coupon::create($data);

        return response()->json(true);
    }

    public function show(Coupon $coupon)
    {
        return response()->json($coupon);
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        $data = $request->validated();

        $coupon->updateOrFail($data);

        return response()->json(true);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return response()->json(true);
    }
}
