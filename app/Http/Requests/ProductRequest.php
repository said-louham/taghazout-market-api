<?php

namespace App\Http\Requests;

use App\Enums\UploadValidationEnum;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['bail', 'required', 'integer', Rule::exists(Category::class, 'id')],
            'name'        => ['bail', 'required', 'max:255',  $this->isMethod('put') ?
                Rule::unique(Product::class, 'name')->ignore($this->product->id) :
                Rule::unique(Product::class, 'name'), ],
            'description'    => ['bail', 'required', 'string'],
            'original_price' => ['bail', 'required', 'numeric', 'lte:selling_price', 'min:0'],
            'selling_price'  => ['bail', 'required', 'numeric', 'gte:original_price', 'min:0'],
            'quantity'       => ['bail', 'required', 'numeric', 'min:0'],
            'trending'       => ['bail', 'integer', 'nullable'],
            'featured'       => ['bail', 'integer', 'nullable'],
            'status'         => ['bail', 'integer', 'nullable'],
            'files.*.file'   => [
                'bail',
                'nullable',
                File::types(array_merge(UploadValidationEnum::IMAGE))
                    ->max(UploadValidationEnum::MAX_FILE_SIZE),
            ],
        ] + (
            $this->isMethod('put')
                ? ['files.*.media_uuid' => ['bail', 'nullable', 'uuid', Rule::exists(Media::class, 'uuid')]]
                : []
        );

    }
}
