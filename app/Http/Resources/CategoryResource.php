<?php

namespace App\Http\Resources;

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $media = $request->route()->getControllerClass() == CategoryController::class ? $this->getMedia('*') : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'media' => $media,
        ];
    }
}
