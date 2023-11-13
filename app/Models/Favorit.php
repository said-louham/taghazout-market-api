<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id',
    ];

    /**
     * Get the user who favorited the product.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that was favorited.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
