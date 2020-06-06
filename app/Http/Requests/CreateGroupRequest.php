<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGroupRequest extends FormRequest
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
            'name' => ['required', 'unique:groups', 'unique:group_stories'],
            'direction' => ['required', 'digits_between:1,2'],
            'course' => ['required', 'digits_between:1,4'],
            'students' => ['required', 'mimes:csv,txt']
        ];
    }
}
