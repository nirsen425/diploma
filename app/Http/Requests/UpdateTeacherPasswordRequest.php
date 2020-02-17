<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherPasswordRequest extends FormRequest
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
            'old_password' => ['required'],
            'new_password' => ['required', 'confirmed', 'string', 'min:8', 'regex:/^[a-zA-Z0-9]+$/', 'regex:/[a-z]+/', 'regex:/[0-9]+/', 'regex:/[A-Z]+/'],
            'new_password_confirmation' => ['required', 'string', 'min:8', 'regex:/^[a-zA-Z0-9]+$/', 'regex:/[a-z]+/', 'regex:/[0-9]+/', 'regex:/[A-Z]+/', ],
        ];
    }
}
