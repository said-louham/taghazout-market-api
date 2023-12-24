<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;

class CartController extends Controller
{

    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
        return CartResource::collection($cartItems);
    }

    public function store(Request $request)
    {
        $cartItems = $request->input('cartItems');
        $userId = auth()->id();

        $userCart = Cart::where('user_id', $userId)->get();

        if ($userCart->isEmpty()) {
            foreach ($cartItems as $cartItem) {
                $productId = $cartItem['product_id'];
                $quantity = $cartItem['quantity'];

                $cartItem = new Cart([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
                $userCart->push($cartItem);
            }
        } else {
            foreach ($cartItems as $cartItem) {
                $productId = $cartItem['product_id'];
                $quantity = $cartItem['quantity'];
                $existingCartItem = $userCart->where('product_id', $productId)->first();

                if ($existingCartItem) {
                    $existingCartItem->quantity += $quantity;
                    $existingCartItem->save();
                } else {
                    $cartItem = new Cart([
                        'user_id' => $userId,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                    ]);
                    $userCart->push($cartItem);
                }
            }
        }

        $userCart->each(function ($cartItem) {
            $cartItem->save();
        });

        return CartResource::collection($userCart);
    }


    public function update(Request $request, string $id)
    {
        $quantity = $request->input('quantity', 1);

        $cartItem = Cart::where('user_id', auth()->id())
            ->where('id', $id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart item not found',
            ]);
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        return response()->json([
            'status' => 200,
            'message' => 'Cart updated',
            'data' => $cartItem,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('id', $id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'status' => 404,
                'message' => 'Cart item not found',
            ]);
        }

        $cartItem->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Product removed from cart',
        ]);
    }
    public function destroyUserCart()
    {
        $userCart = Cart::where('user_id', auth()->id())->get();

        if (count($userCart) != 0) {
            foreach ($userCart as $cartItem) {
                $cartItem->delete();
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Cart deleted successfully',
        ]);
    }
}
