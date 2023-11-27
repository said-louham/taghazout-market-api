<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use Illuminate\Http\Request;
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
        $data = collect($request->validated())->keyBy('product_id');

        $user = auth()->user();

        $user->products()->sync($data);

        return response()->json(true);
    }

    public function deleteWishlist(Request $request)
    {
        $products = $request->input('products', []);
        $user     = auth()->user();

        $user->products()->whereIn('product_id', $products)->delete();

        return response()->json(true);
    }
}
