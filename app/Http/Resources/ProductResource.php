<?php

namespace App\Http\Resources;

use App\Enums\UploadCollectionEnum;
use App\Http\Controllers\Api\ProductController;
use App\Services\UploadService;
use App\Traits\ResourceFilterable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    use ResourceFilterable;

    public function toArray(Request $request): array
    {
        $mediaUuid = UploadService::getMedia(
            mediaModel: $this,
            collection: UploadCollectionEnum::PRODUCTS->value
        );

        return $this->fields([
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'description'    => $this->description,
            'original_price' => $this->original_price,
            'selling_price'  => $this->selling_price,
            'quantity'       => $this->quantity,
            'trending'       => $this->trending,
            'featured'       => $this->featured,
            'category'       => $this->whenLoaded('category'),
            'ratings'        => $this->whenLoaded('ratings'),
        ]) + ([
            'media_url' => $this->whenNotNull(
                $request->route()->getControllerClass() == ProductController::class && $mediaUuid->isNotEmpty() ?
                    $mediaUuid :
                    null
            )]
        );
    }
}
