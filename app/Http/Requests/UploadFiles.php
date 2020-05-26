<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFiles extends FormRequest
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
        return [
            'name' => ['required', 'string', 'unique:files'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Файл должен иметь имя',
            'name.unique' => 'Файл с таким именем уже существует',
        ];
    }
}
