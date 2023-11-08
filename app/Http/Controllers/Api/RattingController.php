<?php

namespace App\Http\Controllers\Api;

use App\Models\Rating;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class RattingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Ratings = Rating::all();
        return response()->json([
            'Ratings' => $Ratings
        ]);
    }

    public function RateProduct(Request $request,  $product_id)
    {
        $this->validate($request, [
            'rating' => 'required|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        $rating = Rating::where('user_id', auth()->id())
            ->where('product_id', $product_id)
            ->first();

        if ($rating) {
            $rating->rating = $request->rating;
            $rating->comment = $request->comment;
            $rating->save();
        } else {
            $rating = new Rating([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            $rating->save();
        }

        return response()->json([
            'message' => 'Rating added successfully',
            'rating' => $rating
        ], 200);
    }

    public function destroy($product_id)
    {
        $rating = Rating::where('user_id', auth()->id())
            ->where('product_id', $product_id);
        $rating->delete();

        return response()->json([
            'message' => 'Rating deleted successfully'
        ], 201);
    }
}
