<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tracking_nbr',
        'full_name',
        'email',
        'phone',
        'address',
        'status',
        'payment_mode',
        'coupon_discount',
        'shipping_cost',
        'tax',
        'coupon_id',
    ];

    protected $casts = [
        'shipping_cost'   => 'float',
        'coupon_discount' => 'float',
        'tax'             => 'float',
    ];

    public function updateOrderstatus($order, int $status): void
    {
        $order->update(['status' => $status]);
    }

    public function updateUserAndDecrementProductQuantity($user, array $data = []): void
    {
        if (! is_null($user)) {
            $user->update([
                'phone'   => $data['phone'],
                'address' => $data['address'],
            ]);
        }

        foreach ($data['cart'] as $cart) {
            Product::find($cart['product_id'])->decrement('quantity', $cart['quantity']);
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order_items(): BelongsToMany
    {
        return $this->belongsToMany(product::class, 'order_products', 'order_id', 'product_id');
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
}
