<?php

namespace App\Http\Controllers\Api;

use App\Enums\UploadCollectionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Cart;
use App\Models\Favorit;
use App\Models\Product;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    public function index()
    {
        $data = QueryBuilder::for(Product::class)
            ->select([
                'id',
                'name',
                'slug',
                'description',
                'original_price',
                'selling_price',
                'quantity',
                'trending',
                'featured',
                'category_id',
            ])->with([
                'category:id,name,description',
                'ratings:id,comment,rating,product_id,user_id,created_at' => [
                    'user:id,full_name',
                ],
            ]);

        return ProductResource::collection($data->get());
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        $product = Product::create($data);

        collect($data['files'])->whereNotNull('file')->each(function ($file) use ($product) {
            $product->addMedia($file)->usingFileName($file->hashName())->toMediaCollection(UploadCollectionEnum::PRODUCTS->value);
        });

        DB::commit();

        return response()->json($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();

        DB::beginTransaction();

        $product->updateOrFail(collect($data)->except(['name', 'files'])->toArray());

        $modelMedia = UploadService::getMedia(mediaModel: $product, collection: UploadCollectionEnum::PRODUCTS->value)->pluck('uuid')->toArray();

        $deletedMedia = collect($data['files'])->whereNotNull('file')->each(static function ($file, $key) use ($request, $product, $modelMedia) {

            if ($request->hasFile('files.'.$key.'.file')) {
                $product->addMedia($file['file'])->usingFileName($file['file']->hashName())->toMediaCollection(UploadCollectionEnum::PRODUCTS->value);
            } elseif (isset($file['media_uuid'])) {
                $itemKey = array_search($file['media_uuid'], $modelMedia);

                if ($itemKey !== false) {
                    unset($modelMedia[$itemKey]);
                }
            }
        })->flatten()->toArray();
        // TODO: refactore the deletedMedia array
        if (! is_null($deletedMedia)) {
            Media::whereIn('uuid', $deletedMedia)->delete();
        }

        DB::commit();

        return response()->json(true);
    }

    public function updateProductImage(Request $request, $product_id, $imageId)
    {
        return 'update product media';
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();

        $product->ratings()->delete();
        Favorit::find($product->id)->dele();
        Cart::find($product->id)->delete();
        $product->delete();

        DB::commit();

        return response()->json($product);
    }
}
