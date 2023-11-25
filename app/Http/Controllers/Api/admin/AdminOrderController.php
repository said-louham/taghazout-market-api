<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Mail\OrderEmail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\QueryBuilder;

class AdminOrderController extends Controller
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
            ]);

        return OrderResource::collection($data->paginate(10));
    }

    public function show(Order $order)
    {
        return response()->json($order);
    }

    public function update(Request $request, Order $order)
    {
        $order->updateOrderstatus($order, $request->status_message);

        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        DB::beginTransaction();

        $order->order_items()->delete();
        $order->delete();

        DB::commit();
    }

    public function SendEmail($id)
    {
        $order = Order::find($id);

        DB::beginTransaction();

        Mail::to($order->email)->send(new OrderEmail($order));

        DB::commit();

        return response()->json(true);
    }
}
