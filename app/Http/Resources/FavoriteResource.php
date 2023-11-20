<?php

namespace App\Http\Resources;

use App\Enums\UploadCollectionEnum;
use App\Models\Product;
use App\Services\UploadService;
use App\Traits\ResourceFilterable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    use ResourceFilterable;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $productMediaUrl = ! is_null($this->product_id) ?
            UploadService::getMedia(mediaModel: Product::find($this->product_id), collection: UploadCollectionEnum::PRODUCTS->value) : null;

        return $this->fields([
            'id'      => $this->id,
            'name'    => $this->name,
            'product' => $this->whenLoaded('product'),
        ]) + ([
            'product_media_url' => $this->whenNotNull($productMediaUrl),
        ]);
    }
}
