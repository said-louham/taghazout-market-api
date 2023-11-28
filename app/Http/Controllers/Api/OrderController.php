<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Jobs\SendOrderEmail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller
{
    public function index()
    {
        $data = QueryBuilder::for(Order::class)
            ->select([
                'user_id',
                'tracking_nbr',
                'full_name',
                'email',
                'phone',
                'address',
                'status_message',
                'payment_mode',
                'coupon_discount',
                'shipping_cost',
                'tax',
            ])->with([
                'user:id,full_name',
                'order_items:id,order_id,product_id,quantity,price' => [
                    'product:id,name',
                ],
            ])
            ->where('user_id', auth()->id())
            ->paginate(_paginatePages());

        return response()->json($data);
    }

    public function store(OrderRequest $request)
    {
        $data = $request->validated();

        $order = Order::create($data);

        $user = User::find(auth()->user()->id);

        $user->update([
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        foreach ($data['cart'] as $item) {
            OrderItem::create($item + ['order_id' => $order->id]);

            // decrement product quantiy
            //   $product->decrement('quantity', $item['quantity']);
        }

        dispatch(new SendOrderEmail($order));

        return Response::toJsonResponse(new OrderResource($order));

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::where('user_id', auth()->user()->id)->where('id', $id)->first();
        if ($order) {
            return response()->json([
                'order' => $order,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ]);
        }
    }
}
