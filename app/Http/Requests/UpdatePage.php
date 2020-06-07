<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePage extends FormRequest
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
        $pageId= $this->route('page');
        return [
            'title' => ['required', 'string', 'min:3', 'max:20', Rule::unique('pages')->ignore($pageId)],
            'show' => ['boolean'],
            'content' => ['required', 'string', 'min:50', 'max:65530'],
            'meta_headline' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string', 'max:255'],
            'meta_words' => ['required', 'string', 'max:255']
        ];
    }
}
