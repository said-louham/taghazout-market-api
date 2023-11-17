<?php

namespace App\Http\Resources;

use App\Enums\UploadCollectionEnum;
use App\Http\Controllers\Api\CategoryController;
use App\Services\UploadService;
use App\Traits\ResourceFilterable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    use ResourceFilterable;

    public function toArray(Request $request): array
    {
        $mediaUuid = UploadService::getMedia(
            mediaModel: $this,
            collection: UploadCollectionEnum::CATEGORIES->value
        );

        return $this->fields([
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
        ]) + ([
            'media_url' => $this->whenNotNull(
                $request->route()->getControllerClass() == CategoryController::class ?
                    $mediaUuid :
                    null
            )]
        );
    }
}
