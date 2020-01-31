<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacher extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userId = $this->route('teacher')->user()->value('id');
        return [
            'name' => ['required', 'string', 'max:255'],
            'patronymic' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'show' => ['boolean'],
            'rights' => ['required'],
            'short_description' => ['required', 'string', 'min:10', 'max:100'],
            'full_description' => ['required', 'string', 'min:50', 'max:65530'],
            'photo' => ['sometimes', 'image', 'dimensions:min_width=200,min_height=200', 'min_resolve', 'crop_image_square'],
            'login' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => ['sometimes', 'nullable', 'string', 'min:8', 'regex:/^[a-zA-Z0-9]+$/',
                'regex:/[a-z]+/', 'regex:/[0-9]+/', 'regex:/[A-Z]+/']
        ];
    }
}
