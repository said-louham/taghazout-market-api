<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{

    public $baseurl="http://127.0.0.1:8000/";

    public function index()
    {
        $categories = Category::latest()->get();
        return response()->json([
            'categories' => $categories,
        ]);
    }


    public function store(Request $request)
    {


        $validatedData = Validator::make($request->all(), 
        [
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required',
        ]);


        if($validatedData->fails()){
            return response()->json([
                'message' => 'validation error',
                'error' => $validatedData->errors()
            ]);
        }

        $category = new Category();
        $category->name = $request->name;
        $category->status = $request->status;
        $category->description = $request->description;


        $slug=Str::slug($request->input('name'));
        $counter=1;
        while(Category::whereSlug($slug)->exists()){
            $slug=$slug.'-'.$counter;
            $counter++;
        }
        
        $category->slug = $slug;

        if($request->hasFile('image')) {
                $imageName = time().'.'. $request->image->extension();
                $imageName=$this->baseurl.'category/'.$imageName;
                $request->image->move(public_path('category'), $imageName);
                $category->image=$imageName;
        }
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category added successfully!',
            'data' => $category
        ], 201);

    }

   
    public function show(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ]);
        }

        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }



    public function update(Request $request, string $id)
    {
      
        $category = Category::find($id);


        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found!',
            ]);
        }
        $category->name = $request->name;
        $category->status = $request->status;
        $category->description = $request->description;

        $slug = Str::slug($request->input('name'));
        $counter = 1;
        while(Category::whereSlug($slug)->where('id', '!=', $id)->exists()){
            $slug = $slug.'-'.$counter;
            $counter++;
        }
        $category->slug = $slug;


        if($request->hasFile('image')) {
            $imageName = time().'.'. $request->image->extension();
            $imageName=$this->baseurl.'category/'.$imageName;
            $request->image->move(public_path('category'), $imageName);
            $category->image = $imageName;
        }

        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!',
            'data' => $category
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
                
            ]);
        }
        if (file_exists(public_path('category').'/'. basename($category->image))){
            unlink(public_path('category').'/'.basename($category->image));
        }  
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!',
            'data'=>$category
        ]);
    }
}
