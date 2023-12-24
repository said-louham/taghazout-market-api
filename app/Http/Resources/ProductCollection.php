<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($product) {
                return [
                    'id' => $product->id,
                    'category_id' => $product->category_id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'description' => $product->description,
                    'original_price' => $product->original_price,
                    'selling_price' => $product->selling_price,
                    'quantity' => $product->quantity,
                    'trending' => $product->trending,
                    'featured' => $product->featured,
                    'status' => $product->status,
                    'create_at' => Carbon::parse($product->created_at)->format('Y-m-d H:i:s'),
                    'images' => $product->ProductImages->map(function ($image) {
                        return [
                            'id' => $image->id,
                            'product_id' => $image->product_id,
                            'image' => $image->image,
                        ];
                    }),
                    'category' => $product->category,
                    'Ratings' => $product->Ratings ? $product->Ratings->map(function ($rating) {
                        return [
                            'id' => $rating->id,
                            'rating' => $rating->rating,
                            'comment' => $rating->comment,
                            'created_at' => $rating->created_at,
                            'updated_at' => $rating->updated_at,
                             'user' => [
                                'id' => $rating->user->id,
                                'full_name' => $rating->user->full_name,
                                'email' => $rating->user->email,
                                'phone' => $rating->user->phone,
                                'address' => $rating->user->email,
                            ],
                        ];
                    }) : null,


                ];
            }),
        ];
    }
}
