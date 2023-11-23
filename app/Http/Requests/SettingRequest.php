<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'website_name' => ['bail', 'required', 'string', 'max:255'],
            'website_url'  => ['bail', 'required', 'url:http,https'],
            'address'      => ['bail', 'required', 'string', 'max:255'],
            'phone'        => ['bail', 'required', 'string', 'max:255'],
            'email'        => ['bail', 'required', 'email', 'max:255'],
            'facebook'     => ['bail', 'nullable', 'url:http,https'],
            'instagram'    => ['bail', 'nullable', 'url:http,https'],

        ];
    }
}
