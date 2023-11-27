<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use Spatie\QueryBuilder\QueryBuilder;

class FavoritController extends Controller
{
    public function index()
    {
        $data = QueryBuilder::for(Favorite::class)
            ->where('user_id', auth()->id())
            ->select([
                'product_id',
            ])->with([
                'product:id,name',
            ]);

        return FavoriteResource::collection($data->get());
    }

    public function store(FavoriteRequest $request)
    {
        $products = $request->validated();

        $user = auth()->user();
        $user->products()->sync($products);

        return response()->json(true);
    }

    public function destroy(Favorite $favorit)
    {
        // delete
        $favorit->where('product_id', $favorit->product_id)
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json(true);
    }
}
