<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class CartController extends Controller
{
    public function index()
    {
        $data = QueryBuilder::for(Category::class)
            ->select([
                'user_id',
                'product_id',
                'quantity',
            ])
            ->where('user_id', auth()->id())
            ->with((
                'product:id,name,selling_price'
            ));

        return CartResource::collection($data->get());
    }

    public function store(CartRequest $request)
    {
        $data   = $request->validated();
        $userId = auth()->id();

        collect($data['items'])->each(function ($item) use ($userId) {
            $productId = $item['product_id'];
            $quantity  = $item['quantity'];

            Cart::updateOrCreate(
                ['user_id' => $userId, 'product_id' => $productId],
                ['quantity' => DB::raw("quantity + $quantity")]
            );
        });
    }

    public function update(CartRequest $request, Cart $cart)
    {
        $data = $request->validated() + auth()->id();

        $cart->updateOrFail($data);

        return response()->json(true);
    }

    public function destroy(Cart $cart)
    {

        $cart->delete();

        return response()->json(true);
    }
}
