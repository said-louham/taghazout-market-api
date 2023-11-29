<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Jobs\SendOrderEmail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller
{
    public function index()
    {
        $data = QueryBuilder::for(Order::class)
            ->select([
                'id',
                'user_id',
                'tracking_nbr',
                'full_name',
                'email',
                'phone',
                'address',
                'status',
                'payment_mode',
                // 'coupon_discount',
                //  'shipping_cost',
                //  'tax',
            ])
            ->with([
                'user:id,full_name',

                'order_items' => static function ($query) {
                    $query->select([
                        'price',
                        'product_id',
                        'selling_price',
                        'order_products.quantity',
                        'trending',
                        'featured',
                    ]);
                },

            ])
            ->where('user_id', auth()->id())
            ->paginate(10);

        return response()->json($data);
    }

    public function store(OrderRequest $request)
    {
        $data = $request->validated();
        $user = auth()->check() ? User::findOrFail(auth()->id()) : null;

        DB::beginTransaction();

        $order = Order::create(collect($data)->except(['cart'])->toArray() + ['user_id' => $user?->id]);
        $order->order_items()->sync($data['cart']);

        $order->updateUserAndDecrementProductQuantity($user, $data);

        //  dispatch(new SendOrderEmail($order));

        DB::commit();

        return response()->json(true);
    }

    public function show(Order $order)
    {
        return response()->json($order);
    }
}
