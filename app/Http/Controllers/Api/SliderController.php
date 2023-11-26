<?php

namespace App\Http\Controllers\Api;

use App\Enums\UploadCollectionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\SliderRequest;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use App\Services\UploadService;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class SliderController extends Controller
{
    public function index()
    {
        $data = QueryBuilder::for(Slider::class)
            ->select([
                'id',
                'title',
                'description',
            ])
            ->paginate(_paginatePages());

        return SliderResource::collection($data);
    }

    public function store(SliderRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        $slider = Slider::create($data);

        if ($request->hasFile('file')) {
            $slider->addMedia($data['file'])->usingFileName($data['file']->hashName())->toMediaCollection(UploadCollectionEnum::SlIDERS->value);
        }
        DB::commit();

        return response()->json($slider);
    }

    public function update(SliderRequest $request, Slider $slider)
    {
        $data = $request->validated();

        DB::beginTransaction();

        $slider->updateOrFail(collect($data)->except(['file', 'media_uuid'])->toArray());

        if ($request->hasFile('file') && isset($data['file'])) {
            $slider->addMedia($data['file'])->usingFileName($data['file']->hashName())->toMediaCollection(UploadCollectionEnum::SlIDERS->value);
        } elseif (! isset($data['media_uuid'])) {
            UploadService::deleteMedia(relatedModel: $slider, collection: UploadCollectionEnum::SlIDERS->value);
        }

        DB::commit();

        return response()->json(true);
    }

    public function show(Slider $slider)
    {
        return response()->json($slider);
    }

    public function destroy(Slider $slider)
    {
        $slider->delete();

        return response()->json($slider);
    }
}
