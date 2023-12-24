<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Mail\OrderEmail;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use App\Jobs\SendOrderEmail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'orders' => $orders
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'payment_mode' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'validation error',
                'error' => $validator->errors()
            ]);
        }
        $order = Order::create([
            'user_id'=>auth()->user()->id,
            'tracking_no' => "taghazout-market-" . Str::random(10),
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status_message' => 'in-progress',
            'payment_mode' => $request->payment_mode,
            'coupon_discount' => $request->coupon_discount ?? 0,
            'shipping_cost' => $request->shipping_cost ?? 0,
            'tax' => $request->tax ?? 0,
        ]);

        $user = User::find(auth()->user()->id);
        $user->update([
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        if (isset($request->cart) && is_array($request->cart)) {
            foreach ($request->cart as $cartItem) {
                $product = Product::findOrFail($cartItem['product_id']);
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem['product_id'],
                    'quantity' => $cartItem['quantity'],
                    'price' => $product->selling_price,
                ]);

                // decrement product quantiy 
                $product->decrement('quantity', $cartItem['quantity']);
            }
        } else {
            return response()->json([
                'message' => 'validation error',
                'error' => 'cart is empty'
            ]);
        }
        try {
            // Mail::to($order->email)->send(new OrderEmail($order));
            // SendOrderEmail::dispatch($order);
            dispatch(new SendOrderEmail($order));

            return Response::toJsonResponse( new OrderResource($order));

        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong.' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::where("user_id", auth()->user()->id)->where('id', $id)->first();
        if ($order) {
            return response()->json([
                'order' => $order
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }
    }
}
