<?php

namespace App\Http\Controllers\Api;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CopponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::all();

        return response()->json([
            "data" => $coupons
        ], 200);
    }




    // public function applyCoupon(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'code' => 'required|max:255',
    //         'cart_value' => 'required|numeric|min:0'
    //     ]);
    //     $code = $request->input('code');
    //     $cartValue = $request->input('cart_value');


    //     $coupon = Coupon::where('code', $code)->first();

    //     if ($coupon && $cartValue >= $coupon->cart_value) {
    //         if ($coupon->type === 'fixed') {
    //             $discount = $coupon->value;
    //         } else {
    //             $discount = $cartValue * $coupon->value / 100;
    //         }
    //         return response()->json([
    //             'status' => 200,
    //             'discount' => $discount,
    //             'coupon' => $coupon
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'status' => 404,
    //             'error' => 'Invalid coupon'
    //         ]);
    //     }
    // }

    public function applyCoupon(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|max:255',
            'cart_value' => 'required|numeric|min:0'
        ]);
        $code = $request->input('code');
        $cartValue = $request->input('cart_value');


        $coupon = Coupon::where('code', $code)->first();
        if ($coupon) {

            if ($cartValue >= $coupon->cart_value) {
                if ($coupon->type === 'fixed') {
                    $discount = $coupon->value;
                } else {
                    $discount = $cartValue * $coupon->value / 100;
                }
                return response()->json([
                    'status' => 200,
                    'discount' => $discount,
                    'coupon' => $coupon
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'error' => 'Your Cart Total must be greater than    $'."  ".$coupon->cart_value
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'error' => 'Invalid coupon'
            ]);
        }
    }


    public function store(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'code' => 'required|unique:coupons|max:255',
                'type' => 'required|in:fixed,percent',
                'value' => 'required|numeric|min:0',
                'cart_value' => 'required|numeric|min:0'
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'message' => 'validation error',
                'error' => $validateUser->errors()
            ]);
        }

        $code = $request->input('code');
        $type = $request->input('type');
        $value = $request->input('value');
        $cartValue = $request->input('cart_value');

        $coupon = new Coupon();
        $coupon->code = $code;
        $coupon->type = $type;
        $coupon->value = $value;
        $coupon->cart_value = $cartValue;
        $coupon->save();

        return response()->json([
            'message' => 'Coupon created successfully',
            'data' => $coupon
        ], 201);
    }


    public function show(Coupon $coupon)
    {
        return response()->json([
            "data" => $coupon
        ], 200);
    }


    public function update(Request $request, Coupon $coupon)
    {
        $code = $request->input('code');
        $type = $request->input('type');
        $value = $request->input('value');
        $cartValue = $request->input('cart_value');


        $coupon->code = $code;
        $coupon->type = $type;
        $coupon->value = $value;
        $coupon->cart_value = $cartValue;
        $coupon->save();

        return response()->json([
            'message' => 'Coupon updated successfully',
            'data' => $coupon
        ], 200);
    }









    public function destroy($id)
    {
        $Coupon = Coupon::find($id);
        if ($Coupon) {
            $Coupon->delete();
            return response()->json([
                'message' => 'Coupon deleted successfully',
                'data' => $Coupon
            ], 200);
        } else {
            return response()->json([
                'message' => 'Coupon not found',
            ]);
        }
    }
}
