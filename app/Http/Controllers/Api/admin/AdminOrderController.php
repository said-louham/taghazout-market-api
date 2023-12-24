<?php

namespace App\Http\Controllers\Api\admin;

use App\Models\Order;
use App\Mail\OrderEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Response;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'orderItems.product')->orderBy('created_at', 'desc')->get();
        return Response::toJsonResponse(OrderResource::collection($orders));
    }

    public function show(string $id)
    {
        $order = Order::find($id);
        if ($order) {
            return response()->json([
                'order' => $order
            ]);
        } else {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }
    }

    // update Orders status
    public function update(Request $request, string $id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->update([
                'status_message' => $request->status_message
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Status updated',
                'order' => $order
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }
    }

    public function destroy(string $id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->delete();
            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
    }



    public function SendEmail($id)
    {
        $order = Order::find($id);
        try {
            Mail::to($order->email)->send(new OrderEmail($order));
            return response()->json([
                'status' => true,
                'message' => 'Email sent'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Email not sent'
            ], 404);
        }
    }
}
