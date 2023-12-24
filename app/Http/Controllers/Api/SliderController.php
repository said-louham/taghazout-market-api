<?php

namespace App\Http\Controllers\Api;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $baseurl="http://127.0.0.1:8000/";
    public function index()
    {
        $sliders=Slider::latest()->get();
        return response()->json($sliders);
        
    }


    public function store(Request $request)
    {
        $validateSlider = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|max:255',
            'description' => 'required|string|max:255',
        ]);

        if($validateSlider->fails()){
            return response()->json([
                'message' => 'validation error',
                'error' => $validateSlider->errors()
            ]);
        }
        if ($request->hasFile('image')) {
            $file=$request->file('image');
            $extension = $file->getClientOriginalExtension();
            $imageName = time() .'.' . $extension;
            $file->move(public_path('Sliders'), $imageName);
          $ImageNamedata = $this->baseurl.'Sliders/'. $imageName;
        }


      $slider=slider::create([
            'title'=>  $request->title,
            'description'=>  $request->description,
            'image'=>  $ImageNamedata,
            'status'=>   $request->status ===true ? 1:0 ,
        ]);
        return response()->json([
            'message' => 'Slider Inserted Successfully',
            'data' => $slider

        ]);
    }



 

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        $validatedData=$request->validated();
        if($request->has('image')){
           $file=$request->image;
           $imageName=time().'_'.$file->getClientOriginalName();
           $file->move(public_path('Sliders'),$imageName);
    
          if (file_exists(public_path('Sliders/').basename($slider->image))){
               unlink(public_path('Sliders').'/'.basename($slider->image));
           }       
           $slider->image=$this->baseurl.'Sliders/'.$imageName;
       }
       $validatedData['status'] = $request->status ==true? 1:0;
       $slider->update([
           'title'=>  $validatedData['title'],
           'description'=>  $validatedData['description'],
           'image'=>  $slider->image,
           'status'=>  $validatedData['status'],
       ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        $slider->delete();
        return response()->json([
            'message' => 'Slider Deleted Successfully',
            'status' => true,
            'data'=>$slider
            ], 200);
        
    }
}
