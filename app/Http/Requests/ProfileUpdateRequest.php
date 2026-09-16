<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'display_name' => ['nullable', 'string', 'max:255'],
            'bio'          => ['nullable', 'string', 'max:1000'],
            'avatar'       => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}