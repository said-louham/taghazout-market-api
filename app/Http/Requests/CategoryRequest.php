<?php

namespace App\Http\Requests;

use App\Enums\UploadValidationEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'         => ['bail', 'required', 'string', 'max:250', 'min:1'],
            'description'  => ['bail', 'required', 'string', 'max:250', 'min:1'],
            'files'        => ['required', 'array', 'min:1'],
            'files.*.file' => ['bail', 'nullable',
                File::types(array_merge(UploadValidationEnum::IMAGE))
                    ->max(UploadValidationEnum::MAX_FILE_SIZE),
            ],
        ]
            + (
                $this->isMethod('put')
                    ? ['files.*.media_uuid' => ['bail', 'nullable', 'uuid', Rule::exists(Media::class, 'uuid')]]
                    : []
            );
    }
}
