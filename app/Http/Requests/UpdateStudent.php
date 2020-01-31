<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudent extends FormRequest
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
        $userId = $this->route('student')->user()->value('id');
        return [
            'name' => ['required', 'string', 'max:255'],
            'patronymic' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'rights' => ['required'],
            'student_ticket' => ['required'],
            'login' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => ['sometimes', 'nullable', 'string', 'min:8', 'regex:/^[a-zA-Z0-9]+$/',
                'regex:/[a-z]+/', 'regex:/[0-9]+/', 'regex:/[A-Z]+/']
        ];
    }
}
