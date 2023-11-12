<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tracking_no',
        'full_name',
        'email',
        'phone',
        'address',
        'status_message',
        'payment_mode',
        'coupon_discount',
        'shipping_cost',
        'tax',
    ];

    protected $casts = [
        'shipping_cost' => 'float',
        'coupon_discount' => 'float',
        'tax' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order_items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
