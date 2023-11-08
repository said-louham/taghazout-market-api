<?php

namespace App\Models;

use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        "coupon_discount",
        "shipping_cost",
        "tax",
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
