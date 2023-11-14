<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
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
            ]);

        return $data->get();
    }

    public function store(CategoryRequest $request)
    {
        DB::beginTransaction();

        $category = Category::create($request->validated());
        // media

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
