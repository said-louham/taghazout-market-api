<?php

namespace App\Http\Resources;

use App\Enums\UploadCollectionEnum;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'media' => UploadService::getMedia(mediaModel: $this, collection: UploadCollectionEnum::CATEGORIES->value),
        ];
    }
}
