<?php

namespace App\Http\Resources;

use App\Enums\UploadCollectionEnum;
use App\Http\Controllers\Api\SliderController;
use App\Services\UploadService;
use App\Traits\ResourceFilterable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    use ResourceFilterable;

    public function toArray(Request $request): array
    {
        $mediaUuid = UploadService::getMedia(
            mediaModel: $this,
            collection: UploadCollectionEnum::SlIDERS->value
        );

        return $this->fields([
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
        ]) + ([
            'media_url' => $this->whenNotNull(
                $request->route()->getControllerClass() == SliderController::class ?
                    $mediaUuid :
                    null
            )]
        );
    }
}
