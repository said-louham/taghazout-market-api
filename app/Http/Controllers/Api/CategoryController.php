<?php

namespace App\Http\Controllers\Api;

use App\Enums\UploadCollectionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    public function index()
    {
        $media = QueryBuilder::for(Category::class)->find(23)->getmedia('*');
        ds([
            'media' => $media,
        ]);

        $data = QueryBuilder::for(Category::class)
            ->select([
                'id',
                'name',
                'slug',
                'description',
            ]);

        return CategoryResource::collection($data->get());
    }

    public function store(CategoryRequest $request)
    {
        DB::beginTransaction();

        $data = $request->validated();
        $category = Category::create($data);

        collect($data['files'])->each(function ($file) use ($category) {
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
        DB::beginTransaction();

        $category->updateOrFail($request->validated());
        // update media

        DB::commit();

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json($category);
    }
}
