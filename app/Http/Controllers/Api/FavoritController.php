<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorit;
use Illuminate\Http\Request;

class FavoritController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $favorites = Favorit::where('user_id', auth()->id())
            ->with('product.ProductImages')
            ->get();
        return response()->json([
            'status' => 'success',
            'wishlist' => $favorites,
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->check()) {
            $Favorit = Favorit::where('user_id', auth()->id())->where('product_id', $request->product_id)->exists();
            if ($Favorit) {
                return response()->json([
                    'status' => 409,
                    'message' => 'Product already added to Favorit'
                ], 200);
            } else {
                $Favorit= Favorit::create([
                    'product_id' => $request->product_id,
                    'user_id' => auth()->id()
                ]);
                $favorites = Favorit::where('user_id', auth()->id())
                ->with('product.ProductImages')
                ->get();
                return response()->json([
                    'status' => 200,
                    'message' => 'Product added to Favorit',
                    'wishlist' => $favorites
                ], 201);
            }
        }
        return response()->json([
            'status' => 401,
            'message' => 'Unauthorized',
        ]);
    }

    public function destroy(string $id)
    {
        $favorit = Favorit::where('user_id', auth()->id())->where('product_id', $id)->first();

        if ($favorit) {
            $favorit->delete();
            $favorites = Favorit::where('user_id', auth()->id())
                ->with('product.ProductImages')
                ->get();
            return response()->json([
                'status' => 200,
                'message' => 'Product removed from favorites',
                'wishlist' => $favorites
            ]);
        }

        return response()->json([
            'status' => 404,
            'message' => 'Product not found in favorites',
        ]);
    }
}
