<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorit;
use Spatie\QueryBuilder\QueryBuilder;

class FavoritController extends Controller
{
    public function index()
    {
        $data = QueryBuilder::for(Favorit::class)
            ->where('user_id', auth()->id())
            ->select([
                'id',
                'product_id',
            ])->with([
                'product:id,name',
            ]);

        return FavoriteResource::collection($data->get());
    }

    public function store(FavoriteRequest $request)
    {
        $data = $request->validated();

        Favorit::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $data['product_id'],
            ],
            $data + auth()->id());

        return response()->json(true);
    }

    public function destroy(Favorit $favorit)
    {
        $favorit->where('product_id', $favorit->product_id)
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json(true);
    }
}
