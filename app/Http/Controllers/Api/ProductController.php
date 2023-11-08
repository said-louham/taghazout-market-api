<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $baseurl = "http://127.0.0.1:8000/";
    public function index()
    {
        $products = Product::with('Ratings')->latest()->get();
        return new ProductCollection($products);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255|unique:products,name',
            'slug' => 'required|unique:products,slug|max:255',
            'description' => 'required',
            'original_price' => 'required|min:0',
            'selling_price' => 'required|min:0',
            'quantity' => 'required|min:0',
            'trending' => 'nullable',
            'featured' => 'nullable',
            'status' => 'nullable',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'error' => $validator->errors()
            ]);
        }

        $category = Category::find($request->input('category_id'));
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ]);
        }

        $product = $category->products()->create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'brand' => $request->input('brand'),
            'description' => $request->input('description'),
            'original_price' => $request->input('original_price'),
            'selling_price' => $request->input('selling_price'),
            'quantity' => $request->input('quantity'),
            'trending' => $request->input('trending') ? "1" : "0",
            'featured' => $request->input('featured') ? "1" : "0",
            'status' => $request->input('status') ? "1" : "0",
        ]);



        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $key => $imageFile) {
                $extension = $imageFile->getClientOriginalExtension();
                $imageName = time() . $key . '.' . $extension;
                $imageFile->move(public_path('products'), $imageName);
                $finalImagePathName = $this->baseurl . 'products/' . $imageName;

                $product->ProductImages()->create([
                    'image' => $finalImagePathName
                ]);
            }
        }



        return response()->json([
            'status' => true,
            'message' => 'Product added successfully',
            'data' => $product
        ], 201);
    }



    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|max:255|unique:products,name,' . $id,
            'slug' => 'sometimes|unique:products,slug,' . $id . '|max:255',
            'description' => 'sometimes|required',
            'original_price' => 'sometimes|numeric|min:0',
            'selling_price' => 'sometimes|numeric|min:0',
            'quantity' => 'sometimes|integer|min:0',
            'trending' => 'sometimes|nullable|boolean',
            'featured' => 'sometimes|nullable',
            'status' => 'sometimes|nullable',
            'image.*' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'error' => $validator->errors()
            ]);
        }

        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found with id '.$id
            ]);
        }

        if ($request->has('category_id')) {
            $category = Category::find($request->input('category_id'));
            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found'
                ]);
            }
        }
        
        $product->name = $request->input('name', $product->name);
        $product->slug = $request->input('slug', $product->slug);
        $product->description = $request->input('description', $product->description);
        $product->original_price = $request->input('original_price', $product->original_price);
        $product->selling_price = $request->input('selling_price', $product->selling_price);
        $product->quantity = $request->input('quantity', $product->quantity);
        $product->trending = $request->has('trending') ? $request->input('trending') : $product->trending;
        $product->featured = $request->has('featured') ? $request->input('featured') : $product->featured;
        $product->status = $request->has('status') ? $request->input('status') : $product->status;

        if ($request->has('category_id')) {
            $product->category_id = $request->input('category_id');
        }

        $product->save();

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $key => $imageFile) {
                $extension = $imageFile->getClientOriginalExtension();
                $imageName = time() .$key.'.' . $extension;
                $imageFile->move(public_path('products'), $imageName);
                $finalImagePathName = $this->baseurl . 'products/' . $imageName;

                $productImage = new ProductImage;
                $productImage->product_id = $product->id;
                $productImage->image = $finalImagePathName;
                $productImage->save();
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => new ProductResource($product)
        ], 200);
    }





    public function disroyImage($image_id)
    {
        $productImage = ProductImage::find($image_id);

        if (!$productImage) {
            return response()->json([
                'status' => false,
                'message' => 'Product Images not found'
            ]);
        }
        if (file_exists(public_path('products') . '/' . basename($productImage->image))) {
            unlink(public_path('products') . '/' . basename($productImage->image));
        }
        $productImage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product image deleted successfully',
            'data'=>$productImage
        ], 200);
    }


        
    public function updateProductImage(Request $request, $imageId) {
        $productImage = ProductImage::find($imageId);
    
        if (!$productImage) {
            return response()->json([
                'status' => false,
                'message' => 'Product image not found'
            ], 404);
        }
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
    
            if (!$image->isValid()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid image file'
                ], 400);
            }
    
   
            $extension = $request->file('image')->getClientOriginalExtension();
            $imageName = time(). '.' . $extension;
            $request->file('image')->move(public_path('products'), $imageName);
            $finalImagePathName = $this->baseurl.'products/'. $imageName;
            $productImage->update([
                "image"=> $finalImagePathName,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Product image updated successfully',
                'data' => $productImage
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Image is required'
            ], 400);
        }
    }



    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }

        $product->ProductImages()->delete();
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product and associated images deleted successfully',
            'data' => $product
        ], 200);
    }
}
