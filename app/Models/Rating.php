<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];

    /**
     * Get the user who rated the product.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that was rated.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
