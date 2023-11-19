<?php

namespace App\Http\Requests;

use App\Enums\UploadValidationEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['bail', 'required', 'string', 'max:255'],
            'description' => ['bail', 'required', 'string', 'max:255'],
            'file'        => ['bail', 'nullable',
                File::types(array_merge(UploadValidationEnum::IMAGE))
                    ->max(UploadValidationEnum::MAX_FILE_SIZE),
            ],
        ]
            + (
                $this->isMethod('put')
                    ? ['media_uuid' => ['bail', 'nullable', 'uuid', Rule::exists(Media::class, 'uuid')]]
                    : []
            );
    }
}
