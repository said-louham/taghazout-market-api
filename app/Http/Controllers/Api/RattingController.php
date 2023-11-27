<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RatingRequest;
use App\Models\Product;
use App\Models\Rating;
use Spatie\QueryBuilder\QueryBuilder;

class RattingController extends Controller
{
    public function index()
    {

        $data = QueryBuilder::for(Rating::class)
            ->select([
                'user_id',
                'product_id',
                'rating',
                'comment',
            ])->with(['user:id,full_name']);

        return response()->json($data->get());
    }

    public function store(RatingRequest $request)
    {
        $data = $request->validated();

        Product::updateOrCreate(
            [
                'user_id'    => auth()->id(),
                'product_id' => $data['product_id'],
            ],
            $data + auth()->id());

        return response()->json(true);
    }

    public function destroy(Rating $rating)
    {

        $rating->delete();

        return response()->json(true);
    }
}
