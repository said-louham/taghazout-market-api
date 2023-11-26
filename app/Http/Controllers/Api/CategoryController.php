<?php

namespace App\Http\Controllers\Api;

use App\Enums\UploadCollectionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\UploadService;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    public function index()
    {
        $data = QueryBuilder::for(Category::class)
            ->select([
                'id',
                'name',
                'slug',
                'description',
            ])
            ->paginate(_paginatePages());

        return CategoryResource::collection($data);
    }

    public function store(CategoryRequest $request)
    {
        DB::beginTransaction();

        $data = $request->validated();

        $category = Category::create($data);

        collect($data['files'])->whereNotNull('file')->each(function ($file) use ($category) {
            $category->addMedia($file['file'])->usingFileName($file['file']->hashName())->toMediaCollection(UploadCollectionEnum::CATEGORIES->value);
        });

        DB::commit();

        return response()->json($category);
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        DB::beginTransaction();

        $category->updateOrFail(collect($data)->except(['files'])->toArray());

        $modelMedia = UploadService::getMedia(mediaModel: $category, collection: UploadCollectionEnum::CATEGORIES->value)->pluck('uuid')->toArray();

        $deletedMedia = collect($data['files'])->whereNotNull('file')->each(static function ($file, $key) use ($request, $category, $modelMedia) {

            if ($request->hasFile('files.' . $key . '.file')) {
                $category->addMedia($file['file'])->usingFileName($file['file']->hashName())->toMediaCollection(UploadCollectionEnum::PRODUCTS->value);
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

    public function update2(CategoryRequest $request, Category $category)
    {
        DB::beginTransaction();

        $data       = $request->validated();
        $modelMedia = UploadService::getMedia(mediaModel: $category, collection: UploadCollectionEnum::CATEGORIES->value)->pluck('uuid')->toArray();

        foreach ($data['files'] as $key => $file) {
            if ($request->hasFile('files.' . $key . '.file')) {
                $category->addMedia($file['file'])->usingFileName($file['file']->hashName())->toMediaCollection(UploadCollectionEnum::PRODUCTS->value);
            } elseif (isset($file['media_uuid'])) {
                $itemKey = array_search($file['media_uuid'], $modelMedia);

                if ($itemKey !== false) {
                    unset($modelMedia[$itemKey]);
                }
            }
        }

        $deletedMedia = array_filter($modelMedia, function ($media) use ($deletedMedia) {
            return ! in_array($media, $deletedMedia);
        });

        if (! is_null($deletedMedia)) {
            Media::whereIn('uuid', $deletedMedia)->delete();
        }

        DB::commit();

        return response()->json(true);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        $category->products()->delete();

        return response()->json($category);
    }
}
