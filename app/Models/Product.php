<?php

namespace App\Models;

use App\Models\Rating;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'original_price',
        'selling_price',
        'quantity',
        'trending',
        'featured',
        'status',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function ProductImages(){
        return $this->hasMany(ProductImage::class,'product_id',"id");
   }
   
    public function Ratings(){
        return $this->hasMany(Rating::class,'product_id',"id");
   }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
